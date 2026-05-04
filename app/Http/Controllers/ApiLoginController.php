<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;

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
            $response = Http::timeout(10)->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ])->get('http://autentificaticapi.carabineros.cl/api/auth/validate-token');

            if ($response->failed()) {
                Log::warning('Login fallido: token inválido', ['rut' => $rut, 'ip' => $ip, 'status' => $response->status()]);
                $errorMessage = $response->json('message') ?? $response->body();
                return back()->withErrors(['msg' => 'La sesión con la API Institucional no es válida o su token ha expirado.']);
            }

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Error de conexión con Autentificatic API: ' . $e->getMessage(), ['ip' => $ip]);
            return back()->withErrors(['msg' => 'Error de conexión con el servidor de validación. Intente nuevamente.']);
        } catch (\Exception $e) {
            Log::error('Error inesperado en login API: ' . $e->getMessage(), ['ip' => $ip]);
            return back()->withErrors(['msg' => 'Error inesperado al validar token.']);
        }

        $user = User::where('Rut', $rut)->first();

        if (!$user) {
            Log::warning('Login fallido: RUT autenticado en API pero sin cuenta local', ['rut' => $rut, 'ip' => $ip]);
            return back()->withErrors(['msg' => 'Usted se autenticó correctamente en la API, pero no tiene cuenta habilitada en PacDilocar. Contacte al administrador.']);
        }

        Auth::login($user);
        $request->session()->regenerate();

        Log::info('Login exitoso', ['rut' => $rut, 'user_id' => $user->id, 'ip' => $ip]);

        return redirect()->intended(route('admin.index'));
    }
}