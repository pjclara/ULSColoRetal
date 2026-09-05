<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDeResseccao extends Model
{
    protected $fillable = [
        'nome',
    ];

    public $timestamps = false;
}
