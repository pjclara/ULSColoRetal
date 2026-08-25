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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('username')->nullable();
            $table->string('abrev')->nullable();
            $table->string('email')->unique();
            $table->boolean('ativo')->default(true);
            $table->boolean('activo')->default(true);
            $table->char('equipa')->nullable();
            $table->char('sexo')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->default('$2y$10$0aDASYf11oYoicCMKmpoNug3NhyvVvBFuh2A7LDJS0nJOKIa63tIO');
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->timestamp('last_login_time')->nullable();
            $table->string('last_login_ip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
