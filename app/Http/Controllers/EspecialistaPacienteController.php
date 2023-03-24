<?php

namespace App\Http\Controllers;

use stdClass;
use App\Models\FilaEspera;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\FuncionarioHospital;
use App\Models\EspecialidadePaciente;
use App\Models\EspecialidadeFuncionario;
use Illuminate\Contracts\Auth\Authenticatable;

class EspecialistaPacienteController extends Controller
{
    public function getConsulta(Request $request,  Authenticatable $user){
        
        $data = new  stdClass;
        $now = Carbon::now();
        
        $funcionario = FuncionarioHospital::where('id_usuario', $user->id_usuario)->get()->first();
       
        $especialistaId = EspecialidadeFuncionario::where('id_funcionario', $funcionario->id_funcionario)->get()->first();
        $especialista = new EspecialidadePaciente;
        $especialista->id_especialista = $especialistaId->id_especialista;
        $especialista->id_prontuario = $request->id_prontuario;
        $especialista->data_chamada_especialista = $request->data_chamada_especialista;
        $especialista->data_atendimento = $now;
        $especialista->obs = $request->obs;
        $especialista->status = '1';
        $especialista->save();

        $filaAntes = FilaEspera::where('id_paciente', $request->id_paciente)->where('id_sala', $request->id_sala)
                  ->get()->first();
        FilaEspera::where('id_paciente', $request->id_paciente)->where('id_sala', $request->id_sala)->update(['finalizado'=> '1']);
        $now = Carbon::now();
        $fila = new FilaEspera;
        $fila->id_sala = $request->id_sala_prox;
        $fila->id_paciente = $filaAntes->id_paciente;
        $fila->nivel_gravidade = $filaAntes->nivel_gravidade;
        $fila->data_inicio = $now; 
        $fila->log = "Fila recebeu usuario as $now, passou pelo $especialista->id_especialista";
        $fila->save();


        $data->mensagem = "Salvo";

        return response()->json($data, 200 );
    }
}
