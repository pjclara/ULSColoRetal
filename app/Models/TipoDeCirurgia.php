<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDeCirurgia extends Model
{
    protected $fillable = [
        'nome',
    ];

    public $timestamps = false;
}
