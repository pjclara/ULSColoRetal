<?php

namespace App\Services;

use App\Models\Utente;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UtenteService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Utente::query()
            ->latest()
            ->paginate($perPage);
    }

    public function search(array $filters)
    {
        return Utente::query()
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
            ->withQueryString();
    }
}
