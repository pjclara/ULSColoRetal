<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDeDiagnostico extends Model
{
    protected $table = 'tipo_de_diagnosticos';

    protected $fillable = [
        'nome',
    ];

    public function diagnosticos()
    {
        return $this->hasMany(Diagnostico::class);
    }
}
