<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Internamento extends Model
{

    protected $fillable = [
        'utente_id',
        'data_internamento',
        'data_alta',
        'motivo_internamento',
        'observacoes',
    ];

    /** @use HasFactory<\Database\Factories\InternamentoFactory> */
    use HasFactory, SoftDeletes;

    public function utente()
    {
        return $this->belongsTo(Utente::class);
    }

    public function responsavel()
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }
}
