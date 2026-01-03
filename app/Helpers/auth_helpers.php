<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('authUser')) {
    function authUser()
    {
        $guard = request()->is('admin/*') ? 'admin' : 'web';

        return Auth::guard($guard)->user();
    }
}
