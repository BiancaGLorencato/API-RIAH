<?php

namespace App\Http\Controllers;

use stdClass;
use App\Models\User;
use Firebase\JWT\JWT;
use App\Models\Hospital;
use App\Models\Prontuario;
use Illuminate\Http\Request;
use App\Models\FuncionarioHospital;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $data = new stdClass;
        $hospitalToken = $request->token_hospital;

        $credentials = $request->only(['email', 'password']);
        if(Auth::attempt($credentials) === false){
            $data->mensagem = "Usuário ou senha não encontrado";
            return response()->json($data, 401);
        }

        $user = Auth::user();
        $user->tokens()->delete();
        $token = $user->createToken('token');

        $hospital = Hospital::where('token', $hospitalToken)->select('token', 'id_hospital')->get()->first();

        if($user->perfil != 'paciente'){
            $this->loginFuncionario($hospital, $user);
        }else{
            
            $this->loginPaciente($hospital, $user);
        }
        
        $data->token = $token->plainTextToken;
        $data->perfil = $user->perfil;
        
        return response()->json($data,200);
    }

    private function loginFuncionario($hospital, $user)
    {
        $funcionario = FuncionarioHospital::where('id_hospital', $hospital->id_hospital)
                        ->where('id_usuario', $user->id_usuario )->get()->first();
        if($funcionario->primeiro_login == 0){
            FuncionarioHospital::where('id_funcionario', $funcionario->id_funcionario)
                ->update(['logado' => 1, 'primeiro_login' => 1]);
        }else{
            $op = FuncionarioHospital::where('id_funcionario', $funcionario->id_funcionario)->get()->first();
            $op->logado = 1;
            $op->save();
        }
    }

    private function loginPaciente($user)
    {
        $count = Prontuario::where('id_usuario', $user->id_usuario)
                            ->where('alta', 0)->count();
        if($count < 0){
            $prontuario = new Prontuario;
            $prontuario->id_paciente = $user->id_usuario;
            $prontuario->alta = 0;
        }
    }
}
