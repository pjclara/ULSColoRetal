<?php

namespace App\Http\Controllers;

use App\Services\EstatisticaService;

class EstatisticaController extends Controller
{
    public function __construct(private EstatisticaService $service) {}

    public function index()
    {
        return inertia('Estatisticas/Index', $this->service->build());
    }
}
