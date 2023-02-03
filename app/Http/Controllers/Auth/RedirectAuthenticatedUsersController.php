<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RedirectAuthenticatedUsersController extends Controller
{
    public function home()
    {
        if (auth()->user()->role == 'admin') {
            return redirect()->route('adminDashboard');
        }
        elseif(auth()->user()->role == 'user'){
            return redirect()->route('userDashboard');
        }
        elseif(auth()->user()->role == 'client'){
            return redirect()->route('clientDashboard');
        }
        else{
            return auth()->logout();
        }
    }
}
