<?php
// database/migrations/2024_01_01_000006_create_requests_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('tutor_id');
            $table->text('message')->nullable();
            $table->integer('custom_class_count')->nullable();
            $table->decimal('tutor_custom_price', 10, 2)->nullable();
            $table->string('request_sched')->nullable();
            $table->integer('class_avail')->nullable();
            $table->decimal('custom_class_price', 10, 2)->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};