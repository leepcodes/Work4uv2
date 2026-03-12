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
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('tutor_id');
            $table->unsignedBigInteger('schedule_id');
            $table->integer('class_number'); // get the max class nmber from schedle and add 1
            $table->date('schedule') ->nullable(); //date and time naman 
            $table->string('video_link')->nullable();
            $table->text('notes')->nullable();
            $table->text('documents')->nullable();
            $table->dateTime('scheduled_at')->nullable();
            $table->enum('status', [ 'to_be_scheduled','upcoming','done','failed','completed'])->default('to_be_scheduled');
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