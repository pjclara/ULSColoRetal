<?php

namespace App\Services;

use App\Models\IntervencaoDescricao;

class IntervencaoDescricaoService
{
    public function create(array $data): IntervencaoDescricao
    {
        return IntervencaoDescricao::create($data);
    }

    public function update(IntervencaoDescricao $intervencaoDescricao, array $data): IntervencaoDescricao
    {
        $intervencaoDescricao->update($data);

        return $intervencaoDescricao;
    }

    public function delete(IntervencaoDescricao $intervencaoDescricao): bool
    {
        return $intervencaoDescricao->delete();
    }
}
