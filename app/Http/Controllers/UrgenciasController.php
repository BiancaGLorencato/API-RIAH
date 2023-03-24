<?php

namespace App\Http\Controllers;

use stdClass;
use App\Models\Sala;
use App\Models\Hospital;
use App\Models\FilaEspera;
use App\Models\Prontuario;
use App\Models\TriagemSala;
use Illuminate\Http\Request;
use App\Models\TiposUrgencias;
use Illuminate\Support\Carbon;
use App\Models\SeguenciaUrgencia;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\FilaEsperaController;
use Illuminate\Contracts\Auth\Authenticatable;

class UrgenciasController extends Controller
{
    public function getUrgencias(Request $request, Authenticatable $user)
    {
        $data = new stdClass;
        $hospitalToken = $request->token_hospital;
        $hospital = Hospital::where('token', $hospitalToken)->select('id_hospital')->get()->first();

        $tipos = TiposUrgencias::where('id_hospital', $hospital->id_hospital)->get();
        $data->tipos = $tipos;
        return response()->json($data, 200);

    }

    public function escolherUrgencia(Authenticatable $user, Request $request)
    {
        $data = new stdClass;
        $hospitalToken = $request->token_hospital;
        $hospital = Hospital::where('token', $hospitalToken)->select('id_hospital')->get()->first();

        $idUrgencia = $request->id_urgencia;
        $proxPasso = SeguenciaUrgencia::where('id_urgencia', $idUrgencia)->get()->first();

        Prontuario::where('id_usuario', $user->id_usuario)
                    ->where('id_hospital', $hospital->id_hospital)
                    ->update(['id_urgencia'=> $idUrgencia]);

        if($proxPasso->triagem == 1){
            $passos =  TriagemSala::where('id_hospital', $hospital->id_hospital)->get()->first();

            $now = Carbon::now();
            $fila = new FilaEspera;
            $fila->id_sala = $passos->id_sala;
            $fila->id_paciente = $user->id_usuario;
            $fila->data_inicio = $now; 
            $fila->log = "Fila recebeu usuario as $now";
            $fila->save();

            $idSala = $passos->id_sala;
            $salaInfo = Sala::where('id_sala', $idSala)->get()->first();

            $filaEspera = new FilaEsperaController;
            $qFila = $filaEspera->pacienteAFrente($idSala, $user->id_usuario);
            

            $data->titulo = $salaInfo->nome;
            $data->mensagem = "Siga para o andar $salaInfo->andar , sua consulta será na sala $salaInfo->numero. Existem $qFila pacientes na sua frente. ";
            return response()->json($data, 200);
        }

    }

    public function createUrgencia(Request $request, Authenticatable $user)
    {
        $data = new stdClass;
        if($user->perfil != "admin"){
            $data->mensagem = "Usuário sem permissao";
            return response()->json($data, 401);
        }
        $hospitalToken = $request->token_hospital;
        $hospital = Hospital::where('token', $hospitalToken)->select('id_hospital')->get()->first();

        $nomeUrgencia = $request->urgencia;
        $count = TiposUrgencias::where('id_hospital', $hospital->id_hospital)->where('nome_urgencia', $nomeUrgencia)->count();
        if($count > 0){
            $data->mensagem = "Especialidade já cadastrada o nome deve ser único";
            return response()->json($data, 401);
        }

        $urgencia = new TiposUrgencias;
        $urgencia->id_hospital = $hospital->id_hospital;
        $urgencia->nome_urgencia = $nomeUrgencia;
        $urgencia->status = $request->status;
        $urgencia->save();

        $urgencias = TiposUrgencias::where('id_hospital', $hospital->id_hospital)->where('nome_urgencia', $nomeUrgencia)->get()->first();
        $idUrgencia = $urgencia->id;
        
        $seguinte = new SeguenciaUrgencia; 
        $seguinte->id_urgencia = $idUrgencia;
        $seguinte->id_especialidade = $request->especialidade;
        $seguinte->save();


    }

    public function updateUrgencia()
    {

    }

    public function deleteUrgencia()
    {

    }
}
