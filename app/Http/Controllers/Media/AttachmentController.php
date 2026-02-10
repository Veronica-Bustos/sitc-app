<?php

namespace App\Http\Controllers\Media;

use App\Filters\AttachmentFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Media\StoreRequest;
use App\Http\Requests\Media\UpdateRequest;
use App\Models\Attachment;
use App\Models\InventoryMovement;
use App\Models\Item;
use App\Models\MaintenanceRecord;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, AttachmentFilter $filters): View
    {
        $attachments = Attachment::query()
            ->with(['attachable', 'uploader'])
            ->filter($filters)
            ->orderByDesc('created_at')
            ->paginate(24)
            ->withQueryString();

        // Get filter options
        $mimeTypes = AttachmentFilter::getMimeTypeCategories();
        $attachableTypes = AttachmentFilter::getAttachableTypes();
        $uploaders = User::query()
            ->whereHas('attachments')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('attachment.index', compact(
            'attachments',
            'mimeTypes',
            'attachableTypes',
            'uploaders'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        // Get entities for dropdowns
        $items = Item::query()
            ->select('id', 'name', 'code')
            ->orderBy('name')
            ->get();

        $movements = InventoryMovement::query()
            ->with('item:id,name')
            ->select('id', 'item_id', 'movement_type', 'performed_at')
            ->orderByDesc('performed_at')
            ->limit(100)
            ->get();

        $maintenanceRecords = MaintenanceRecord::query()
            ->with('item:id,name')
            ->select('id', 'item_id', 'type', 'request_date')
            ->orderByDesc('request_date')
            ->limit(100)
            ->get();

        // Preselected values from query params
        $preselectedType = $request->input('type'); // item, movement, maintenance
        $preselectedId = $request->input('id');

        return view('attachment.create', compact(
            'items',
            'movements',
            'maintenanceRecords',
            'preselectedType',
            'preselectedId'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $files = $request->file('files', []);

        $attachableType = match ($validated['attachable_type']) {
            'item' => Item::class,
            'movement' => InventoryMovement::class,
            'maintenance' => MaintenanceRecord::class,
            default => null,
        };

        if (! $attachableType) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['attachable_type' => __('Invalid attachable type')]);
        }

        $uploadedCount = 0;

        foreach ($files as $file) {
            // Generate unique filename
            $fileName = uniqid('att_', true).'.'.$file->getClientOriginalExtension();
            $filePath = 'attachments/'.date('Y/m');

            // Store file
            $storedPath = $file->storeAs($filePath, $fileName, 'local');

            if ($storedPath) {
                Attachment::create([
                    'file_path' => $storedPath,
                    'file_name' => $fileName,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'disk' => 'local',
                    'description' => $validated['description'] ?? null,
                    'is_featured' => $validated['is_featured'] ?? false,
                    'order' => $validated['order'] ?? 0,
                    'uploader_id' => auth()->id(),
                    'attachable_id' => $validated['attachable_id'],
                    'attachable_type' => $attachableType,
                ]);

                $uploadedCount++;
            }
        }

        return redirect()
            ->route('attachments.index')
            ->with('success', __(':count files uploaded successfully', ['count' => $uploadedCount]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Attachment $attachment): View
    {
        $attachment->load(['attachable', 'uploader']);

        return view('attachment.show', compact('attachment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Attachment $attachment): View
    {
        $this->authorize('update', $attachment);

        $attachment->load(['attachable', 'uploader']);

        // Get related entities for the dropdown
        $attachableType = match ($attachment->attachable_type) {
            Item::class => 'item',
            InventoryMovement::class => 'movement',
            MaintenanceRecord::class => 'maintenance',
            default => null,
        };

        $items = Item::query()
            ->select('id', 'name', 'code')
            ->orderBy('name')
            ->get();

        $movements = InventoryMovement::query()
            ->with('item:id,name')
            ->select('id', 'item_id', 'movement_type', 'performed_at')
            ->orderByDesc('performed_at')
            ->limit(100)
            ->get();

        $maintenanceRecords = MaintenanceRecord::query()
            ->with('item:id,name')
            ->select('id', 'item_id', 'type', 'request_date')
            ->orderByDesc('request_date')
            ->limit(100)
            ->get();

        return view('attachment.edit', compact(
            'attachment',
            'attachableType',
            'items',
            'movements',
            'maintenanceRecords'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Attachment $attachment): RedirectResponse
    {
        $this->authorize('update', $attachment);

        $validated = $request->validated();

        // Only update metadata, not the file itself
        $attachment->update([
            'description' => $validated['description'] ?? null,
            'is_featured' => $validated['is_featured'] ?? false,
            'order' => $validated['order'] ?? 0,
        ]);

        return redirect()
            ->route('attachments.show', $attachment)
            ->with('success', __('Attachment updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Attachment $attachment): RedirectResponse
    {
        $this->authorize('delete', $attachment);

        // Delete the physical file
        if (Storage::disk('local')->exists($attachment->file_path)) {
            Storage::disk('local')->delete($attachment->file_path);
        }

        // Delete the record
        $attachment->delete();

        return redirect()
            ->route('attachments.index')
            ->with('success', __('Attachment deleted successfully'));
    }

    /**
     * Download the attachment file.
     */
    public function download(Request $request, Attachment $attachment): StreamedResponse
    {
        $this->authorize('download', $attachment);

        if (! Storage::disk('local')->exists($attachment->file_path)) {
            abort(404, __('File not found'));
        }

        return Storage::disk('local')->download(
            $attachment->file_path,
            $attachment->original_name
        );
    }

    /**
     * Preview the attachment (for images and PDFs).
     */
    public function preview(Request $request, Attachment $attachment): StreamedResponse
    {
        $this->authorize('view', $attachment);

        if (! Storage::disk('local')->exists($attachment->file_path)) {
            abort(404, __('File not found'));
        }

        $file = Storage::disk('local')->get($attachment->file_path);

        return response()->stream(function () use ($file) {
            echo $file;
        }, 200, [
            'Content-Type' => $attachment->mime_type,
            'Content-Disposition' => 'inline; filename="'.$attachment->original_name.'"',
        ]);
    }
}
