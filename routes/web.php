<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\EstadoModificacionController;
use App\Http\Controllers\FuenteFinanciamientoController;
use App\Http\Controllers\ApiLoginController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\PresupuestoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ReporteDashboardController;

// â”€â”€â”€ PÃ¡gina raÃ­z â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
Route::get('/', function () {
    return redirect()->route('login.custom');
});

// â”€â”€â”€ AutenticaciÃ³n â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
Route::get('/', [ApiLoginController::class, 'showLoginForm'])->name('login.custom');
Route::post('/login-api-process', [ApiLoginController::class, 'loginWithApi'])->name('login.api')->middleware(['throttle:5,1', 'throttle:login-rut']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout')->middleware('auth');

// â”€â”€â”€ Rutas de solo lectura de AJAX (lookups compartidos entre mÃ³dulos) â”€â”€â”€â”€â”€â”€
Route::middleware(['auth'])->group(function () {
    Route::get('/get-especies', [\App\Http\Controllers\PacController::class, 'getEspecies'])->name('get-especies');
    Route::get('/get-codigos',  [App\Http\Controllers\PacController::class,  'getCodigos'])->name('get-codigos');
});

// â”€â”€â”€ ADMINISTRADOR: gestiÃ³n de usuarios del sistema â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
Route::middleware(['auth', 'role:ADMINISTRADOR'])->group(function () {
    Route::get('/admin',                          [App\Http\Controllers\AdminController::class,   'index'])->name('admin.index');
    Route::get('/admin/usuarios',                 [App\Http\Controllers\UsuarioController::class, 'index'])->name('admin.usuarios.index');
    Route::get('/admin/usuarios/create',          [App\Http\Controllers\UsuarioController::class, 'create'])->name('admin.usuarios.create');
    Route::post('/admin/usuarios/create',         [App\Http\Controllers\UsuarioController::class, 'store'])->name('admin.usuarios.store');
    Route::get('/admin/usuarios/{id}',            [App\Http\Controllers\UsuarioController::class, 'show'])->name('admin.usuarios.show');
    Route::get('/admin/usuarios/{id}/edit',       [App\Http\Controllers\UsuarioController::class, 'edit'])->name('admin.usuarios.edit');
    Route::put('/admin/usuarios/{id}',            [App\Http\Controllers\UsuarioController::class, 'update'])->name('admin.usuarios.update');
    Route::get('/admin/usuarios/{id}/confirm-delete', [App\Http\Controllers\UsuarioController::class, 'confirmDelete'])->name('admin.usuarios.confirmDelete');
    Route::delete('/admin/usuarios/{id}',         [App\Http\Controllers\UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy');

    Route::resource('/asignarRol', \App\Http\Controllers\AsignarController::class)->names('asignar');
    Route::resource('/roles',   \App\Http\Controllers\RoleController::class)->names('roles');
    Route::post('/roles',       [App\Http\Controllers\RoleController::class,  'store'])->name('roles.store');
    Route::get('/roles/create', [\App\Http\Controllers\RoleController::class, 'create'])->name('roles.create');

    Route::resource('/permisos',   \App\Http\Controllers\PermisoController::class)->names('permisos');
    Route::post('/permisos',       [App\Http\Controllers\PermisoController::class,  'store'])->name('permisos.store');
    Route::get('/permisos/create', [\App\Http\Controllers\PermisoController::class, 'create'])->name('permisos.create');

});

// --- BITACORA -------------------------------------------------------------------
Route::middleware(['auth', 'permission:MENU BITACORA'])->group(function () {
    Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');
});

// â”€â”€â”€ AUTENTIFICATIC â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
Route::middleware(['auth', 'permission:MENU AUTENTIFICATIC'])->group(function () {
    Route::get('/Autenti/registerUser',  [App\Http\Controllers\Autenti\AutentiController::class, 'index'])->name('registerUser.form');
    Route::post('/Autenti/registerUser', [App\Http\Controllers\Autenti\AutentiController::class, 'store'])->name('registerUser.store');
});

// â”€â”€â”€ PLAN ANUAL DE COMPRAS â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
Route::middleware(['auth', 'permission:MENU PLAN ANUAL DE COMPRAS'])->group(function () {
    Route::get('/pac', [App\Http\Controllers\PacController::class, 'index'])->name('pac.index');
});

Route::middleware(['auth', 'permission:INGRESAR PROYECTO'])->group(function () {
    Route::get('/pac/create', [App\Http\Controllers\PacController::class, 'create'])->name('pac.create');
    Route::post('/pac',       [App\Http\Controllers\PacController::class, 'store'])->name('pac.store');
});

Route::middleware(['auth', 'permission:MODIFICAR PROYECTO'])->group(function () {
    Route::get('/pac/{id}/edit', [App\Http\Controllers\PacController::class, 'edit'])->name('pac.edit');
    Route::put('/pac/{id}',      [App\Http\Controllers\PacController::class, 'update'])->name('pac.update');
});

Route::middleware(['auth', 'permission:ELIMINAR PROYECTO'])->group(function () {
    Route::delete('/pac/{id}', [\App\Http\Controllers\PacController::class, 'destroy'])->name('destroy');
});

// â”€â”€â”€ LICITACIONES â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
Route::middleware(['auth', 'permission:MENU LICITACIONES'])->group(function () {
    Route::get('/modalidad', [\App\Http\Controllers\ModalidadController::class, 'index'])->name('modalidad.index');
});

Route::middleware(['auth', 'permission:INGRESAR LICITACION'])->group(function () {
    Route::get('/modalidad/create/{pac}/{id_mod?}', [\App\Http\Controllers\ModalidadController::class, 'create'])->name('modalidad.create');
    Route::post('/modalidad',                       [\App\Http\Controllers\ModalidadController::class, 'store'])->name('modalidad.store');
});

Route::middleware(['auth', 'permission:MODIFICAR LICITACION'])->group(function () {
    Route::get('/modalidad/{id}/edit', [\App\Http\Controllers\ModalidadController::class, 'edit'])->name('modalidad.edit');
    Route::put('/modalidad/{id}',      [\App\Http\Controllers\ModalidadController::class, 'update'])->name('modalidad.update');
});

Route::middleware(['auth', 'permission:ELIMINAR LICITACION'])->group(function () {
    Route::delete('/modalidad/{id}', [\App\Http\Controllers\ModalidadController::class, 'destroy'])->name('modalidad.destroy');
});

// â”€â”€â”€ ORDENES DE COMPRA â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
Route::middleware(['auth', 'permission:MENU ORDENES DE COMPRA'])->group(function () {
    Route::get('/ordenes', [\App\Http\Controllers\OrdenController::class, 'index'])->name('ordenes.index');
});

Route::middleware(['auth', 'permission:INGRESAR ORDEN DE COMPRA'])->group(function () {
    Route::get('/ordenes/create/{pac}/{modalidad}/{numero}/{id_mod}', [\App\Http\Controllers\OrdenController::class, 'create'])->name('ordenes.create');
    Route::post('/ordenes/store/{pac_id}/{id_mod}',                   [\App\Http\Controllers\OrdenController::class, 'store'])->name('ordenes.store');
});

Route::middleware(['auth', 'permission:MODIFICAR ORDEN DE COMPRA'])->group(function () {
    Route::get('/ordenes/{id}/edit', [\App\Http\Controllers\OrdenController::class, 'edit'])->name('ordenes.edit');
    Route::put('/ordenes/{ordenes}', [\App\Http\Controllers\OrdenController::class, 'update'])->name('ordenes.update');
});

Route::middleware(['auth', 'permission:ELIMINAR LICITACION'])->group(function () {
    Route::delete('/ordenes/{id}', [\App\Http\Controllers\OrdenController::class, 'destroy'])->name('ordenes.destroy');
});

// â”€â”€â”€ PRESUPUESTO â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
Route::middleware(['auth', 'permission:MENU PRESUPUESTO'])->group(function () {
    Route::get('/presupuesto/check-duplicate', [\App\Http\Controllers\PresupuestoController::class, 'checkDuplicate'])->name('presupuesto.check-duplicate');
    Route::resource('/presupuesto', \App\Http\Controllers\PresupuestoController::class)->names('presupuesto');
});

// â”€â”€â”€ DASHBOARD â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
Route::middleware(['auth', 'permission:MENU DASHBOARD'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
});

// â”€â”€â”€ REPORTES â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
Route::middleware(['auth', 'permission:MENU REPORTES'])->group(function () {
    Route::get('/reporte',           [ReporteController::class,          'index'])->name('reporte.index');
    Route::get('/reporte/dashboard', [ReporteDashboardController::class, 'index'])->name('reporte.dashboard');
});


// â”€â”€â”€ CONFIGURACIÃ“N â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
Route::middleware(['auth', 'permission:MENU CONFIGURACION'])->group(function () {
    Route::resource('/grados',              \App\Http\Controllers\GradoController::class);
    Route::resource('/departamentos',       \App\Http\Controllers\DepartamentoController::class);
    Route::resource('/especies',            \App\Http\Controllers\EspecieController::class);
    Route::resource('/estados',             \App\Http\Controllers\EstadoController::class);
    Route::resource('/clasificador',        \App\Http\Controllers\ClasificadorController::class);
    Route::resource('/unidadcompra',        \App\Http\Controllers\UnidadCompraController::class);
    Route::resource('/codigos',             \App\Http\Controllers\CodigoController::class);
    Route::resource('/tipodecompra',        \App\Http\Controllers\TipocompraController::class);
    Route::resource('estados-modificacion', EstadoModificacionController::class);
    Route::resource('fuentefinanciamiento', FuenteFinanciamientoController::class);

    Route::get('/estadolicitacion',                        [\App\Http\Controllers\EstadoLicitacionController::class, 'index'])->name('estadolicitacion.index');
    Route::get('/estadolicitacion/create',                 [\App\Http\Controllers\EstadoLicitacionController::class, 'create'])->name('estadolicitacion.create');
    Route::post('/estadolicitacion.store',                 [\App\Http\Controllers\EstadoLicitacionController::class, 'store'])->name('estadolicitacion.store');
    Route::get('/estadolicitacion/{id}/edit',              [\App\Http\Controllers\EstadoLicitacionController::class, 'edit'])->name('estadolicitacion.edit');
    Route::put('/estadolicitacion/{estadoLicitacion}',     [App\Http\Controllers\EstadoLicitacionController::class, 'update'])->name('estadolicitacion.update');

    Route::get('/estadocompras',                     [\App\Http\Controllers\EstadoCompraController::class, 'index'])->name('estadocompras.index');
    Route::get('/estadocompras/create',              [\App\Http\Controllers\EstadoCompraController::class, 'create'])->name('estadocompras.create');
    Route::post('/estadocompras.store',              [\App\Http\Controllers\EstadoCompraController::class, 'store'])->name('estadocompras.store');
    Route::get('/estadocompras/{id}/edit',           [\App\Http\Controllers\EstadoCompraController::class, 'edit'])->name('estadocompras.edit');
    Route::put('/estadocompras/{estadocom}',         [App\Http\Controllers\EstadoCompraController::class, 'update'])->name('estadocompras.update');
    Route::delete('/estadocompras/{id}',             [\App\Http\Controllers\EstadoCompraController::class, 'destroy'])->name('estadocompras.destroy');
});

// â”€â”€â”€ FUNCIONARIOS (sin menÃº explÃ­cito â€” solo autenticaciÃ³n) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
Route::middleware(['auth', 'role:ADMINISTRADOR'])->group(function () {
    Route::resource('/funcionarios', \App\Http\Controllers\FuncionarioController::class)->names('funcionarios');
    Route::post('/funcionarios',     [App\Http\Controllers\FuncionarioController::class, 'store'])->name('funcionarios.store');
});
