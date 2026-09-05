<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervencao extends Model
{
    /** @use HasFactory<\Database\Factories\IntervencaoFactory> */
    use HasFactory;

    protected $table = 'intervencaos';

    protected $fillable = [
        'nome',
        'grupo_intervencao_id',
        'abrev',
        'centro_de_referencia',
        'cirurgia_de_ressecao',
    ];

    protected $casts = [
        'centro_de_referencia' => 'boolean',
    ];

    public function grupoIntervencao()
    {
        return $this->belongsTo(GrupoIntervencao::class);
    }

    public function blocoOperatorios()
    {
        return $this->belongsToMany(BlocoOperatorio::class, 'bloco_operatorio_intervencao');
    }

    public function listaDeEsperas()
    {
        return $this->belongsToMany(ListaDeEspera::class, 'intervencao_lista_de_espera');
    }
}
