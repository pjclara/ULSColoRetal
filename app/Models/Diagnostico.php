<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnostico extends Model
{
    protected $fillable = [
        'nome',
        'grupo_diagnostico_id',
        'tipo_diagnostico_id',
        'abrev',
        'centro_referencia',];

    /** @use HasFactory<\Database\Factories\DiagnosticoFactory> */
    use HasFactory;

    public function internamentos()
    {
        return $this->belongsToMany(Internamento::class);
    }

    public function grupoDiagnostico()
    {
        return $this->belongsTo(GrupoDiagnostico::class);
    }

    public function listaDeEsperas()
    {
        return $this->belongsToMany(ListaDeEspera::class);
    }
}
