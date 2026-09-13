<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Linha da pivot complicacao_internamento: além de ligar a complicação ao internamento,
 * guarda em `resolucao` os ids (resolucao_complicacaos) que descrevem como aquela
 * complicação, nesse internamento em concreto, foi resolvida.
 */
class ComplicacaoInternamento extends Pivot
{
    protected $table = 'complicacao_internamento';

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'resolucao' => 'array',
    ];
}
