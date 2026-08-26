<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnostico extends Model
{
    protected $fillable = [
        'nome',
        'descricao'];

    /** @use HasFactory<\Database\Factories\DiagnosticoFactory> */
    use HasFactory;

    public function internamentos()
    {
        return $this->belongsToMany(Internamento::class);
    }
}
