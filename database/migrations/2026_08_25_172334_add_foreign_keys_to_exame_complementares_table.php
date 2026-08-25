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
        Schema::table('exame_complementares', function (Blueprint $table) {
            $table->foreign(['created_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['deleted_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['referenciacao_id'])->references(['id'])->on('referenciacao_centro_de_referencias')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['updated_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exame_complementares', function (Blueprint $table) {
            $table->dropForeign('exame_complementares_created_by_id_foreign');
            $table->dropForeign('exame_complementares_deleted_by_id_foreign');
            $table->dropForeign('exame_complementares_referenciacao_id_foreign');
            $table->dropForeign('exame_complementares_updated_by_id_foreign');
        });
    }
};
