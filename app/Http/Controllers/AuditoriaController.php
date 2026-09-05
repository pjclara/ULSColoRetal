<?php

namespace App\Http\Controllers;

use App\Services\AuditoriaService;
use Illuminate\Http\Request;

class AuditoriaController extends Controller
{
    public function __construct(
        private AuditoriaService $service
    ) {}

    public function index(Request $request)
    {
        $periodo = $request->string('periodo')->toString() ?: 'ano';

        return inertia('Auditoria/Index', [
            'linhas' => $this->service->relatorio($periodo),
            'periodo' => $periodo,
        ]);
    }
}
