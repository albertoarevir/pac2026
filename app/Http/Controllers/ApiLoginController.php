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
                Log::error('Login fallido: token invalido', ['rut' => $rut, 'ip' => $ip, 'status' => $response->status()]);
                return back()->withErrors(['msg' => 'La sesion con la API Institucional no es valida o su token ha expirado.']);
            }

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Error de conexion con Autentificatic API: ' . $e->getMessage(), ['ip' => $ip]);
            return back()->withErrors(['msg' => 'Error de conexion con el servidor de validacion. Intente nuevamente.']);
        } catch (\Exception $e) {
            Log::error('Error inesperado en login API: ' . $e->getMessage(), ['ip' => $ip]);
            return back()->withErrors(['msg' => 'Error inesperado al validar token.']);
        }

        try {
            // Primero buscar usuario activo (no eliminado)
            $user = User::where('Rut', $rut)->first();

            // Si no hay usuario activo, buscar soft-deleted y restaurarlo
            if (!$user) {
                $userTrashed = User::onlyTrashed()->where('Rut', $rut)->first();

                if ($userTrashed) {
                    $userTrashed->restore();
                    $user = $userTrashed;
                }
            }

            if (!$user) {
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

            // Limpiar caché de permisos de Spatie para obtener datos frescos
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            $user->unsetRelation('roles')->unsetRelation('permissions');
            $user->load('roles', 'permissions');

            if ($user->roles->isEmpty()) {
                return back()->withErrors(['msg' => 'Su cuenta no tiene roles asignados. Contacte al administrador del sistema para que le asigne un perfil de acceso.']);
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

            if ($user->hasRole('ADMINISTRADOR')) {
                return redirect()->route('admin.index');
            }

            if ($user->hasPermissionTo('MENU PLAN ANUAL DE COMPRAS')) {
                return redirect()->route('pac.index');
            }

            // Autenticado pero sin permisos de acceso al sistema
            Auth::logout();
            $request->session()->invalidate();
            return back()->withErrors(['msg' => 'Su cuenta esta activa pero sin acceso al sistema. Contacte al administrador.']);

        } catch (\Exception $e) {
            Log::error('Error en proceso de login: ' . $e->getMessage(), ['rut' => $rut, 'ip' => $ip]);
            return back()->withErrors(['msg' => 'Error interno al procesar el login. Contacte al administrador.']);
        }
    }
}
