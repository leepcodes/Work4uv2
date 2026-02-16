<?php
// database/migrations/2024_01_01_000002_create_tutors_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutors', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('follow_id')->nullable();
            $table->string('webinairs_id')->nullable();
            $table->string('elearnings_id')->nullable();
            $table->string('reviews_id')->nullable();
            $table->integer('student_count')->default(0);
            $table->text('skills')->nullable();
            $table->string('grad_student')->nullable();
            $table->integer('current_student')->default(0);
            $table->integer('publish_video')->default(0);
            $table->integer('subj_created')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutors');
    }
};