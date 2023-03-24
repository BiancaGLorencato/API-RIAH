<?php

namespace App\Http\Controllers;

use stdClass;
use App\Models\Filtros;
use App\Models\Hospital;
use Illuminate\Http\Request;
use App\Models\Especialidade;
use Illuminate\Contracts\Auth\Authenticatable;

class EspecialidadeController extends Controller
{
    public function getEspecialidadeHospital(Request $request, Authenticatable $user)
    {
        $data = new stdClass;
        $hospital = Hospital::where('token', $request->token_hospital)->get()->first();
        
        $filtros = New Filtros; 
        $retornoHospital = $filtros->filtrosValidarHospital($hospital);
        if($retornoHospital->error == true){
            return response()->json($retornoHospital, 401);
        }

        $especialidades =  Especialidade::where('id_hospital', $hospital->id_hospital)->get();
        foreach ($especialidades as $especialidade) {
            
            $info[] = ["nome"=> $especialidade->nome, "id"=>$especialidade->id_especialidade, 
                        "sala"=> $especialidade->id_sala];
        }
      
        $data->info = $info;
        return response()->json($data, 200);
    }
}
