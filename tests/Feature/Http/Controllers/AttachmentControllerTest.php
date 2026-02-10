<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Models\Attachment;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Media\AttachmentController
 */
final class AttachmentControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $this->seed(PermissionsSeeder::class);
    }

    private function actingAsUserWithPermissions(array $permissions): User
    {
        $user = User::factory()->create();
        $user->givePermissionTo($permissions);
        $this->actingAs($user);

        return $user;
    }

    private function actingAsUserWithoutPermissions(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        return $user;
    }

    private function actingAsAttachmentManager(): User
    {
        return $this->actingAsUserWithPermissions([
            PermissionEnum::ATTACHMENTS_VIEW->value,
            PermissionEnum::ATTACHMENTS_CREATE->value,
            PermissionEnum::ATTACHMENTS_EDIT->value,
            PermissionEnum::ATTACHMENTS_DELETE->value,
        ]);
    }

    private function actingAsAttachmentViewer(): User
    {
        return $this->actingAsUserWithPermissions([
            PermissionEnum::ATTACHMENTS_VIEW->value,
        ]);
    }

    #[Test]
    public function index_displays_view(): void
    {
        $this->actingAsAttachmentManager();
        Attachment::factory()->count(3)->create();

        $response = $this->get(route('attachments.index'));

        $response->assertOk();
        $response->assertViewIs('attachment.index');
        $response->assertViewHas('attachments', fn($attachments) => $attachments instanceof LengthAwarePaginator);
    }

    #[Test]
    public function create_displays_view(): void
    {
        $this->actingAsAttachmentManager();

        $response = $this->get(route('attachments.create'));

        $response->assertOk();
        $response->assertViewIs('attachment.create');
    }

    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Media\AttachmentController::class,
            'store',
            \App\Http\Requests\Media\StoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $this->actingAsAttachmentManager();
        $item = Item::factory()->create();

        $file = UploadedFile::fake()->image('test-image.jpg');

        $response = $this->post(route('attachments.store'), [
            'files' => [$file],
            'attachable_type' => 'item',
            'attachable_id' => $item->id,
            'description' => 'Test description',
            'is_featured' => true,
            'order' => 1,
        ]);

        $response->assertRedirect(route('attachments.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('attachments', 1);
        $attachment = Attachment::first();
        $this->assertEquals($item->id, $attachment->attachable_id);
        $this->assertEquals('Test description', $attachment->description);
        $this->assertTrue($attachment->is_featured);
        $this->assertEquals(1, $attachment->order);

        Storage::disk('local')->assertExists($attachment->file_path);
    }

    #[Test]
    public function show_displays_view(): void
    {
        $this->actingAsUserWithoutPermissions();
        $attachment = Attachment::factory()->create();

        $response = $this->get(route('attachments.show', $attachment));

        $response->assertOk();
        $response->assertViewIs('attachment.show');
        $response->assertViewHas('attachment', $attachment);
    }

    #[Test]
    public function edit_displays_view(): void
    {
        $user = $this->actingAsAttachmentManager();
        $attachment = Attachment::factory()->create(['uploader_id' => $user->id]);

        $response = $this->get(route('attachments.edit', $attachment));

        $response->assertOk();
        $response->assertViewIs('attachment.edit');
        $response->assertViewHas('attachment', $attachment);
    }

    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Media\AttachmentController::class,
            'update',
            \App\Http\Requests\Media\UpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $user = $this->actingAsAttachmentManager();
        $attachment = Attachment::factory()->create(['uploader_id' => $user->id]);

        $response = $this->put(route('attachments.update', $attachment), [
            'description' => 'Updated description',
            'is_featured' => true,
            'order' => 5,
        ]);

        $response->assertRedirect(route('attachments.show', $attachment));
        $response->assertSessionHas('success');

        $attachment->refresh();
        $this->assertEquals('Updated description', $attachment->description);
        $this->assertTrue($attachment->is_featured);
        $this->assertEquals(5, $attachment->order);
    }

    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $this->actingAsAttachmentManager();
        $attachment = Attachment::factory()->create();

        $response = $this->delete(route('attachments.destroy', $attachment));

        $response->assertRedirect(route('attachments.index'));
        $response->assertSessionHas('success');

        $this->assertSoftDeleted($attachment);
    }

    #[Test]
    public function download_returns_file(): void
    {
        $this->actingAsUserWithoutPermissions();
        $file = UploadedFile::fake()->image('test.jpg');
        $path = $file->store('attachments/test', 'local');

        $attachment = Attachment::factory()->create([
            'file_path' => $path,
            'file_name' => 'test.jpg',
            'original_name' => 'original-test.jpg',
        ]);

        $response = $this->get(route('attachments.download', $attachment));

        $response->assertOk();
        $response->assertHeader('content-disposition', 'attachment; filename=original-test.jpg');
    }

    #[Test]
    public function unauthorized_users_cannot_edit(): void
    {
        $this->actingAsAttachmentViewer();

        $attachment = Attachment::factory()->create();

        $response = $this->get(route('attachments.edit', $attachment));

        $response->assertForbidden();
    }

    #[Test]
    public function guest_cannot_access_attachments(): void
    {
        $response = $this->get(route('attachments.index'));
        $response->assertRedirect(route('login'));
    }
}
