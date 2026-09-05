<?php

namespace App\Services;

use App\Models\Agendamento;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AgendamentoService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Agendamento::query()
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): Agendamento
    {
        return Agendamento::create($data);
    }

    public function update(Agendamento $agendamento, array $data): Agendamento
    {
        $agendamento->update($data);

        return $agendamento;
    }

    public function delete(Agendamento $agendamento): bool
    {
        return $agendamento->delete();
    }
}