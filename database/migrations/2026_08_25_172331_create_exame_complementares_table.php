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
        Schema::create('exame_complementares', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('referenciacao_id')->index('exame_complementares_referenciacao_id_foreign');
            $table->string('exameable_type', 191);
            $table->unsignedBigInteger('exameable_id');
            $table->enum('local_realizacao_id', ['1', '2', '3']);
            $table->date('data_do_pedido')->nullable();
            $table->date('data_da_realizacao');
            $table->longText('comentarios')->nullable();
            $table->boolean('bloquear_tabela')->default(false);
            $table->unsignedBigInteger('created_by_id')->nullable()->index('exame_complementares_created_by_id_foreign');
            $table->unsignedBigInteger('updated_by_id')->nullable()->index('exame_complementares_updated_by_id_foreign');
            $table->unsignedBigInteger('deleted_by_id')->nullable()->index('exame_complementares_deleted_by_id_foreign');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['exameable_type', 'exameable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exame_complementares');
    }
};
