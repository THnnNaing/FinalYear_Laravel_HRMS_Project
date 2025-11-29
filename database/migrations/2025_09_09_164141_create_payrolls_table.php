<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('month'); // Stores first day of the month (e.g., 2025-09-01)
            $table->decimal('basic_salary', 10, 2);
            $table->integer('worked_days')->nullable();
            $table->integer('unpaid_leave_days')->default(0);
            $table->decimal('total_bonus', 10, 2)->default(0.00);
            $table->decimal('total_deduction', 10, 2)->default(0.00);
            $table->decimal('net_salary', 10, 2);
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->unique(['employee_id', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};