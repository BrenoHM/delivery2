<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {

        //dd(resource_path());
        $plans = Plan::with('items')->get();

        return view('site.pages.home', [
            'plans' => $plans
        ]);
    }
}
