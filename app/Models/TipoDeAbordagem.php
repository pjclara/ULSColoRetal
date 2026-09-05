<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDeAbordagem extends Model
{
    protected $fillable = [
        'nome',
    ];

    public $timestamps = false;
}
