<?php
// database/migrations/2024_01_01_000005_create_schedules_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
      Schema::create('schedules', function (Blueprint $table) {
        $table->id();
        $table->uuid('uuid')->unique();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('subject_id');
        $table->unsignedBigInteger('tutor_id');
        $table->decimal('tutor_custom_price', 10, 2);
        $table->integer('total_class_count');
        $table->integer('complete_class_count');
        $table->integer('remaining_class_count');
        $table->enum('status', ['scheduled', 'completed', 'upcoming', 'done'])->default('upcoming');
        $table->date('available_date')->nullable();
        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};