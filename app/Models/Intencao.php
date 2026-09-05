<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intencao extends Model
{
    protected $fillable = [
        'nome',
    ];

    public $timestamps = false;
}
