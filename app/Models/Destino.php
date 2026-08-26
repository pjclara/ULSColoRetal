<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destino extends Model
{
    protected $fillable = [
        'nome',
    ];

    /** @use HasFactory<\Database\Factories\DestinoFactory> */
    use HasFactory;
}
