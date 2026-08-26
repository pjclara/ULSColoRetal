<?php

namespace App\Actions\{Diagnostico}s;

use App\Models\Diagnostico;

class UpdateDiagnosticoAction
{
    public function handle(Diagnostico $model, array $data): Diagnostico
    {
        $model->update($data);

        return $model->refresh();
    }
}