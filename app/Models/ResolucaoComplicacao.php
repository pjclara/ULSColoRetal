<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResolucaoComplicacao extends Model
{
    protected $table = 'resolucao_complicacaos';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'clavien_dindo_id',
    ];

    public function clavienDindo()
    {
        return $this->belongsTo(ClavienDindo::class);
    }
}
