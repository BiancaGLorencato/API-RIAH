<?php

namespace App\Http\Controllers;

use App\Models\Exames;
use App\Models\Hospital;
use Illuminate\Http\Request;

class ExameController extends Controller
{
    public function examesHospital($tokenHospital)
    {
        $hospital = Hospital::where('token', $tokenHospital)->get()->first();

        $exames = Exames::where('id_hospital', $hospital->id_hospital)->get();

        return response()->json($exames, 200);
    }



}
