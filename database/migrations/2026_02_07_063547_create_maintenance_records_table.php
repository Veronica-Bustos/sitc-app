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

        Schema::create('maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained();
            $table->date('request_date');
            $table->date('intervention_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->string('type', 30);
            $table->string('status', 20);
            $table->string('priority', 10);
            $table->text('description');
            $table->text('diagnosis')->nullable();
            $table->text('actions_taken')->nullable();
            $table->text('parts_replaced')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->foreignId('technician_id')->nullable()->constrained('users');
            $table->foreignId('requester_id')->nullable()->constrained('users');
            $table->date('next_maintenance_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_records');
    }
};
