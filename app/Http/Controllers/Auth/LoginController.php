<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Get the post login redirect path.
     */
    protected function redirectTo(): string
    {
        $user = auth()->user();

        if ($user?->hasRole('administrador')) {
            return route('admin.dashboard');
        }

        if ($user?->hasRole('encuestador')) {
            return route('surveyor.votes.create');
        }

        if ($user?->hasRole('veedor') || $user?->hasRole('observador')) {
            return route('observer.dashboard');
        }

        return route('home');
    }
}
