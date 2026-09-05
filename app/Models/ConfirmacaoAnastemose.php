<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfirmacaoAnastemose extends Model
{
    protected $fillable = [
        'nome',
    ];

    public $timestamps = false;
}
