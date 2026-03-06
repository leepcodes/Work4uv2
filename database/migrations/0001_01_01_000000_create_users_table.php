<?php
// database/migrations/2024_01_01_000001_create_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('birthday')->nullable();
            $table->string('gender')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('service')->nullable();
            $table->text('languages')->nullable();
            $table->text('subjects')->nullable();
            $table->text('photo')->nullable();
            $table->text('certificates')->nullable();
            $table->text('documents')->nullable();
            $table->text('description')->nullable();
            $table->boolean('free_class')->default(false);
            $table->integer('verification_step')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};