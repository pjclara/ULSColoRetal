<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Internamento extends Model
{

    protected $fillable = [
        'utente_id',
        'data_de_entrada',
        'data_de_saida',
        'data_de_alta',
        'motivo_internamento',
        'cama',
        'localizacao_id',
        'origem_do_internamento_id',
        'estado_da_alta_id',
        'responsavel_id',
        'comentarios',
        'clavien_dindo_id',
        'destino_id',
        'caso_social_id',
    ];

    /** @use HasFactory<\Database\Factories\InternamentoFactory> */
    use HasFactory, SoftDeletes;

    public function utente()
    {
        return $this->belongsTo(Utente::class);
    }

    public function responsavel()
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    public function estadoDaAlta()
    {
        return $this->belongsTo(EstadoDaAlta::class, 'estado_da_alta_id');
    }

    /// ddiagnsoticos

    public function diagnosticos()
    {
        return $this->belongsToMany(Diagnostico::class);
    }
}
