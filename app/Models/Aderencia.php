<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aderencia extends Model
{
    protected $fillable = [
        'nome',
    ];

    public $timestamps = false;
}
