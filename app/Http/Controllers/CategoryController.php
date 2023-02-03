<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if( $request->json ) {
            return Category::where('user_id', Auth::id())
                    ->paginate($request->per_page);
        }

        if( $request->dropdown ) {
            return Category::where('user_id', Auth::id())->get();
        }

        return Inertia::render('Client/Products/Index', []);
    }
}
