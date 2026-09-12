<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoDeAgendamento extends Model
{
    /** @use HasFactory<\Database\Factories\EstadoDeAgendamentoFactory> */
    use HasFactory;


    protected $fillable = [
        'nome',
    ];

    protected $casts = [
        'nome' => 'string',
    ];

    /**
     * Nomes de estado válidos para um agendamento (case-insensitive). A lista de espera tem os
     * seus próprios 4 estados geridos à parte — ver ListaDeEsperaController::ESTADO_LISTA_ESPERA_*.
     */
    public const NOMES_PARA_AGENDAMENTO = ['agendado', 'operado', 'cancelado'];

    /**
     * Opções (value/label) para popular o select de "Estado de agendamento", restritas e
     * ordenadas pelos nomes válidos acima (esta tabela é gerida livremente pelo utilizador).
     */
    public static function optionsParaAgendamento(): \Illuminate\Support\Collection
    {
        return static::all()
            ->filter(fn (self $estado) => in_array(strtolower(trim($estado->nome)), self::NOMES_PARA_AGENDAMENTO, true))
            ->sortBy(fn (self $estado) => array_search(strtolower(trim($estado->nome)), self::NOMES_PARA_AGENDAMENTO))
            ->values()
            ->map(fn (self $estado) => ['value' => $estado->id, 'label' => $estado->nome]);
    }
}
