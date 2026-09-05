<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReIntervencaoNaoProgramada extends Model
{
    protected $fillable = [
        'nome',
    ];

    public $timestamps = false;
}
