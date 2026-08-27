<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CentroDeReferenciaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'utente_id' => $this->utente_id,
            'origem_id' => $this->origem_id,
            'destino_id' => $this->destino_id,
            'responsavel_id' => $this->responsavel_id,
            'data_de_referenciacao' => $this->data_de_referenciacao,
            'data_de_diagnostico' => $this->data_de_diagnostico,
            'observacoes' => $this->observacoes,
        ];
    }
}
