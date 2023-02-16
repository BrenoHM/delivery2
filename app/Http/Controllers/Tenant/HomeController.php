<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tenant;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index(Request $request)
    {
        //session_start();
        //dd($_SESSION['tenant']);
        // dd($request->session()->get('tenant')); 
        // return 'Página inicial do tenant' . config('tenant.id');
        //dd(config('tenant.id'));
        $tenantId = config('tenant.id');

        $tenant = Tenant::with('user')->find($tenantId);

        $categories = Category::with('products')->where('tenant_id', $tenantId)->get();

        //return $categories;

        return view('tenant.pages.home', [
            'tenant' => $tenant,
            'categories' => $categories
        ]);
    }
}
