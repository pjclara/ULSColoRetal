<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocalizacaoAnastemose extends Model
{
    protected $fillable = [
        'nome',
    ];

    public $timestamps = false;
}
