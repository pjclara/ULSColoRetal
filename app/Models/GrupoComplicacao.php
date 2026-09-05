<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoComplicacao extends Model
{
    protected $table = 'grupo_complicacaos';
    protected $fillable = [
        'nome',
    ];

    public function complicacaos()
    {
        return $this->hasMany(Complicacao::class);
    }
}
