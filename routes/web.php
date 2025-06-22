<?php

use App\Http\Controllers\AlmacenesController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\GastosController;
use App\Http\Controllers\GraficasController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\ReporEstadisController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RecepcionesController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\SucursalesController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\TipoGastosController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VentasController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('auth.login');

// Protege las rutas de usuarios con el permiso `users.index`
Route::middleware(['auth', 'can:users.index'])->group(function () {
    Route::resource('users', UserController::class)->names('users');
});
Route::resource('users', UserController::class)->names('users');
Route::resource('sucursales',SucursalesController::class)->names('sucursales');
Route::resource('productos', ProductosController::class)->names('productos');
Route::resource('proveedores', ProveedoresController::class)->names('proveedores');
Route::resource('tipogastos', TipoGastosController::class)->names('tipogastos');
Route::resource('ventas', VentasController::class)->names('ventas');
Route::resource('compras', ComprasController::class)->names('compras');
Route::resource('roles', RoleController::class)->names('roles');

Route::get('ganancias-sucusal', [ReporEstadisController::class ,'Ganancias_Sucursal']);
Route::get('ganancias-sucusal-producto', [ReporEstadisController::class, 'Ganancias_Sucursal_Productos']);
Route::get('ganancias-productos', [ReporEstadisController::class, 'Ganancias_Productos']);
Route::get('ventas-ganancias-sucursal', [ReporEstadisController::class, 'ventas_ganancias_sucursal']);
Route::get('ventas-ganancias-productos', [ReporEstadisController::class, 'ventas_ganancias_productos']);

Route::get('gastos/create/{idcompra}', [GastosController::class, 'create'])->name('gastos.create');


Route::get('gastos/create/{idcompra}', [GastosController::class, 'create'])->name('gastos.create');
Route::post('gastos', [GastosController::class, 'store'])->name('gastos.store');

Route::get('recepciones',[RecepcionesController::class, 'index'])->name('recepciones.index');
Route::get('recepciones/create/{idcompra}', [RecepcionesController::class, 'create'])->name('recepciones.create');
Route::post('recepciones', [RecepcionesController::class, 'store'])->name('recepciones.store');
Route::get('recepciones/{id}', [RecepcionesController::class, 'show'])->name('recepciones.show');

Route::get('graficas', [GraficasController::class, 'metas'])->name('graficas.metas');	

Route::get('reportes', [ReportesController::class, 'formdemandas'])->name('reportes.formdemandas');
Route::post('reportes', [ReportesController::class, 'demandas'])->name('reportes.demandas');