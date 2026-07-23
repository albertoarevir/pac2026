<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(fn() => route('login.custom'));

        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        $middleware->alias([
            'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Sesión expirada (419)
        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, Request $request) {
            if ($e->getStatusCode() == 419) {
                \Illuminate\Support\Facades\Auth::logout();
                return redirect()
                    ->route('login.custom')
                    ->with('info', 'Su sesión ha expirado por inactividad. Por favor, ingrese de nuevo.');
            }
        });

        // Sin permiso (rol o permiso de Spatie)
        $exceptions->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, Request $request) {
            return redirect()
                ->route('login.custom')
                ->with('info', 'No tienes permiso para acceder a esa sección.');
        });

        // Ruta no encontrada (404)
        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, Request $request) {
            return redirect()
                ->route('login.custom')
                ->with('info', 'La página que buscas no existe.');
        });
    })->create();