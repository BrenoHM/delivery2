<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use Exception;
use Illuminate\Http\Request;
use Gerencianet\Exception\GerencianetException;
use Gerencianet\Gerencianet;

class GerencianetController extends Controller
{
    //
    public function testGerencianetSubscriber()
    {
        $plan = Plan::with('items')->find(1);
        //return $plan;
        
        $options = config('gerencianet');

        //dd($plan->plan_id);

        $params = ['id' => $plan->plan_id];

        $items = [
            [
                'name' => $plan->items[0]->name,
                'amount' => $plan->items[0]->amount,
                'value' => $plan->items[0]->value
            ]
        ];

        $paymentToken = '1a04df1d21aa726543112aafa4dc63c3af4ef999';

        $customer = [
            'name' => 'Breno Mol',
            'cpf' => '01603117644',
            'phone_number' => '31988975279',
            'email' => 'emaildobrenomol@gmail.com',
            'birth' => '1977-01-15'
        ];

        $billingAddress = [
            'street' => 'Av. JK',
            'number' => 909,
            'neighborhood' => 'Bauxita',
            'zipcode' => '35400000',
            'city' => 'Ouro Preto',
            'state' => 'MG',
        ];

        $body = [
            'items' => $items,
            'payment' => [
                'credit_card' => [
                    'billing_address' => $billingAddress,
                    'payment_token' => $paymentToken,
                    'customer' => $customer
                ]
            ]
        ];


        try {
            $api = new Gerencianet($options);
            $response = $api->createOneStepSubscription($params, $body);

            print_r("<pre>" . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</pre>");
        } catch (GerencianetException $e) {
            print_r($e->code);
            print_r($e->error);
            print_r($e->errorDescription);
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function createOneStepBillet()
    {
        $options = config('gerencianet');

        $plan = Plan::with('items')->find(1);

        $params = [
            "id" => $plan->plan_id // plan_id
        ];
        
        $items = [
            [
                'name' => $plan->items[0]->name,
                'amount' => $plan->items[0]->amount,
                'value' => $plan->items[0]->value
            ]
        ];
        
        $metadata = [
            "notification_url" => "https://your-domain.com.br/notification/"
        ];
        
        $customer = [
            "name" => "Gorbadoc Oldbuck",
            "cpf" => "94271564656"
        ];
        
        $body = [
            "items" => $items,
            "payment" => [
                "banking_billet" => [
                    "expire_at" => "2024-12-10",
                    "message" => "This is a space\n of up to 80 characters\n to tell\n your client something",
                    "customer" => $customer
                ]
            ]
        ];
        
        try {
            $api = new Gerencianet($options);
            $response = $api->createOneStepSubscription($params, $body);
        
            print_r("<pre>" . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</pre>");
        } catch (GerencianetException $e) {
            print_r($e->code);
            print_r($e->error);
            print_r($e->errorDescription);
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function getNotification($token)
    {
        $options = config('gerencianet');

        //$token = $_POST["notification"];
        $token = $token;
 
        $params = [
            'token' => $token
        ];

        try {
            $api = new Gerencianet($options);
            $chargeNotification = $api->getNotification($params, []);
            return $chargeNotification;
            // Para identificar o status atual da sua transa????o voc?? dever?? contar o n??mero de situa????es contidas no array, pois a ??ltima posi????o guarda sempre o ??ltimo status. Veja na um modelo de respostas na se????o "Exemplos de respostas" abaixo.
          
            // Veja abaixo como acessar o ID e a String referente ao ??ltimo status da transa????o.
                
            // Conta o tamanho do array data (que armazena o resultado)
            $i = count($chargeNotification["data"]);
            // Pega o ??ltimo Object chargeStatus
            $ultimoStatus = $chargeNotification["data"][$i-1];
            // Acessando o array Status
            $status = $ultimoStatus["status"];
            // Obtendo o ID da transa????o        
            $charge_id = $ultimoStatus["identifiers"]["charge_id"];
            // Obtendo a String do status atual
            $statusAtual = $status["current"];
                
            // Com estas informa????es, voc?? poder?? consultar sua base de dados e atualizar o status da transa????o especifica, uma vez que voc?? possui o "charge_id" e a String do STATUS
          
            echo "O id da transa????o ??: ".$charge_id." seu novo status ??: ".$statusAtual;
         
            header("HTTP/1.1 200");
            //print_r($chargeNotification);
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

    public function cancelPlan(Request $request)
    {
        $options = config('gerencianet');

        $response = [
            'success' => false,
            'message' => ""
        ];

        $params = [
            "id" => $request->subscription_id
        ];

        try {
            $api = new Gerencianet($options);
            $response = $api->cancelSubscription($params);

            if( $response["code"] == 200 ) {
                $response['success'] = true;
                $response['message'] = "Seu plano foi cancelado!";
                Subscription::where('subscription_id', $request->subscription_id)->update(['status' => 'canceled']);
            }

            return response()->json($response);
        
            //print_r("<pre>" . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</pre>");
        } catch (GerencianetException $e) {

            // print_r($e->code);
            // print_r($e->error);
            // print_r($e->errorDescription);
            
            $response['message'] = "Houve um erro ao cancelar seu plano, contacte o administrador do sistema!";
            return response()->json($response);

        } catch (Exception $e) {
            
            $response['message'] = "Erro no servidor, contacte o administrador do sistema!";
            return response()->json($response);

            //print_r($e->getMessage());
        }

        
    }
}
