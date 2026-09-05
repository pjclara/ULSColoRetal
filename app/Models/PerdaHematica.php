<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerdaHematica extends Model
{
    protected $table = 'perdas_hematicas';

    protected $fillable = [
        'nome',
    ];

    public $timestamps = false;
}
