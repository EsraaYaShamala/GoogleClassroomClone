<?php

namespace App\Http\Controllers;

// use App\Http\Requests\Auth\LoginRequest as AuthLoginRequest;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function create()
    {
        return view('login');
    }
    public function store(LoginRequest $request)
    {
        $validate = $request->validated();
        $result = Auth::attempt($validate->only(['email', 'password']), $request->boolean('remember'));
        dd($result);

        if ($result) {
            return redirect()->intended('/');
        }
        return back()->withInput()->withErrors([
            'email' => 'Email or password is incorrect'
        ]);
    }
}
