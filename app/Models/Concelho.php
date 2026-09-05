<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Concelho extends Model
{
    protected $fillable = [
        'nome',
    ];

    public $timestamps = false;

    public function utentes()
    {
        return $this->hasMany(Utente::class);
    }
}
