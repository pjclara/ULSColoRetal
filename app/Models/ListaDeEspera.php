<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListaDeEspera extends Model
{
    /** @use HasFactory<\Database\Factories\ListaDeEsperaFactory> */
    use HasFactory;

    protected $fillable = [
        'utente_id',
        'prioridade_id',
        'data_de_lista',
        'estado_lista_espera',
        'cancelar_lista_espera',
        'comentarios',
        'responsavel_id'
    ];


    // cast
    protected $casts = [
        'data_de_lista' => 'datetime',
    ];

    public function utente()
    {
        return $this->belongsTo(Utente::class);
    }

    // diagnostico
    public function diagnosticos()
    {
        return $this->belongsToMany(Diagnostico::class);
    }

    public function responsavel()
    {
        return $this->belongsTo(User::class);
    }

    // agendamento
    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }

}
