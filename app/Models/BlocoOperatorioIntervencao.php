<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Pivot com id próprio: cada linha representa uma intervenção concreta realizada
 * num bloco operatório específico (não o catálogo `intervencaos` em si).
 * `intervencao_descricaos.intervencao_id` referencia o id desta tabela.
 */
class BlocoOperatorioIntervencao extends Pivot
{
    protected $table = 'bloco_operatorio_intervencao';

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'bloco_operatorio_id',
        'intervencao_id',
    ];

    public function blocoOperatorio()
    {
        return $this->belongsTo(BlocoOperatorio::class);
    }

    public function intervencao()
    {
        return $this->belongsTo(Intervencao::class);
    }

    public function descricao()
    {
        return $this->hasOne(IntervencaoDescricao::class, 'intervencao_id');
    }
}
