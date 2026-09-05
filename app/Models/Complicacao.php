<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complicacao extends Model
{
    /** @use HasFactory<\Database\Factories\ComplicacaoFactory> */
    use HasFactory;

    protected $table = 'complicacaos';
    protected $fillable = [
        'nome',
        'abrev',
        'grupo_complicacao_id',
    ];

    public function internamentos()
    {
        return $this->belongsToMany(Internamento::class);
    }

    public function grupoComplicacao()
    {
        return $this->belongsTo(GrupoComplicacao::class);
    }
}
