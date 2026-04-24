<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; 
use App\Models\User;

class ApiLoginController extends Controller
{
    public function showLoginForm()
    {
        // Retorna la vista con el código HTML/JS que se encuentra en resources/views/inicio.blade.php
        return view('inicio'); 
    }

    public function loginWithApi(Request $request)
    {
        // 1. Validar que lleguen los datos del formulario (Rut y Token de Acceso)
        $request->validate([
            'rut_funcionario' => 'required|string',
            'accessToken' => 'required|string'
        ]);

        $rut = $request->input('rut_funcionario');
        $token = $request->input('accessToken');

        // 2. Validar token con la API Institucional122766942
        try {
            $response = Http::timeout(10)->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ])->get('http://autentificaticapi.carabineros.cl/api/auth/validate-token'); 
            
            if ($response->failed()) {
                $errorMessage = $response->json('message') ?? $response->body();
                return back()->withErrors(['msg' => 'La sesión con la API Institucional no es válida o su token ha expirado. Mensaje API: ' . $errorMessage]);
            }

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            \Log::error("Error de conexión con Autentificatic API: " . $e->getMessage());
            return back()->withErrors(['msg' => 'Error de conexión con servidor de validación de token. Por favor, intente nuevamente.']);
        } catch (\Exception $e) {
            \Log::error("Error inesperado en login API: " . $e->getMessage());
            return back()->withErrors(['msg' => 'Error inesperado al validar token.']);
        }

        // 3. Buscar al usuario en BD local
        $user = User::where('Rut', $rut)->first();

        if (!$user) {
            return back()->withErrors(['msg' => 'Usted se autenticó correctamente en la API Institucional, pero no tiene una cuenta habilitada en PacDilocar. Contacte al administrador.']);
        }

        // 4. Crear sesión Laravel
        Auth::login($user);
        $request->session()->regenerate();

        // 5. Redirigir al dashboard
        return redirect()->intended(route('admin.index'));
    }
}
