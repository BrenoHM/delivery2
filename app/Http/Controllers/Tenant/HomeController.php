<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tenant;
use App\Models\Timeline;
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

        $isOpened = Timeline::isOpened($tenantId);

        //return $categories;

        return view('tenant.pages.home', [
            'tenant' => $tenant,
            'categories' => $categories,
            'isOpened' => $isOpened
        ]);
    }

}
