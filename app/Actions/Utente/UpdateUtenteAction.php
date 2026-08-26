<?php

namespace App\Actions\{Utente}s;

use App\Models\Utente;

class UpdateUtenteAction
{
    public function handle(Utente $model, array $data): Utente
    {
        $model->update($data);

        return $model->refresh();
    }
}