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
        Schema::create('decisao_reuniao', function (Blueprint $table) {
            $table->unsignedBigInteger('decisao_id')->index('decisao_reuniao_decisao_id_foreign');
            $table->unsignedBigInteger('reuniao_id')->index('decisao_reuniao_reuniao_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decisao_reuniao');
    }
};
