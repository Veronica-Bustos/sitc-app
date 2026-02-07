<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Attachment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
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

        $this->actingAs(User::factory()->create());
    }

    #[Test]
    public function index_displays_view(): void
    {
        $attachments = Attachment::factory()->count(3)->create();

        $response = $this->get(route('attachments.index'));

        $response->assertOk();
        $response->assertViewIs('attachment.index');
        $response->assertViewHas('attachments', $attachments);
    }

    #[Test]
    public function create_displays_view(): void
    {
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
        $item = \App\Models\Item::factory()->create();
        $file_path = fake()->word();
        $file_name = fake()->word();
        $original_name = fake()->word();
        $mime_type = fake()->word();
        $size = fake()->numberBetween(1, 999999);
        $disk = fake()->word();
        $is_featured = fake()->boolean();
        $order = fake()->numberBetween(0, 100);

        $response = $this->post(route('attachments.store'), [
            'file_path' => $file_path,
            'file_name' => $file_name,
            'original_name' => $original_name,
            'mime_type' => $mime_type,
            'size' => $size,
            'disk' => $disk,
            'is_featured' => $is_featured,
            'order' => $order,
            'attachable_id' => $item->id,
            'attachable_type' => \App\Models\Item::class,
        ]);

        $attachments = Attachment::query()
            ->where('file_path', $file_path)
            ->where('file_name', $file_name)
            ->where('original_name', $original_name)
            ->where('mime_type', $mime_type)
            ->where('size', $size)
            ->where('disk', $disk)
            ->where('is_featured', $is_featured)
            ->where('order', $order)
            ->get();
        $this->assertCount(1, $attachments);
        $attachment = $attachments->first();

        $response->assertRedirect(route('attachments.index'));
        $response->assertSessionHas('attachment.id', $attachment->id);
    }

    #[Test]
    public function show_displays_view(): void
    {
        $attachment = Attachment::factory()->create();

        $response = $this->get(route('attachments.show', $attachment));

        $response->assertOk();
        $response->assertViewIs('attachment.show');
        $response->assertViewHas('attachment', $attachment);
    }

    #[Test]
    public function edit_displays_view(): void
    {
        $attachment = Attachment::factory()->create();

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
        $attachment = Attachment::factory()->create();
        $file_path = fake()->word();
        $file_name = fake()->word();
        $original_name = fake()->word();
        $mime_type = fake()->word();
        $size = fake()->randomNumber();
        $disk = fake()->word();
        $is_featured = fake()->boolean();
        $order = fake()->numberBetween(-10000, 10000);

        $response = $this->put(route('attachments.update', $attachment), [
            'file_path' => $file_path,
            'file_name' => $file_name,
            'original_name' => $original_name,
            'mime_type' => $mime_type,
            'size' => $size,
            'disk' => $disk,
            'is_featured' => $is_featured,
            'order' => $order,
        ]);

        $attachment->refresh();

        $response->assertRedirect(route('attachments.index'));
        $response->assertSessionHas('attachment.id', $attachment->id);

        $this->assertEquals($file_path, $attachment->file_path);
        $this->assertEquals($file_name, $attachment->file_name);
        $this->assertEquals($original_name, $attachment->original_name);
        $this->assertEquals($mime_type, $attachment->mime_type);
        $this->assertEquals($size, $attachment->size);
        $this->assertEquals($disk, $attachment->disk);
        $this->assertEquals($is_featured, $attachment->is_featured);
        $this->assertEquals($order, $attachment->order);
    }

    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $attachment = Attachment::factory()->create();

        $response = $this->delete(route('attachments.destroy', $attachment));

        $response->assertRedirect(route('attachments.index'));

        $this->assertSoftDeleted($attachment);
    }
}
