<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('overtime_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('overtime_request_id')->constrained('overtime_requests')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Pegawai yang memberikan feedbac
            $table->text('activity_description');
            $table->string('documentation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('overtime_feedback');
    }
};