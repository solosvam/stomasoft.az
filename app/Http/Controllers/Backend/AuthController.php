<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function loginpage()
    {
        return Auth::guard()->check()
            ? redirect()->route('admin.main')
            : view('admin.pages.authentication.login');
    }

    public function login(LoginRequest $request)
    {
        $host = $request->getHost();

        $isDemoDomain = str_starts_with($host, 'demo.');

        if (!$isDemoDomain && $request->login === 'demo') {
            return back()->withErrors([
                'login' => 'Demo hesab yalnız demo domenində işləyir.'
            ]);
        }

        $credentials = $request->only('login', 'password');

        if (Auth::guard()->attempt($credentials,true)) {
            $user = Auth::guard()->user();
            $request->checkAccount($user);
            return redirect()->route('admin.main');
        }else{
            throw ValidationException::withMessages([
                'password' => trans('Şifrə səhfdir'),
            ]);
        }
    }

    public function logout()
    {
        Auth::guard()->logout();
        return redirect(route('admin.login'));
    }

}
