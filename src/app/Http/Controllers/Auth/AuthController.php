<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login (Request $request)
    {
        $validateRequest = request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(auth()->attempt($validateRequest)) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->with('error', 'Invalid email or password');
    }

    public function logout ()
    {
        auth()->logout();
        return redirect()->route('admin.login');
    }

    public function viewLogin ()
    {
        return view('pages.auth.login');
    }
}
