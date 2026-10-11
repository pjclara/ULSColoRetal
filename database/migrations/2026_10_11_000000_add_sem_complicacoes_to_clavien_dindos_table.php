<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Classicamente o Clavien-Dindo só classifica a gravidade de complicações já existentes (Grau 1 a
 * 5), por isso não havia nenhuma opção para marcar explicitamente "não houve complicações" — o
 * dashboard (InternamentoService::getPendentes) só considera um doente classificado quando
 * clavien_dindo_id deixa de ser null/"A aguardar", por isso esta opção é necessária para poder
 * concluir a alta de quem não teve complicações.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('clavien_dindos')->insertOrIgnore([
            'id' => 9,
            'nome' => 'Sem complicações',
        ]);
    }

    public function down(): void
    {
        DB::table('clavien_dindos')->where('id', 9)->where('nome', 'Sem complicações')->delete();
    }
};
