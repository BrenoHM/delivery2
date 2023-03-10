<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Gerencianet\Exception\GerencianetException;
use Gerencianet\Gerencianet;

class NotificationController extends Controller
{
    public function receiveNotification(Request $request)
    {
        $options = config('gerencianet');

        $token = $request->notification;
 
        $params = [
            'token' => $token
        ];

        try {

            $api = new Gerencianet($options);
            $chargeNotification = $api->getNotification($params, []);

            // Conta o tamanho do array data (que armazena o resultado)
            $i = count($chargeNotification["data"]);
            // Pega o último Object chargeStatus
            $ultimoStatus = $chargeNotification["data"][$i-1];
            // Acessando o array Status
            $status = $ultimoStatus["status"];

            $type = $ultimoStatus['type'];
            
            // Obtendo a String do status atual
            $statusAtual = $status["current"];

            $result = [
                'type' => $type,
                'custom_id' => $ultimoStatus['custom_id'],
                'subscription_id' => $ultimoStatus['identifiers']['subscription_id'],
                'status' => $statusAtual,
            ];

            if( $type == 'subscription' ) {
                // atualiza tabela subscriptions
            }else if( $type == 'subscription_charge' ) {
                // Obtendo o ID da transação
                $charge_id = $ultimoStatus["identifiers"]["charge_id"];
                $result['charge_id'] = $charge_id;
                // atualizar tabela charges
            }

            return response()->json($result);
                
            // Com estas informações, você poderá consultar sua base de dados e atualizar o status da transação especifica, uma vez que você possui o "charge_id" e a String do STATUS
          
            echo "O id da transação é: ".$charge_id." seu novo status é: ".$statusAtual;
            

        } catch (GerencianetException $e) {
            print_r($e->code);
            print_r($e->error);
            print_r($e->errorDescription);
            header("HTTP/1.1 400");
        } catch (Exception $e) {
            print_r($e->getMessage());
            header("HTTP/1.1 401");
        }

    }
    
}
