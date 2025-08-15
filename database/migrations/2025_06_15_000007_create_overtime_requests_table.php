<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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

            // Tambahan: Nomor SPT
            $table->string('spt_number', 100);
            $table->index('spt_number'); // bukan unique, karena satu SPT bisa untuk banyak pegawai

            $table->string('spt_file')->nullable();

            // Approval system
            $table->enum('status', ['pending', 'feedback_submitted', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_note')->nullable();

            $table->boolean('feedback_submitted')->default(false);

            // Unique composite: cegah duplikasi slot waktu untuk pegawai yang sama
            $table->unique(['user_id', 'overtime_date', 'start_time', 'end_time'], 'uniq_user_date_time');

            // (Opsional) CHECK: jam mulai < jam selesai (MySQL 8+/Postgres)
            // $table->check('start_time < end_time');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('overtime_requests');
    }
};
