<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoIntervencao extends Model
{
    protected $table = 'grupo_intervencaos';

    protected $fillable = [
        'nome',
    ];

    public function intervencoes()
    {
        return $this->hasMany(Intervencao::class);
    }
}
