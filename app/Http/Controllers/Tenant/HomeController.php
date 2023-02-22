<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tenant;
use Illuminate\Http\Request;

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

        //return $categories;

        return view('tenant.pages.home', [
            'tenant' => $tenant,
            'categories' => $categories
        ]);
    }
}
