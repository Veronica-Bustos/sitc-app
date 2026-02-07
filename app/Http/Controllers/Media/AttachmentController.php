<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\StoreRequest as AttachmentStoreRequest;
use App\Http\Requests\Media\UpdateRequest as AttachmentUpdateRequest;
use App\Models\Attachment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttachmentController extends Controller
{
    public function index(Request $request): View
    {
        $attachments = Attachment::all();

        return view('attachment.index', [
            'attachments' => $attachments,
        ]);
    }

    public function create(Request $request): View
    {
        return view('attachment.create');
    }

    public function store(AttachmentStoreRequest $request): RedirectResponse
    {
        $attachment = Attachment::create($request->validated());

        $request->session()->flash('attachment.id', $attachment->id);

        return redirect()->route('attachments.index');
    }

    public function show(Request $request, Attachment $attachment): View
    {
        return view('attachment.show', [
            'attachment' => $attachment,
        ]);
    }

    public function edit(Request $request, Attachment $attachment): View
    {
        return view('attachment.edit', [
            'attachment' => $attachment,
        ]);
    }

    public function update(AttachmentUpdateRequest $request, Attachment $attachment): RedirectResponse
    {
        $attachment->update($request->validated());

        $request->session()->flash('attachment.id', $attachment->id);

        return redirect()->route('attachments.index');
    }

    public function destroy(Request $request, Attachment $attachment): RedirectResponse
    {
        $attachment->delete();

        return redirect()->route('attachments.index');
    }
}
