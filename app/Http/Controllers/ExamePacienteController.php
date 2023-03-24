<?php

namespace App\Http\Controllers;

use stdClass;
use App\Models\FilaEspera;
use Illuminate\Http\Request;
use App\Models\ExamePaciente;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;

class ExamePacienteController extends Controller
{
    public function enviaExame(Request $request,  Authenticatable $user){
        
        $data = new  stdClass;
        $especialista = new ExamePaciente;
        $especialista->id_exame = $request->id_exame;
        $especialista->id_prontuario = $request->id_prontuario;
        $especialista->status = 0;
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
        $fila->log = "Fila recebeu usuario as $now, para exame";
        $fila->save();


        $data->mensagem = "Salvo";

        return response()->json($data, 200 );
    }
}
