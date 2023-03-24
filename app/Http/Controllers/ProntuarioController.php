<?php

namespace App\Http\Controllers;

use stdClass;
use App\Models\FilaEspera;
use App\Models\Prontuario;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProntuarioController extends Controller
{
    public function getProntuario($idProntuario)
    {
        $data = new stdClass;
        
        $prontuario = Prontuario::where('id_prontuario', $idProntuario)->get()->first();
        $data->pressao = $prontuario->pressao;
        $data->temperatura = $prontuario->temperatura;
        $data->taixa_diabetica = $prontuario->taixa_diabetica;
        $data->sintomas = $prontuario->sintomas;
        $data->alergia = $prontuario->alergia;
        $data->obs_triagem = $prontuario->obs_triagem;

        return response()->json($data, 200);
    }

    public function createProntuario(Request $request)
    {
        # code...
    }


    public function alta(Request $request)
    {
        $data = new stdClass;
        $now = Carbon::now();

        Prontuario::where('id_prontuario', $request->id_prontuario)->update(
                                        ['alta'=> '1', 
                                        'remedios'=> $request->remedio,
                                        'recomendacoes'=> $request->recomendacoes,
                                        'data_atla'=>$now]);
        FilaEspera::where('id_paciente', $request->id_paciente)->where('id_sala', $request->id_sala)
                    ->update(['finalizado'=> '1', 'alta'=> '1']);

        $data->mensagem = "Paciente recebeu alta";
        return response()->json($data, 200);
    }

   

}
