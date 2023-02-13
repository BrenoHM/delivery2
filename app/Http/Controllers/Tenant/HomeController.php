<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index()
    {
        return 'Página inicial do tenant' . config('tenant.id');
        //dd(config('tenant.id'));
    }
}
