<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB as FacadesDB;

class DashboardController extends Controller
{
    public function client()
    {
        $orders = Order::selectRaw('MONTH(created_at) month, SUM(total) as total')
                                ->whereRaw('YEAR (created_at) = YEAR (NOW())')
                                ->where('tenant_id', Auth::user()->tenant_id)
                                ->groupByRaw('MONTH(created_at)')
                                ->get();

        $collect = collect($orders);

        $rows = [];
        foreach($this->months() as $key => $value) {
            array_push($rows, [
                'month' => $value,
                'value' => $collect->where('month', $key)->first()->total ?? null
            ]);
        }

        return Inertia::render('Client/Dashboard', [
            'orders' => $rows
        ]);
    }

}
