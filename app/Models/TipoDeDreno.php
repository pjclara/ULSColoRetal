<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDeDreno extends Model
{
    protected $fillable = [
        'nome',
    ];

    public $timestamps = false;
}
