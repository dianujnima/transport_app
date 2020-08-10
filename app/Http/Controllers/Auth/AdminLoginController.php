<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AdminLoginController extends Controller 
{
    use AuthenticatesUsers;

    protected $redirectTo = '/web_admin/login';

    public function username()
    {
        return 'username';
    }

    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => ['logout']]);
    }

    public function showLoginForm()
    {
        return view('auth.admin.login');
    }

    public function login(\Illuminate\Http\Request $request)
    {
        $a = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            return redirect()->intended(route('admin.home'));
        }

        return redirect()
            ->back()
            ->withInput($request->only($request->only('email', 'remember')))
            ->withErrors(['email' => 'These credentials do not match our records.']);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect(route('admin.login'));
    }
}
