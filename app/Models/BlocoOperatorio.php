<?php

namespace App\Models;

use App\Observers\BlocoOperatorioObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(BlocoOperatorioObserver::class)]
class BlocoOperatorio extends Model
{
    /** @use HasFactory<\Database\Factories\BlocoOperatorioFactory> */
    use HasFactory, SoftDeletes;

    public function internamento()
    {
        return $this->belongsTo(Internamento::class);
    }

    protected $fillable = [
        'internamento_id',
        'data_de_inicio',
        'tipo_de_cirurgia_id',
        'resseccao_de_orgao',
        're_intervencao_nao_programada_id',
        'tipo_de_abordagem_id',
        'causa_de_conversao',
        'comentarios',
    ];

    protected $casts = [
        'data_de_inicio' => 'date:Y-m-d',
    ];

    public function tipoDeCirurgia()
    {
        return $this->belongsTo(TipoDeCirurgia::class);
    }

    public function tipoDeAbordagem()
    {
        return $this->belongsTo(TipoDeAbordagem::class);
    }

    public function reIntervencaoNaoProgramada()
    {
        return $this->belongsTo(ReIntervencaoNaoProgramada::class);
    }

    // intervenções cirúrgicas realizadas neste bloco operatório.
    // ->using() + withPivot('id') expõem o id da própria linha pivot, porque
    // intervencao_descricaos.intervencao_id referencia essa linha (a ocorrência
    // concreta desta intervenção neste bloco), não o catálogo `intervencaos`.
    public function intervencoesCirurgicas()
    {
        return $this->belongsToMany(Intervencao::class, 'bloco_operatorio_intervencao')
            ->using(BlocoOperatorioIntervencao::class)
            ->withPivot('id');
    }

    public function blocoOperatorioCRS()
    {
        return $this->hasOne(BlocoOperatorioCRS::class);
    }

    // acesso direto às linhas da tabela pivot (com id próprio), para poder
    // eager-load a descrição de anastomose de cada ocorrência de intervenção.
    public function blocoOperatorioIntervencoes()
    {
        return $this->hasMany(BlocoOperatorioIntervencao::class);
    }
}
