<?php

namespace App\ViewModels;

use App\Models\Utente;
use JsonSerializable;

class UtenteViewModel implements JsonSerializable
{
    public function __construct(
        private readonly Utente $utente,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->utente->id,
            'nome' => $this->utente->nome,
            'numero_processo' => $this->utente->numero_processo,
            'data_nascimento' => $this->utente->data_nascimento?->format('Y-m-d'),
            'idade' => $this->utente->idade,
            'centro_de_referencia' => $this->utente->centroDeReferencia ? [
                'id' => $this->utente->centroDeReferencia->id,
                'utente_id' => $this->utente->centroDeReferencia->utente_id,
                'data_de_diagnostico' => $this->utente->centroDeReferencia->data_de_diagnostico,
                'data_de_referenciacao' => $this->utente->centroDeReferencia->data_de_referenciacao,
                'origem_id' => $this->utente->centroDeReferencia->origem_id,
                'data_de_entrada' => $this->utente->centroDeReferencia->data_de_entrada,
                'data_de_saida' => $this->utente->centroDeReferencia->data_de_saida,
                'destino_id' => $this->utente->centroDeReferencia->destino_id,
                'responsavel_id' => $this->utente->centroDeReferencia->responsavel_id,
                'comentarios' => $this->utente->centroDeReferencia->comentarios,
                'origem' => $this->utente->centroDeReferencia->origem?->only(['id', 'nome']),
            ] : null,
            'lista_de_esperas' => $this->utente->listaDeEsperas ? $this->utente->listaDeEsperas->map(fn($item) => new ListaDeEsperaViewModel($item)) : [],
        ];
    }
}
