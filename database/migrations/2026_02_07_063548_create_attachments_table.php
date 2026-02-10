<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('file_path', 500);
            $table->string('file_name', 255);
            $table->string('original_name', 255);
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('size');
            $table->string('disk', 50)->default('s3');
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('order')->default(0);
            $table->foreignId('uploader_id')->nullable()->constrained('users');
            $table->morphs('attachable');
            $table->timestamps();
            $table->softDeletes();

            $table->index('file_name');

            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->fullText(['original_name', 'description']);
            }
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
