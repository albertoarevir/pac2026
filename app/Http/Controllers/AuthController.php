<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Método para mostrar el formulario de login (la vista inicio.blade.php)
    public function showLoginForm()
    {
        return view('inicio');
    }

    // Método para procesar los datos del formulario y comunicarse con la API
    public function login(Request $request)
    {
        $rut = $request->input('rut_funcionario');
        $password = $request->input('clave_intranet');
        $website = $request->input('website');

        try {
            // Realizar la llamada a la API desde el servidor de Laravel
            $response = Http::post('http://autentificaticapi.carabineros.cl/api/auth/login', [
                'rut' => $rut,
                'password' => $password,
                'website' => $website,
            ]);

            if ($response->successful()) {
                $userData = $response->json()['success'];

                $user = User::where('rut', $rut)->first();

                if (!$user) {
                    $user = User::create([
                        'name' => $userData['nombre'] ?? 'Usuario ' . $rut,
                        'rut' => $rut,
                        'password' => \Illuminate\Support\Facades\Hash::make($password),
                    ]);
                }

                Auth::login($user, true);
                session(['api_token' => $userData['access_token']]);

                return redirect()->intended('/dashboard');

            } else {
                $errors = $response->json()['errors'];
                // Devuelve los errores de la API al formulario
                return back()->withErrors(['rut_funcionario' => $errors['rut'] ?? 'Error de autenticación.'])->onlyInput('rut_funcionario');
            }
        } catch (\Exception $e) {
            return back()->withErrors(['rut_funcionario' => 'No se pudo conectar con el servidor de autenticación.'])->onlyInput('rut_funcionario');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}