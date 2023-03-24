<?php

namespace App\Http\Controllers;

use stdClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FuncionarioHospital;
use Illuminate\Contracts\Auth\Authenticatable;

class FuncionarioEspecialistaController extends Controller
{
    public function getEspecialidadeFuncionario(Request $request, Authenticatable $user)
    {
        $data = new stdClass;
        

        $data->exame = 0;
        $funcionario = DB::table('usuario_funcionario')
        ->join('exame_funcionario', 'usuario_funcionario.id_funcionario', '=',
                 'exame_funcionario.id_funcionario')
        ->where('id_usuario', $user->id_usuario)->get()->first();
        if($funcionario != null){
            $data->exame = 1;
        }

        $funcionario = DB::table('usuario_funcionario')
        ->join('triagem_sala_enf', 'usuario_funcionario.id_funcionario', '=', 'triagem_sala_enf.id_enfermeiro')
        ->where('id_usuario', $user->id_usuario)->get()->first();
        if($funcionario != null){
            $data->triagem = 1;
            $data->especialidade = 0;
            $data->sala = $funcionario->id_sala;
            return response()->json($data, 200);
        }

        $funcionario = DB::table('usuario_funcionario')
        ->join('especialista_funcionario', 'usuario_funcionario.id_funcionario', '=',
                 'especialista_funcionario.id_funcionario')
        ->where('id_usuario', $user->id_usuario)->get()->first();
        if($funcionario != null){
            $data->triagem = 0;

            $salasEspecialidade = DB::table('especialidade')
                                    ->join('salas', 'especialidade.id_sala', '=',
                                            'salas.id_sala')
                                    ->where('id_especialidade', $funcionario->id_especialidade)->get()->first();
            $data->sala = $salasEspecialidade->id_sala;
            $data->nomeSala = $salasEspecialidade->nome;
            $data->especialidade = $funcionario->id_especialidade;
            $data->especialidadeOn = 1;
            return response()->json($data, 200);
        }


        

        $data->mensagem = "Nenhuma especialidade encontrada";
        return response()->json($data, 400);

    }
}
