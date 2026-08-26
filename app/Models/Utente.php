<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Utente extends Model
{
    protected $fillable = [
        'nome',
        'slug',
        'numero_utente',
        'numero_processo',
        'sexo_id',
        'estado_civil_id',
        'etnia_id',
        'concelho_id',
        'data_nascimento',
        'classe_social_id',
        'created_by_id',
        'updated_by_id',
        'deleted_by_id',
    ];

    /** @use HasFactory<\Database\Factories\UtenteFactory> */
    use HasFactory;

    // append the utente's full name to the model's array form
    protected $appends = ['nome_curto'];

    // devolver 1º e ultimo nome do utente
    public function getNomeCurtoAttribute()
    {
        $nomes = explode(' ', $this->nome);
        return $nomes[0] . ' ' . end($nomes);
    }
}
