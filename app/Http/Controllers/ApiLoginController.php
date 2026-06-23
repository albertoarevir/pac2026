<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Bitacora;

class ApiLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('inicio');
    }

    public function loginWithApi(Request $request)
    {
        $request->validate([
            'rut_funcionario' => 'required|string',
            'accessToken'     => 'required|string',
        ]);

        $rut   = $request->input('rut_funcionario');
        $token = $request->input('accessToken');
        $ip    = $request->ip();

        try {
            // RIESGO: API sin HTTPS. Solicitar a TIC habilitar TLS en autentificaticapi.carabineros.cl
            $response = Http::timeout(10)->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ])->get(config('services.autentificatic.url'));

            if ($response->failed()) {
                Log::warning('Login fallido: token invalido', ['rut' => $rut, 'ip' => $ip, 'status' => $response->status()]);
                return back()->withErrors(['msg' => 'La sesion con la API Institucional no es valida o su token ha expirado.']);
            }

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Error de conexion con Autentificatic API: ' . $e->getMessage(), ['ip' => $ip]);
            return back()->withErrors(['msg' => 'Error de conexion con el servidor de validacion. Intente nuevamente.']);
        } catch (\Exception $e) {
            Log::error('Error inesperado en login API: ' . $e->getMessage(), ['ip' => $ip]);
            return back()->withErrors(['msg' => 'Error inesperado al validar token.']);
        }

        $user = User::where('Rut', $rut)->first();

        if (!$user) {
            Log::warning('Login fallido: RUT autenticado en API pero sin cuenta local', ['rut' => $rut, 'ip' => $ip]);
            Bitacora::create([
                'user_id'     => null,
                'modulo'      => 'Acceso al Sistema',
                'accion'      => 'login_sin_cuenta',
                'descripcion' => 'RUT validado en API institucional pero sin cuenta local: ' . $rut,
                'ip'          => $ip,
                'user_agent'  => request()->userAgent(),
            ]);
            return back()->withErrors(['msg' => 'Usted se autentico correctamente en la API, pero no tiene cuenta habilitada en PacDilocar. Contacte al administrador.']);
        }

        Auth::login($user);
        $request->session()->regenerate();

        Bitacora::create([
            'user_id'     => $user->id,
            'modulo'      => 'Acceso al Sistema',
            'accion'      => 'login',
            'descripcion' => 'Inicio de sesion exitoso via API institucional',
            'ip'          => $ip,
            'user_agent'  => $request->userAgent(),
        ]);

        Log::info('Login exitoso', ['rut' => $rut, 'user_id' => $user->id, 'ip' => $ip]);

        $destination = $user->hasRole('ADMINISTRADOR')
            ? route('admin.index')
            : route('pac.index');

        return redirect()->intended($destination);
    }
}
