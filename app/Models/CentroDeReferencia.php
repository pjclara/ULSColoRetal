<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroDeReferencia extends Model
{
    /** @use HasFactory<\Database\Factories\CentroDeReferenciaFactory> */
    use HasFactory;

    protected $table = 'referenciacao_centro_de_referencias';

    protected $fillable = [
        'utente_id',
        'data_de_diagnostico',
        'data_de_referenciacao',
        'origem_id',
        'data_de_entrada',
        'data_de_saida',
        'destino_id',
        'responsavel_id',
        'comentarios',
    ];

    public function utente()
    {
        return $this->belongsTo(Utente::class);
    }

    public function origem()
    {
        return $this->belongsTo(OrigemDaReferenciacao::class, 'origem_id');
    }

    public function destino()
    {
        return $this->belongsTo(Destino::class, 'destino_id');
    }

    public function responsavel()
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }
}
