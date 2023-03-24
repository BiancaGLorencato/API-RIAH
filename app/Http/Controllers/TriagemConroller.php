<?php

namespace App\Http\Controllers;

use stdClass;
use App\Models\Hospital;
use App\Models\FilaEspera;
use App\Models\Prontuario;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\FuncionarioHospital;
use App\Models\EspecialidadePaciente;
use Illuminate\Contracts\Auth\Authenticatable;

class TriagemConroller extends Controller
{
    public function salvarInfoPaciente(Request $request, Authenticatable $user) 
    {
        $data = new stdClass; 
        
        $hospital = Hospital::where('token', $request->token_hospital)->get()->first();
        $enfermeiro = FuncionarioHospital::where('id_usuario', $user->id_usuario)
                        ->where('id_hospital', $hospital->id_hospital)->get()->first();
        $id_enf = $enfermeiro->id_funcionario;

        $prontuario = Prontuario::where('id_usuario',  $request->id_paciente)->get()->first();
        Prontuario::where('id_usuario',  $request->id_paciente)
            ->update([ 'id_enf_triagem'=>$id_enf,
                     'nivel_gravidade'=>$request->nivel_gravidade,
                     'pressao'=> $request->pressao, 
                     'temperatura'=>$request->temperatura,
                     'taixa_diabetica'=> $request->taixa_diabetica,
                     'sintomas' => $request->sintomas,
                     'alergia'=> $request->alergia,
                     'obs_triagem'=> $request->obs
                    ]);
        
        FilaEspera::where('id_paciente', $request->id_paciente)->where('id_sala', $request->id_sala)
                    ->update(['finalizado'=> '1']);
        
        $now = Carbon::now();
        $fila = new FilaEspera;
        $fila->id_sala = $request->id_sala_prox;
        $fila->id_paciente = $request->id_paciente;
        $fila->nivel_gravidade = $request->nivel_gravidade;
        $fila->data_inicio = $now; 
        $fila->log = "Fila recebeu usuario as $now, passou pela triagem";
        $fila->save();

        $data->mensagem="Informações salvas, paciente encaminhado";
        
        return response()->json($data, 200);
    }
}
