<?php

namespace App\Services;

use App\Models\Utente;
use App\ViewModels\UtenteViewModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UtenteService
{
    public function paginate(
        int $perPage = 15,
        ?string $search = null,
    ): LengthAwarePaginator {
        return Utente::query()
            ->when(
                $search,
                fn($query) => $query->where(function ($query) use ($search) {
                    $query
                        ->where('nome', 'like', "%{$search}%")
                        ->orWhere('numero_utente', 'like', "%{$search}%");
                })
            )
            ->latest()
            ->paginate($perPage)
            ->withQueryString()
            ->through(
                fn(Utente $utente) => new UtenteViewModel($utente)
            );
    }

    public function search(array $filters)
    {
        return Utente::query()
            ->with('centroDeReferencia.origem')
            ->latest()
            ->when(
                $filters['search'] ?? null,
                function ($query, $search) {
                    $search = trim($search);

                    $query->where(function ($query) use ($search) {
                        $query
                            ->where('nome', 'like', "%{$search}%")
                            ->orWhere(
                                'numero_processo',
                                'like',
                                "%{$search}%"
                            );
                    });
                }
            )
            ->orderBy('nome')
            ->paginate(15)
            ->withQueryString()->through(fn($utente) => new UtenteViewModel($utente));
    }

    public function checkUtenteExists(int $utenteId): UtenteViewModel
    {
        $utente = Utente::query()
            ->with([
                'centroDeReferencia.origem',
                'listaDeEsperas.responsavel',
                'listaDeEsperas.diagnosticos',
                'listaDeEsperas.agendamentos',
            ])
            ->find($utenteId);

        if (!$utente) {
            abort(404, 'Utente não encontrado.');
        }

        return new UtenteViewModel($utente);
    }
}
