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

        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained();
            $table->foreignId('from_location_id')->nullable()->constrained('locations');
            $table->foreignId('to_location_id')->nullable()->constrained('locations');
            $table->string('movement_type', 20);
            $table->foreignId('user_id')->constrained();
            $table->integer('quantity')->default(1);
            $table->text('notes')->nullable();
            $table->string('reason', 100)->nullable();
            $table->string('reference_document', 50)->nullable();
            $table->dateTime('performed_at');
            $table->timestamps();

            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->fullText(['reason', 'notes']);
            }
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
