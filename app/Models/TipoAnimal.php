<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoAnimal extends Model
{
    use HasFactory;
    protected $table= 'tipo_animals';
    public $timestamps = false;
    protected $fillable = [
        'tipo_animal',
        'eliminado'
    ];
}
