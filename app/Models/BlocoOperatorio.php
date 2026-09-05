<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlocoOperatorio extends Model
{
    /** @use HasFactory<\Database\Factories\BlocoOperatorioFactory> */
    use HasFactory;

    public function internamento()
    {
        return $this->belongsTo(Internamento::class);
    }

    protected $fillable = [
        'internamento_id',
        // Adicione outros campos que você deseja permitir a atribuição em massa
    ];

    // intervenções cirúrgicas relacionadas ao bloco operatório podem ser adicionadas aqui como relacionamentos, se necessário.
    public function intervencoesCirurgicas()
    {
        return $this->belongsToMany(Intervencao::class);
    }
}
