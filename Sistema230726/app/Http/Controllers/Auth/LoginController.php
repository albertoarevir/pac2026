<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/pac';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            Bitacora::create([
                'user_id'     => $user->id,
                'modulo'      => 'Acceso al Sistema',
                'accion'      => 'logout',
                'descripcion' => 'Cierre de sesion',
                'ip'          => $request->ip(),
                'user_agent'  => $request->userAgent(),
            ]);
        }

        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}