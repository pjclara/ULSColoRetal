<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{

    /** @use HasFactory<\Database\Factories\AgendamentoFactory> */
    use HasFactory;

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
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];



    public function responsavel()
    {
        return $this->belongsTo(User::class);
    }

    public function listaDeEspera()
    {
        return $this->belongsTo(ListaDeEspera::class);
    }

    public function tipoDeAgendamento()
    {
        return $this->belongsTo(TipoDeAgendamento::class);
    }

    public function localDeAgendamento()
    {
        return $this->belongsTo(LocalDeAgendamento::class);
    }

    public function salaDeAgendamento()
    {
        return $this->belongsTo(SalaDeAgendamento::class);
    }

    public function periodoDeAgendamento()
    {
        return $this->belongsTo(PeriodoDeAgendamento::class);
    }

    public function estadoDeAgendamento()
    {
        return $this->belongsTo(EstadoDeAgendamento::class);
    }
}
