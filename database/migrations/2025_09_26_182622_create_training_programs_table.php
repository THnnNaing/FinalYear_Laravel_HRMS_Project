<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('details')->nullable();
            $table->foreignId('instructor_employee_id')->constrained('employees')->onDelete('restrict');
            $table->json('available_days'); // Store as JSON array, e.g., ["Mon", "Tue"]
            $table->integer('available_total_employees')->unsigned();
            $table->string('available_time'); // e.g., "4:00pm to 6:00pm"
            $table->enum('status', ['available', 'active', 'ended'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_programs');
    }
};