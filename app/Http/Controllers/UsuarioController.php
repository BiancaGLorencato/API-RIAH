<?php

namespace App\Http\Controllers;

use stdClass;
use App\Models\User;
use App\Models\Filtros;
use App\Models\Hospital;
use App\Models\Prontuario;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\UsuarioDeletado;
use App\Models\PacienteHospital;
use App\Models\FuncionarioHospital;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Authenticatable;

class UsuarioController extends Controller
{
    public function createUser($hospitalToken, Request $request)
    {
      
        $data = new stdClass;

        $filtros = New Filtros; 
        $retorno = $filtros->filtrosCadastro($request);

        if($retorno->error == true){
            return response()->json($retorno, 401);
        }
       

        $hospital = Hospital::where('token', $hospitalToken)->get()->first();
       
        $retornoHospital = $filtros->filtrosValidarHospital($hospital);
        if($retornoHospital->error == true){
            return response()->json($retornoHospital, 401);
        }

        $usuario = User::create([
         'cpf' => $request->cpf,
         'rg' => $request->rg,
         'nome_completo' => $request->nome_completo,
         'cep' => $request->cep,
         'endereco' => $request->endereco,
         'data_nascimento' => $request->data_nascimento,
         'email' => $request->email,
         'password' => Hash::make($request->password),
         'termo_uso' => $request->termo_uso,
         'perfil' => $request->perfil,
         'remember_token' => Str::random(32)
        ]);

        $user = User::where('cpf', $request->cpf)->where('email', $request->email)->get()->first();

        

        $idUsuario = $user->id_usuario;

        $dataInicio = Carbon::now();

        if($user->perfil == "paciente"){
            
            $hospitalPaciente = new Prontuario;
            $hospitalPaciente->id_hospital = $hospital->id_hospital;
            $hospitalPaciente->id_usuario = $idUsuario;
            $hospitalPaciente->data_inicio = $dataInicio;
            $hospitalPaciente->nivel_gravidade = 0;
            $hospitalPaciente->log = "Criado no hospital $hospital->nome";
            $hospitalPaciente->save();
        }else{
            $funcionario = new FuncionarioHospital;
            $funcionario->id_usuario = $idUsuario;
            $funcionario->id_hospital = $hospital->id_hospital;
            $funcionario->matricula = $request->matricula;
            $funcionario->cargo = $request->cargo;
            $funcionario->logado = 0;
            $funcionario->primeiro_login = 0;
            $funcionario->emailultimo_logado = $dataInicio;
            $funcionario->save();

            //FALTA ENVIAR EMAIL
        }

        $credentials = ['email'=>$request->email, 'password'=>$request->password];
        Auth::attempt($credentials);

        $user = Auth::user();
        $user->tokens()->delete();
        $token = $user->createToken('token');

        $data->token = $token->plainTextToken;
        $data->perfil = $user->perfil;
        $data->mensagem = "Cadastrado com sucesso";
        return response()->json($data, 200);

        
    }

    public function getUser(Authenticatable $user)
    {
        $data = new stdClass; 
        $data->nome = $user->nome_completo;
        return response()->json($data, 200);
    }

    public function deleteUser(Request $request)
    {
        $data = new stdClass;

        $dataInicio = Carbon::now();

        $hospitalToken = $request->token_hospital;
        $hospitalPropriedade = Hospital::where('token', $hospitalToken)->get()->first();
        
        $filtros = New Filtros; 
        $retornoHospital = $filtros->filtrosValidarHospital($hospitalPropriedade);
        if($retornoHospital->error == true){
            return response()->json($retornoHospital, 400);
        }

        $credentials = $request->only(['email', 'password']);
        if(Auth::attempt($credentials) === false){
            $data->mensagem = "Dados não encontrados";
            return response()->json($data, 400);
        }

        $user = Auth::user();
        if($user->data_nascimento != $request->data_nascimento){
            $data->mensagem = "Dados não encontrados";
            return response()->json($data, 400);
        }
        
       
        $deletar = new UsuarioDeletado; 
        $deletar->cpf = $user->cpf;
        $deletar->rg = $user->rg;
        $deletar->nome_completo = $user->nome_completo;
        $deletar->cep = $user->cep;
        $deletar->endereco = $user->endereco;
        $deletar->data_nascimento = $user->data_nascimento;
        $deletar->email = $user->email;
        $deletar->termo_uso = $user->termo_uso;
        $deletar->perfil = $user->perfil;
        $deletar->created_at = $user->created_at;
        $deletar->updated_at = $user->updated_at;
        $deletar->deletado = $dataInicio;
        $deletar->save();
        $user->delete();

        $data->mensagem = "Dados excluidos";
        return response()->json($data, 200);
    }
    
}
