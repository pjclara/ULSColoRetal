<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDeReconstrucao extends Model
{
    protected $fillable = [
        'nome',
    ];

    public $timestamps = false;
}
