<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlocoOperatorioCRS extends Model
{
    /** @use HasFactory<\Database\Factories\BlocoOperatorioCRSFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'bloco_operatorio_c_r_s';

    protected $fillable = [
        'bloco_operatorio_id',
        'experiencia_cirurgiao_id',
        'asa',
        'mortalidade',
        'score_fisiologico',
        'score_gravidade_cirurgico',
        'preparacao_intestinal_id',
        'intencao_id',
        'paleativa_causa',
        'duracao',
        'estoma_de_protecao_id',
        'local_extracao_peca_id',
        'tipo_de_dreno_id',
        'resseccao_multi_orgao',
        'resseccao_multi_orgao_quais',
        'aderencia_id',
        'tipo_de_resseccao_id',
        'neoplasia_residual_id',
        'perdas_hematica_id',
        'transfusao_intra_operatoria',
        'unidades_globulos',
        'protector_de_parede',
        'complicacoes',
        'complicacoes_quais',
        'created_by_id',
        'updated_by_id',
    ];

    public function blocoOperatorio()
    {
        return $this->belongsTo(BlocoOperatorio::class);
    }

    public function intencao()
    {
        return $this->belongsTo(Intencao::class);
    }

    public function estomaDeProtecao()
    {
        return $this->belongsTo(EstomaDeProtecao::class);
    }

    public function localExtracaoPeca()
    {
        return $this->belongsTo(LocalExtracaoPeca::class);
    }

    public function tipoDeDreno()
    {
        return $this->belongsTo(TipoDeDreno::class);
    }

    public function aderencia()
    {
        return $this->belongsTo(Aderencia::class);
    }

    public function tipoDeResseccao()
    {
        return $this->belongsTo(TipoDeResseccao::class);
    }

    public function neoplasiaResidual()
    {
        return $this->belongsTo(NeoplasiaResidual::class);
    }

    public function perdasHematica()
    {
        return $this->belongsTo(PerdaHematica::class);
    }
}
