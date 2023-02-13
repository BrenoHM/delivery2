<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index(Request $request)
    {
        //session_start();
        //dd($_SESSION['tenant']);
        dd($request->session()->get('tenant')); 
        return 'Página inicial do tenant' . config('tenant.id');
        //dd(config('tenant.id'));
    }
}
