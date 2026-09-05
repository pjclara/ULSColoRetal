<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocalExtracaoPeca extends Model
{
    protected $fillable = [
        'nome',
    ];

    public $timestamps = false;
}
