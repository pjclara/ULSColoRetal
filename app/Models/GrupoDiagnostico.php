<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoDiagnostico extends Model
{
    protected $table = 'grupo_diagnosticos';
    protected $fillable = [
        'nome',
    ];

    public function diagnosticos()
    {
        return $this->hasMany(Diagnostico::class);
    }
}
