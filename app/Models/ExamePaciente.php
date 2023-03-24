<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamePaciente extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    
    protected $table = "exame_paciente";
}
