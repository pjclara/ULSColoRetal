<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    protected $fillable = [
        'lista_de_espera_id',
        'start',
        'end',
        'responsavel_id',
        'tipo_de_agendamento_id',
        'local_de_agendamento_id',
        'sala_de_agendamento_id',
        'periodo_de_agendamento_id',
        'estado_de_agendamento',
        'estado_de_agendamento_id',
        'comentarios',
        'created_by_id',
        'updated_by_id',
        'deleted_by_id',
    ];

    /** @use HasFactory<\Database\Factories\AgendamentoFactory> */
    use HasFactory;

    public function responsavel()
    {
        return $this->belongsTo(User::class);
    }

    public function listaDeEspera()
    {
        return $this->belongsTo(ListaDeEspera::class);
    }
}
