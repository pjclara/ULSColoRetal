<?php

namespace App\Http\Controllers;

use App\Models\ListaDeEspera;
use App\Models\Utente;
use App\Services\ListaDeEsperaService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrigemDaReferenciacao;
use App\Models\Destino;

class ListaDeEsperaController extends Controller
{
    // service
    public function __construct(private ListaDeEsperaService $service) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listaDeEsperas = $this->service->paginate();

        return inertia('ListaDeEsperas/Index', [

            'listaDeEsperas' => $listaDeEsperas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $search = request()->get('search', '');

        if ($search) {
            $utentes = Utente::with('centroDeReferencia')->where(function ($query) use ($search) {
                $query->where('nome', 'like', "%{$search}%")
                    ->orWhere('numero_processo', 'like', "%{$search}%");
            })->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $utentes = Utente::with('centroDeReferencia')->orderBy('created_at', 'desc')->paginate(10);
        }

        // order by created at already applied in both branches, no need to reapply

        return inertia('ListaDeEsperas/Create', [
            'utentes' => $utentes,
            'filters' => [
                'search' => $search,
                'page' => request()->get('page', 1),
            ],
            'origems' => OrigemDaReferenciacao::pluck('nome', 'id'),
            'destinos' => Destino::pluck('nome', 'id'),
            'users' => User::pluck('name', 'id'),

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ListaDeEspera $listaDeEspera)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ListaDeEspera $listaDeEspera)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListaDeEspera $listaDeEspera)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListaDeEspera $listaDeEspera)
    {
        //
    }
}
