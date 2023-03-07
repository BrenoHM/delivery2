<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\Timeline;
use Exception;
use Illuminate\Http\Request;
use Gerencianet\Exception\GerencianetException;
use Gerencianet\Gerencianet;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        
        $tenantId = config('tenant.id');

        $tenant = Tenant::with('user')->find($tenantId);

        $categories = Category::with('products')
                    ->withCount('products')
                    ->where('tenant_id', $tenantId)
                    ->orderBy('products_count', 'desc')
                    ->get();

        $isOpened = Timeline::isOpened($tenantId);

        //return $categories;

        return view('tenant.pages.home', [
            'tenant' => $tenant,
            'categories' => $categories,
            'isOpened' => $isOpened
        ]);
    }

    public function testGerencianet()
    {
        
        $options = [
            "client_id" => "Client_Id_18f18696e80d1d60ccfc51efec99e52b09d6dd69",
            "client_secret" => "Client_Secret_79f8600178f0b9f3044bb7e1f0e33510db893b4b",
            "certificate" => realpath(__DIR__ . "/homologacao-429561-delivery.p12"), // Absolute path to the certificate in .pem or .p12 format
            "sandbox" => true,
            "debug" => false,
            "timeout" => 30,
        ];

        $body = [
            "name" => "Plano Avançado",
            "interval" => 1,
            "repeats" => null,
        ];
        
        try {
            $api = new Gerencianet($options);
            $response = $api->createPlan($params = [], $body);
            //dd($response);
            if( $response['code'] == 200 ) {
                Plan::create($response['data']);
            }
        
            print_r("<pre>" . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</pre>");
        } catch (GerencianetException $e) {
            print_r($e->code);
            print_r($e->error);
            print_r($e->errorDescription);
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

}
