<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class GetTennant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        $current_url = $request->url();
        $current_url = str_replace(['https://', 'http://'], '', $current_url);
        $current_url = explode('.', $current_url);
        $subdomain = array_shift($current_url);
        
        $tenant = User::where('slugTenant', $subdomain)->first();
        if ($tenant) {
            config(['tenant.id' => $tenant->id]);
            //dd(config('tenant.id'));
        }
        else {
            abort(404);
        }
        return $next($request);
    }
}
