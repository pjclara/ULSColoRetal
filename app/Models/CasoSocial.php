<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CasoSocial extends Model
{
    /** @use HasFactory<\Database\Factories\CasoSocialFactory> */
    use HasFactory;

    protected $table = 'caso_socials';
}
