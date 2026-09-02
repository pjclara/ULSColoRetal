<?php

namespace App\Actions\{ListaDeEspera}s;

use App\Models\ListaDeEspera;

class DeleteListaDeEsperaAction
{
    public function handle(ListaDeEspera $model): void
    {
        $model->delete();
    }
}