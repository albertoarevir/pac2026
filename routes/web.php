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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*
Route::get('/', function () {
    return view('auth/login');
});
*/


Route::get('/', function () {
    return redirect()->route('login.custom');
});

//Route::get('/', [App\Http\Controllers\InicioController::class, 'index'])->name('inicio');

//Route::get('/inicio', [App\Http\Controllers\InicioController::class, 'login']);

Auth::routes();
//Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');



// rutas para el admin
Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index')
    ->middleware('auth');

// rutas para el admin - usuarios
Route::get('/admin/usuarios', [App\Http\Controllers\UsuarioController::class, 'index'])->name('admin.usuarios.index')
    ->middleware('auth');
Route::get('/admin/usuarios/create', [App\Http\Controllers\UsuarioController::class, 'create'])->name('admin.usuarios.create')
    ->middleware('auth');
Route::post('/admin/usuarios/create', [App\Http\Controllers\UsuarioController::class, 'store'])->name('admin.usuarios.store')
    ->middleware('auth');
Route::get('/admin/usuarios/{id}', [App\Http\Controllers\UsuarioController::class, 'show'])->name('admin.usuarios.show')
    ->middleware('auth');
Route::get('/admin/usuarios/{id}/edit', [App\Http\Controllers\UsuarioController::class, 'edit'])->name('admin.usuarios.edit')
    ->middleware('auth');
Route::put('/admin/usuarios/{id}', [App\Http\Controllers\UsuarioController::class, 'update'])->name('admin.usuarios.update')
    ->middleware('auth');
Route::get('/admin/usuarios/{id}/confirm-delete', [App\Http\Controllers\UsuarioController::class, 'confirmDelete'])->name('admin.usuarios.confirmDelete')
    ->middleware('auth');
