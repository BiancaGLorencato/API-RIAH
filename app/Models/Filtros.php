<?php

namespace App\Models;

use stdClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Filtros extends Model
{
    public function filtrosCadastro($request)
    {
        
        $data = new stdClass;
        $data->error = false;
        $countCPF = User::where('cpf', $request->cpf)->count();
        
        if($countCPF >= 1)
        {
            $data->error = true;
            $data->mensagem = "O CPF já foi cadastrado.";
            return $data;
        }
        
        $countEmail = User::where('email', $request->email)->count();
        if($countEmail >= 1){
            $data->error = true;
            $data->mensagem = "O Email já existe";
            return $data;
        }
       
        if($request->termo_uso != 1){
            $data->error = true;
            $data->mensagem = "Você deve aceitar os termos de uso";
            return $data;
        }

        return $data;
    }

    public function filtrosValidarHospital($hospitalPropriedade)
    {
        $data = new stdClass;
        $data->error = false;

        if($hospitalPropriedade == null){
            $data->error = true;
            $data->mensagem = "Hospital não encontrado";
            return $data;
        }
        if($hospitalPropriedade->status != 1){
            $data->error = true;
            $data->mensagem = "Hospital não está ativo";
            return $data;
        }

        return $data;

    }
}
