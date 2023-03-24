<?php

namespace App\Http\Controllers;

use stdClass;
use App\Models\Sala;
use App\Models\User;
use App\Models\FilaEspera;
use App\Models\Prontuario;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;

class FilaEsperaController extends Controller
{
    public function pacienteAFrente($idSala, $idPaciente)
    {
        $fila = FilaEspera::where('id_sala', $idSala)
                            ->where('id_paciente', '!=', $idPaciente)
                            ->whereNull('finalizado')
                            ->orderBy('nivel_gravidade', 'DESC')->count();
        return $fila;
    }

    public function getPacientesNaFila($idSala)
    {
        $data = new stdClass; 

        $filaCount = FilaEspera::where('id_sala', $idSala)->whereNull('finalizado')
        ->count();
        if($filaCount <= 0){
            $data->mensagem = "Não existe fila no momento";
            $data->quantidade = 0;
            return response()->json($data, 200);
        }
        $fila = FilaEspera::where('id_sala', $idSala)->whereNull('finalizado')
                            ->orderBy('nivel_gravidade', 'DESC')->get();
        for($i = 0; $i < count($fila); $i++){
            $idUsuario = $fila[$i]->id_paciente;
            $prontuario = Prontuario::where('id_usuario', $idUsuario)->get()->first();
            $usuario = User::where('id_usuario', $idUsuario)->get()->first();
            switch($fila[$i]->nivel_gravidade){
                case 1:
                    $cor = "green";
                    break;
                case 2:
                    $cor = "yellow";
                    break;
                case 3:
                    $cor = "red";
                    break;
                default:
                    $cor = "black";

            }
            $pacientes[$i] = [
                            "id_paciente"=>$idUsuario, 
                            "nome"=> $usuario->nome_completo,
                            "id_prontuario" => $prontuario->id_prontuario,
                            "gravidade" => $fila[$i]->nivel_gravidade,
                            "cor_gravidade"=> $cor
            ];
            $data->info = $pacientes;
            $data->quantidade = $i+1;
        }
       
        return response()->json($data, 200);  
    }

    public function getIntrucoesFila(Authenticatable $user)
    {
        $data = new stdClass;  
        $fila = FilaEspera::where('id_paciente' ,  $user->id_usuario)->
                    whereNull('finalizado')->get()->first();
        if($fila === null){
            $alta = Prontuario::where('id_usuario', $user->id_usuario)
                            ->where('alta',1)->get()->count();
            $data->chamando = false;
            if($alta > 0){
                $data->titulo = "Finalizado";
                $data->mensagem = "$user->nome_completo, você recebeu alta. Obrigada por usar o sistema!";
            }else{
                $data->titulo = "Erro";
                $data->mensagem = "Seu usuario não se encontra em nenhuma fila, ou com alta. Tente novamente";
            }
          
            return response()->json($data, 200);

        }
        $salaInfo = Sala::where('id_sala', $fila->id_sala)->get()->first();
        if($fila->chamando == 1){
            $data->chamando = true;
            $data->titulo = $salaInfo->nome;
            $data->mensagem = "Seu nome esta sendo chamado na sala $salaInfo->numero. Siga para o andar $salaInfo->andar";
        }else{
            $data->chamando = false;
            $qFila = $this->pacienteAFrente($fila->id_sala, $user->id_usuario);
            $data->titulo = $salaInfo->nome;
            $data->mensagem = "Siga para o andar $salaInfo->andar , sua consulta será na sala $salaInfo->numero. Existem $qFila pacientes na sua frente. ";      
        }
        return response()->json($data, 200);

    }

    public function chamar($idPaciente, $idSala)
    {
        $data = new stdClass;  

        FilaEspera::where('id_paciente' ,  $idPaciente)->
                    where('id_sala', $idSala)->
                    whereNull('finalizado')->update(['chamando'=>1]);

        $data->mensagem="Chamando";
        return response()->json($data, 200);
    }
}
