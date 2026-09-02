<?php

namespace App\Actions\{ListaDeEspera}s;

use App\Models\ListaDeEspera;

class UpdateListaDeEsperaAction
{
    public function handle(ListaDeEspera $model, array $data): ListaDeEspera
    {
        $model->update($data);

        return $model->refresh();
    }
}