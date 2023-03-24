<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, HasFactory, Notifiable;

    protected $table = "usuarios";

    protected $primaryKey  = "id_usuario";
  
    protected $fillable = [
        'id_usuario',
        'cpf', 'rg', 'nome_completo', 'cep', 'endereco', 'data_nascimento', 'email',
        'email_verified_at', 'password', 'termo_uso','perfil', 'remember_token', 
        'created_at', 'updated_at'
    ];
}
