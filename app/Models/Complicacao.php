<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complicacao extends Model
{
    /** @use HasFactory<\Database\Factories\ComplicacaoFactory> */
    use HasFactory;

    protected $table = 'complicacaos';
    protected $fillable = [
        'nome',
    ];  

    public function internamentos()
    {
        return $this->belongsToMany(Internamento::class);
    }
}
