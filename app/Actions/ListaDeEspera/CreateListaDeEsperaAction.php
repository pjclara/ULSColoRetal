<?php

namespace App\Actions\{ListaDeEspera}s;

use App\Models\ListaDeEspera;

class CreateListaDeEsperaAction
{
    public function handle(array $data): ListaDeEspera
    {
        return ListaDeEspera::create($data);
    }
}