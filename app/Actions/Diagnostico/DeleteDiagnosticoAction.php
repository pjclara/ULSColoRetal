<?php

namespace App\Actions\{Diagnostico}s;

use App\Models\Diagnostico;

class DeleteDiagnosticoAction
{
    public function handle(Diagnostico $model): void
    {
        $model->delete();
    }
}