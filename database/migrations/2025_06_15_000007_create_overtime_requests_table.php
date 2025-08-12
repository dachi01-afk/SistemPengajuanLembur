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
        Schema::create('overtime_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->date('overtime_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('reason');
            $table->string('spt_file')->nullable();

            // Approval system
            $table->enum('status', ['pending', 'feedback_submitted', 'approved', 'rejected'])
                ->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->OnDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overtime_requests');
    }
};
