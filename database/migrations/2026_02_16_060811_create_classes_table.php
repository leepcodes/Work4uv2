<?php
// database/migrations/2024_01_01_000007_create_classes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id')->unique();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('tutor_id');
            $table->unsignedBigInteger('schedule_id')->unique();
            $table->integer('session_count')->default(0);
            $table->enum('status', ['null', 'failed', 'completed'])->default('null');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};