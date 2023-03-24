<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;

class FiltrosCadastro extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function filtros($request)
    {
        $countCPF = User::where('cpf', $request->cpf)->count();
        
        if($countCPF >= 1)
        {
            $data->mensagem = "O CPF já foi cadastrado.";
            return response()->json($data, 422);
        }
        
        $countEmail = User::where('email', $request->email)->count();
        if($countEmail >= 1){
            $data->mensagem = "O Email já existe";
            return response()->json($data, 422);
        }
       
        if($request->termo_uso != 1){
            $data->mensagem = "Você deve aceitar os termos de uso";
            return response()->json($data, 422);
        }
    }

 
}
