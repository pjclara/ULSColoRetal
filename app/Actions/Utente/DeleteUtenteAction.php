<?php

namespace App\Actions\{Utente}s;

use App\Models\Utente;

class DeleteUtenteAction
{
    public function handle(Utente $model): void
    {
        $model->delete();
    }
}