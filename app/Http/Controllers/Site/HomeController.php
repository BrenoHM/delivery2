<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $plans = Plan::with('items')->get();

        $plan_trial = collect($plans)->whereNotNull('trial_days')->first();

        return view('site.pages.home', [
            'plans' => $plans,
            'plan_trial' => $plan_trial
        ]);
    }
}
