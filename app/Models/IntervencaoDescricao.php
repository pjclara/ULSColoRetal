<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IntervencaoDescricao extends Model
{
    use SoftDeletes;

    protected $table = 'intervencao_descricaos';

    protected $fillable = [
        'intervencao_id', // FK para bloco_operatorio_intervencao.id (a ocorrência concreta, não o catálogo)
        'anastemose_modo_id',
        'anastemose_via_id',
        'anastemose_sentido_id',
        'tipo_de_reconstrucao_id',
        'reforco_anastemose',
        'localizacao_anastemose_id',
        'confirmacao_anastemose_id',
        'libertacao_angulo',
        'numero_cargas',
        'distancia_linha_pectinea_anastemose',
        'distancia_linha_pectinea_tumor',
        'qualidade_peca_operatoria_id',
        'comentarios',
        'created_by_id',
        'updated_by_id',
    ];

    public function blocoOperatorioIntervencao()
    {
        return $this->belongsTo(BlocoOperatorioIntervencao::class, 'intervencao_id');
    }

    public function anastemoseModo()
    {
        return $this->belongsTo(AnastemoseModo::class);
    }

    public function anastemoseVia()
    {
        return $this->belongsTo(AnastemoseVia::class);
    }

    public function anastemoseSentido()
    {
        return $this->belongsTo(AnastemoseSentido::class);
    }

    public function tipoDeReconstrucao()
    {
        return $this->belongsTo(TipoDeReconstrucao::class);
    }

    public function localizacaoAnastemose()
    {
        return $this->belongsTo(LocalizacaoAnastemose::class);
    }

    public function confirmacaoAnastemose()
    {
        return $this->belongsTo(ConfirmacaoAnastemose::class);
    }

    public function qualidadePecaOperatoria()
    {
        return $this->belongsTo(QualidadePecaOperatoria::class);
    }
}
