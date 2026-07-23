<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use App\Models\Pac;
use App\Models\Modalidad;
use App\Models\Orden;
use App\Models\User;
use App\Models\Project;
use App\Observers\PacObserver;
use App\Observers\ModalidadObserver;
use App\Observers\OrdenObserver;
use App\Events\PacRechazadoEvent;
use App\Listeners\CambiarEstadoModalidadListener;
use App\Policies\ProjectPolicy;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Pac::observe(PacObserver::class);
        Modalidad::observe(ModalidadObserver::class);
        Orden::observe(OrdenObserver::class);

        Event::listen(Registered::class, SendEmailVerificationNotification::class);
        Event::listen(PacRechazadoEvent::class, CambiarEstadoModalidadListener::class);

        Gate::define('eliminar-proyecto', function (User $user) {
            return $user->hasRole('ADMINISTRADOR');
        });

        Gate::policy(Project::class, ProjectPolicy::class);

        // Rate limiting por RUT: max 5 intentos por minuto
        RateLimiter::for('login-rut', function ($request) {
            return Limit::perMinute(5)->by($request->input('rut_funcionario', $request->ip()));
        });

        // Directiva Blade para inyectar nonce CSP en scripts e inline styles
        Blade::directive('cspNonce', function () {
            return "<?php echo 'nonce=\"' . (app()->bound('csp-nonce') ? app('csp-nonce') : '') . '\"'; ?>";
        });
    }
}