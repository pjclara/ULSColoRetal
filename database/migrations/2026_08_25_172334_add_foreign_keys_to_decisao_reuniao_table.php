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
        Schema::table('decisao_reuniao', function (Blueprint $table) {
            $table->foreign(['decisao_id'])->references(['id'])->on('decisao_terapeuticas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['reuniao_id'])->references(['id'])->on('reuniao_decisao_terapeuticas')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('decisao_reuniao', function (Blueprint $table) {
            $table->dropForeign('decisao_reuniao_decisao_id_foreign');
            $table->dropForeign('decisao_reuniao_reuniao_id_foreign');
        });
    }
};
