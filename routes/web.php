<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\CarroController;   
use App\Http\Controllers\ChoferController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\TrayectoController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ProveedorController;


// Route::get('/', function () {
//     return view('welcome');
// });
// Route::view('/empleado', '/empleado/inicio');

Route::get('/empleado', [EmpleadoController::class, 'inicio']);
// Route::view('/proveedor','/proveedor/inicio');
Route::get('/proveedor', [ProveedorController::class, 'inicio']);
Route::get('/cliente', [ClienteController::class, 'inicio']);
Route::get('/pedido', [PedidoController::class, 'inicio']);
Route::get('/producto', [ProductoController::class, 'inicio']);
Route::get('/sucursal', [SucursalController::class, 'inicio']);
Route::get('/carro', [CarroController::class, 'inicio']);
Route::get('/chofer', [ChoferController::class, 'inicio']);
Route::get('/trayecto', [TrayectoController::class, 'inicio']);
Route::get('/inventario', [InventarioController::class, 'inicio']);
Route::get('/marca', [MarcaController::class, 'inicio']);  
Route::get('/', [InicioController::class, 'inicio']);


