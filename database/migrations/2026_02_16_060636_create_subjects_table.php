<?php
// database/migrations/2024_01_01_000004_create_subjects_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tutor_id');
            $table->string('subject_title');
            $table->string('class_start')->nullable();
            $table->string('suitable_age')->nullable();
            $table->string('currently_enrolled')->nullable();
            $table->text('description')->nullable();
            $table->binary('image_file')->nullable();
            $table->integer('two_class')->nullable();
            $table->integer('three_class')->nullable();
            $table->integer('five_class')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};