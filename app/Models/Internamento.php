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

    protected $casts = [
        'data_de_entrada' => 'date: d/m/Y',
        'data_de_saida' => 'date: d/m/Y',
        'data_de_alta' => 'date: d/m/Y',
    ];

    //append the internamento's dias_internamento to the model's array form
    protected $appends = ['dias_internamento'];

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

    public function complicacaos()
    {
        return $this->belongsToMany(Complicacao::class);
    }

    public function origemDoInternamento()
    {
        return $this->belongsTo(OrigemDoInternamento::class, 'origem_do_internamento_id');
    }

    public function destino()
    {
        return $this->belongsTo(Destino::class, 'destino_id');
    }

    public function clavienDindo()
    {
        return $this->belongsTo(ClavienDindo::class, 'clavien_dindo_id');
    }

    // dias_internamento

    public function getDiasInternamentoAttribute()
    {
        if ($this->data_de_entrada && $this->data_de_saida) {
            return round($this->data_de_entrada->diffInDays($this->data_de_saida));
        }
        elseif ($this->data_de_entrada) {
            return round($this->data_de_entrada->diffInDays(now()));
        }
        return null;
    }

    public function blocoOperatorios()
    {
        return $this->hasMany(BlocoOperatorio::class)->with('intervencoesCirurgicas');
    }

    public function localizacao()
    {
        return $this->belongsTo(Localizacao::class, 'localizacao_id');
    }
}
