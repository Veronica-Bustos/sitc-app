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

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('current_location_id')->nullable()->constrained('locations');
            $table->string('status', 20);
            $table->string('condition', 20);
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 12, 2)->nullable();
            $table->decimal('current_value', 12, 2)->nullable();
            $table->string('serial_number', 100)->nullable()->index();
            $table->string('brand', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('supplier', 150)->nullable();
            $table->date('warranty_expiry')->nullable();
            $table->string('barcode', 100)->nullable()->index();
            $table->string('qr_code', 100)->nullable();
            $table->integer('minimum_stock')->default(0);
            $table->string('unit_of_measure', 20)->nullable();
            $table->decimal('weight_kg', 8, 2)->nullable();
            $table->string('dimensions', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');

            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->fullText(['name', 'description', 'brand', 'model']);
            }
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
