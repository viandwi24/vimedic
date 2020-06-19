<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function home()
    {
        return redirect()->route('admin.home');
    }

    public function login()
    {
        return view('pages.auth.login');
    }

    public function login_post(Request $request)
    {
        $request->validate([
            'username' => 'required|min:3',
            'password' => 'required|min:3',
        ]);

        // atempt login
        $remember = ($request->has('remember')) ? true : false;
        $login = Auth::attempt([
            'username' => $request->username,
            'password' => $request->password
        ], $remember);

        // if login success
        if($login) return redirect()->route('auth.home');

        // if login unsuccessfull
        return back()->withInput()->withErrors(['credentials' => trans('auth.failed')]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('auth.login');
    }
}