Route::delete('/admin/usuarios/{id}', [App\Http\Controllers\UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy')
    ->middleware('auth');

//rutas para el ingreso del pac
Route::get('/pac', [App\Http\Controllers\PacController::class, 'index'])->name('pac.index')
    ->middleware('auth');

Route::get('/pac/create', [App\Http\Controllers\PacController::class, 'create'])->name('pac.create')
    ->middleware('auth');
Route::get('/pac/{id}/edit', [App\Http\Controllers\PacController::class, 'edit'])->name('pac.edit')->middleware('auth');;
//Route::resource('/pac', \App\Http\Controllers\PacController::class);
Route::delete('/pac/{id}', [\App\Http\Controllers\PacController::class, 'destroy'])->name('destroy')
    ->middleware('auth');
Route::put('/pac/{id}', [App\Http\Controllers\PacController::class, 'update'])->name('pac.update')->middleware('auth');;
Route::post('/pac', [App\Http\Controllers\PacController::class, 'store'])->name('pac.store')->middleware('auth');;


Route::resource('/grados', \App\Http\Controllers\GradoController::class)->middleware('auth');;
Route::resource('/departamentos', \App\Http\Controllers\DepartamentoController::class)->middleware('auth');;
Route::resource('/especies', \App\Http\Controllers\EspecieController::class)->middleware('auth');;
Route::resource('/estados', \App\Http\Controllers\EstadoController::class)->middleware('auth');;
Route::resource('/clasificador', \App\Http\Controllers\ClasificadorController::class)->middleware('auth');;
Route::resource('/unidadcompra', \App\Http\Controllers\UnidadCompraController::class)->middleware('auth');;
Route::resource('/codigos', \App\Http\Controllers\CodigoController::class)->middleware('auth');;
Route::resource('/tipodecompra', \App\Http\Controllers\TipocompraController::class)->middleware('auth');;

// Ruta para mostrar el formulario de creación de modalidad
Route::post('/modalidad', [\App\Http\Controllers\ModalidadController::class, 'store'])->name('modalidad.store')->middleware('auth');;
Route::get('/modalidad', [\App\Http\Controllers\ModalidadController::class, 'index'])->name('modalidad.index')->middleware('auth');;
Route::get('/modalidad/{id}/edit', [\App\Http\Controllers\ModalidadController::class, 'edit'])->name('modalidad.edit')->middleware('auth');;
Route::put('/modalidad/{id}', [\App\Http\Controllers\ModalidadController::class, 'update'])->name('modalidad.update')->middleware('auth');;
Route::delete('/modalidad/{id}', [\App\Http\Controllers\ModalidadController::class, 'destroy'])->name('modalidad.destroy')->middleware('auth');;
Route::get('/modalidad/create/{pac}/{id_mod?}', [\App\Http\Controllers\ModalidadController::class, 'create'])->name('modalidad.create')->middleware('auth');;

//formulario ingreso estados de licitaciones
Route::get('/estadolicitacion', [\App\Http\Controllers\EstadoLicitacionController::class, 'index'])->name('estadolicitacion.index')->middleware('auth');;
Route::get('/estadolicitacion/{id}/edit', [\App\Http\Controllers\EstadoLicitacionController::class, 'edit'])->name('estadolicitacion.edit')->middleware('auth');;
Route::put('/estadolicitacion/{estadoLicitacion}', [App\Http\Controllers\EstadoLicitacionController::class, 'update'])->name('estadolicitacion.update')->middleware('auth');;
Route::delete('/estadolicitacion/{id}', [\App\Http\Controllers\EstadoLicitacionController::class, 'destroy'])->name('estadolicitacion.destroy')->middleware('auth');;
Route::get('/estadolicitacion/create', [\App\Http\Controllers\EstadoLicitacionController::class, 'create'])->name('estadolicitacion.create')->middleware('auth');;
Route::post('/estadolicitacion.store', [\App\Http\Controllers\EstadoLicitacionController::class, 'store'])->name('estadolicitacion.store')->middleware('auth');;


//formulario ingreso estados de Compras
Route::get('/estadocompras', [\App\Http\Controllers\EstadoCompraController::class, 'index'])->name('estadocompras.index')->middleware('auth');;
Route::get('/estadocompras/{id}/edit', [\App\Http\Controllers\EstadoCompraController::class, 'edit'])->name('estadocompras.edit')->middleware('auth');;
Route::put('/estadocompras/{estadocom}', [App\Http\Controllers\EstadoCompraController::class, 'update'])->name('estadocompras.update')->middleware('auth');;
Route::delete('/estadocompras/{id}', [\App\Http\Controllers\EstadoCompraController::class, 'destroy'])->name('estadocompras.destroy')->middleware('auth');;
Route::get('/estadocompras/create', [\App\Http\Controllers\EstadoCompraController::class, 'create'])->name('estadocompras.create')->middleware('auth');;
Route::post('/estadocompras.store', [\App\Http\Controllers\EstadoCompraController::class, 'store'])->name('estadocompras.store')->middleware('auth');;



//formulario ingreso ordenes de compras
Route::post('/ordenes/store/{pac_id}/{id_mod}', [\App\Http\Controllers\OrdenController::class, 'store'])->name('ordenes.store')->middleware('auth');;
Route::get('/ordenes', [\App\Http\Controllers\OrdenController::class, 'index'])->name('ordenes.index')->middleware('auth');;
Route::get('/ordenes/{id}/edit', [\App\Http\Controllers\OrdenController::class, 'edit'])->name('ordenes.edit')->middleware('auth');;
//Route::put('/ordenes/{id}', [\App\Http\Controllers\OrdenController::class, 'update'])->name('ordenes.update');
Route::delete('/ordenes/{id}', [\App\Http\Controllers\OrdenController::class, 'destroy'])->name('ordenes.destroy')->middleware('auth');;
Route::get('/ordenes/create/{pac}/{modalidad}/{numero}/{id_mod}', [\App\Http\Controllers\OrdenController::class, 'create'])->name('ordenes.create')->middleware('auth');;
Route::put('/ordenes/{ordenes}', [\App\Http\Controllers\OrdenController::class, 'update'])->name('ordenes.update')->middleware('auth');;

//select especies
Route::get('/get-especies', [\App\Http\Controllers\PacController::class, 'getEspecies'])->name('get-especies');
Route::get('/get-codigos', [App\Http\Controllers\PacController::class, 'getCodigos'])->name('get-codigos');

//Rutas para funcionarios
Route::resource('/funcionarios', \App\Http\Controllers\FuncionarioController::class)->names('funcionarios')->middleware('auth');;
Route::post('/funcionarios', [App\Http\Controllers\FuncionarioController::class, 'store'])->name('funcionarios.store')->middleware('auth');;

//Rutas para asignar
Route::resource('/asignarRol', \App\Http\Controllers\AsignarController::class)->names('asignar');

//Rutas para roles
Route::resource('/roles', \App\Http\Controllers\RoleController::class)->names('roles')->middleware('auth');
Route::post('/roles', [App\Http\Controllers\RoleController::class, 'store'])->name('roles.store')->middleware('auth');
Route::get('/roles/create', [\App\Http\Controllers\RoleController::class, 'create'])->name('roles.create')->middleware('auth');;


//Rutas para permisos
Route::resource('/permisos', \App\Http\Controllers\PermisoController::class)->names('permisos')->middleware('auth');
Route::post('/permisos', [App\Http\Controllers\PermisoController::class, 'store'])->name('permisos.store')->middleware('auth');;
Route::get('/permisos/create', [\App\Http\Controllers\PermisoController::class, 'create'])->name('permisos.create')->middleware('auth');;

//Rutas para dashboard
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

Route::resource('estados-modificacion', EstadoModificacionController::class);

Route::resource('fuentefinanciamiento', FuenteFinanciamientoController::class);


Route::get('/', [ApiLoginController::class, 'showLoginForm'])->name('login.custom');

Route::post('/login-api-process', [ApiLoginController::class, 'loginWithApi'])->name('login.api');


// Muestra el formulario
Route::get('/Autenti/registerUser', [App\Http\Controllers\Autenti\AutentiController::class, 'index'])
    ->name('registerUser.form')
    ->middleware('auth');

// Procesa el registro del usuario (si lo usas)
Route::post('/Autenti/registerUser', [App\Http\Controllers\Autenti\AutentiController::class, 'store'])
    ->name('registerUser.store')
    ->middleware('auth');

    //Route::middleware(['auth'])->group(function () {
    Route::get('/bitacora', [BitacoraController::class, 'index'])
        ->name('bitacora.index')
        ->middleware('auth');
    //});
    
   
   
Route::resource('/presupuesto', \App\Http\Controllers\PresupuestoController::class)->names('presupuesto')->middleware('auth');

//Route::get('/reporte', [ReporteController::class, 'index'])->name('reporte.index');
//Route::get('/reporte/dashboard', [ReporteDashboardController::class, 'index'])->name('reporte.dashboard');


// DESPUÉS ✅ - Con middleware auth
Route::middleware(['auth'])->group(function () {
    Route::get('/reporte', [ReporteController::class, 'index'])->name('reporte.index');
    Route::get('/reporte/dashboard', [ReporteDashboardController::class, 'index'])->name('reporte.dashboard');
});

