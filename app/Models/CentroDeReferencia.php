<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CentroDeReferencia extends Model
{
    /** @use HasFactory<\Database\Factories\CentroDeReferenciaFactory> */
    use HasFactory;

    protected $table = 'referenciacao_centro_de_referencias';

    /**
     * As colunas `uuid` e `created_by_id` da tabela são NOT NULL sem valor por defeito, mas
     * não fazem parte dos dados submetidos pelo formulário — têm de ser preenchidas aqui.
     */
    protected static function booted(): void
    {
        static::creating(function (self $centroDeReferencia) {
            $centroDeReferencia->uuid ??= (string) Str::uuid();
            $centroDeReferencia->created_by_id ??= auth()->id();
        });

        static::updating(function (self $centroDeReferencia) {
            $centroDeReferencia->updated_by_id = auth()->id();
        });
    }

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
