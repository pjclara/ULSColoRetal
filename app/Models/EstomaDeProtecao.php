<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstomaDeProtecao extends Model
{
    protected $fillable = [
        'nome',
    ];

    public $timestamps = false;
}
