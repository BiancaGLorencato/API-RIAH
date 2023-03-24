<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EspecialidadePaciente extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $table = "especialista_paciente";
}
