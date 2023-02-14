<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validations = [
            'name' => 'required|string|max:255',
            'email'=>'required|string|email|max:255|unique:users,email,NULL,id,deleted_at,NULL',
            'role' => 'required|string',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        $messages = [];

        if( $request->role == 'client' ) {
            $validations['domain'] = 'required|unique:tenants,domain,null,id,deleted_at,NULL';
            $messages['domain.unique'] = 'Este nome já esta sendo utilizado';
        }

        $request->validate($validations, $messages);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        if( $request->role == 'client' ) {
            //insert tenant
            $tenant = Tenant::create([
                'domain' => $request->domain,
            ]);
            $user->update(['tenant_id' => $tenant->id]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
