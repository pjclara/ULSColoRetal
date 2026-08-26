<?php

namespace App\Actions\{Utente}s;

use App\Models\Utente;

class CreateUtenteAction
{
    public function handle(array $data): Utente
    {
        return Utente::create($data);
    }
}