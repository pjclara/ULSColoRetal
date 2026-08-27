<?php

namespace App\ViewModels;

use App\Models\Utente;

class UtenteViewModel
{
    public function __construct(
        protected Utente $model
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->model->id,
            'nome_curto' => $this->model->nome_curto,
            'numero_processo' => $this->model->numero_processo,
            'data_de_nascimento' => $this->model->data_de_nascimento,
            'sexo' => $this->model->sexo,
            'idade' => $this->model->idade,
            'centro_de_referencia' => new CentroDeReferenciaViewModel($this->model->centroDeReferencia),
        ];
    }
}