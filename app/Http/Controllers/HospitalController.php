<?php

namespace App\Http\Controllers;

use stdClass;
use App\Models\Filtros;
use App\Models\Hospital;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    public function getHospital($hospital)
    {
        $data = new stdClass;
        $hospitalPropriedade = Hospital::where('nome', $hospital)->get()->first();
        
        $filtros = New Filtros; 
        $retornoHospital = $filtros->filtrosValidarHospital($hospitalPropriedade);
        if($retornoHospital->error == true){
            return response()->json($retornoHospital, 401);
        }

        $data->nome = $hospital;
        $data->url = $hospitalPropriedade->url_logo;
        $data->token = $hospitalPropriedade->token;
        
        return response()->json($data, 200);
    }
}
