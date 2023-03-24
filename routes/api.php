<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExameController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TriagemConroller;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\UrgenciasController;
use App\Http\Controllers\FilaEsperaController;
use App\Http\Controllers\ProntuarioController;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Controllers\EspecialidadeController;
use App\Http\Controllers\ExamePacienteController;
use App\Http\Controllers\EspecialistaPacienteController;
use App\Http\Controllers\FuncionarioEspecialistaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//PARA PUXAR AS INFORMAÇÕES PRINCIPAIS DO HOSPITAL TELA INICIAL
Route::get('/home/{hospital}', [HospitalController::class, 'getHospital']);

//CADASTRO DE PACIENTE
Route::post('/cadastro/{token}', [UsuarioController::class, 'createUser']);

Route::post('/deleteUser', [UsuarioController::class, 'deleteUser']);





//LOGIN DE TODOS OS USUARIOS
Route::post('/login', [LoginController::class, 'login']);


Route::get('/fila/{idPaciente}/{idSala}', [FilaEsperaController::class, 'chamar']);


Route::middleware('auth:sanctum')->group(function () {

    //Admi
    Route::post('/createUrgencia', [UrgenciasController::class, 'createUrgencia']);
    Route::post('/getUrgencia', [UrgenciasController::class, 'getUrgencias']);
   
    //Paciente
    Route::post('/urgenciaEscolhida', [UrgenciasController::class, 'escolherUrgencia']);

    //Enfermeiro e medicos
    Route::post('/especialidadeFuncionario', [FuncionarioEspecialistaController::class, 'getEspecialidadeFuncionario']);
    
    Route::post('/especialidades', [EspecialidadeController::class, 'getEspecialidadeHospital']);

    Route::get('/urgencias', [UrgenciasController::class, 'getUrgencias']);

    Route::get('/usuario/info', [UsuarioController::class, 'getUser']);

    Route::post('/update/protuario/triagem', [TriagemConroller::class, 'salvarInfoPaciente']);
    
    Route::post('/update/prontuario/especialista', [EspecialistaPacienteController::class, 'getConsulta']);
    
    Route::get('/filaEspera/usuario', [FilaEsperaController::class, 'getIntrucoesFila']);

    Route::get('/fila/{idSala}', [FilaEsperaController::class, 'getPacientesNaFila']);
    
    Route::get('/exames/{tokenHospital}', [ExameController::class, 'examesHospital']);
    
    Route::post('/exame/encaminha', [ExamePacienteController::class, 'enviaExame']);
 

    Route::get('/prontuario/{idProntuario}', [ProntuarioController::class, 'getProntuario']);

    Route::post('/update/prontuario/especialista', [EspecialistaPacienteController::class, 'getConsulta']);
    
    Route::post('/update/prontuario/alta', [ProntuarioController::class, 'alta']);
    
});


