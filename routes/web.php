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





//------------------------------------->EMPLEADO
Route::get('/empleado', [EmpleadoController::class, 'inicio']);
Route::get('/empleado/formulario', [EmpleadoController::class, 'formulario']);
//------------------------------------->PROVEEDOR
Route::get('/proveedor', [ProveedorController::class, 'inicio']);
Route::get('/proveedor/formulario', [ProveedorController::class, 'formulario']);
//------------------------------------->CLIENTE
Route::get('/cliente', [ClienteController::class, 'inicio']);
Route::get('/cliente/formulario', [ProveedorController::class, 'formulario']);
//------------------------------------->PEDIDO
Route::get('/pedido', [PedidoController::class, 'inicio']);
Route::get('/pedido/formulario', [PedidoController::class, 'formulario']);
//------------------------------------->PRODUCTO
Route::get('/producto', [ProductoController::class, 'inicio']);
Route::get('/producto/formulario', [ProductoController::class, 'formulario']);
//------------------------------------->SUCURSAL
Route::get('/sucursal', [SucursalController::class, 'inicio']);
Route::get('/sucursal/formulario', [SucursalController::class, 'formulario']);
//------------------------------------->CARRO
Route::get('/carro', [CarroController::class, 'inicio']);
Route::get('/carro/formulario', [CarroController::class, 'formulario']);
//------------------------------------->CHOFER
Route::get('/chofer', [ChoferController::class, 'inicio']);
Route::get('/chofer/formulario', [ChoferController::class, 'formulario']);
//------------------------------------->TRAYECTO
Route::get('/trayecto', [TrayectoController::class, 'inicio']);
Route::get('/trayecto/formulario', [TrayectoController::class, 'formulario']);
//------------------------------------->INVENTARIO  
Route::get('/inventario', [InventarioController::class, 'inicio']);
Route::get('/inventario/formulario', [InventarioController::class, 'formulario']);
//------------------------------------->MARCA
Route::get('/marca', [MarcaController::class, 'inicio']);  
Route::get('/inventario/formulario', [InventarioController::class, 'formulario']);

//----------------------------------->INICIO
Route::get('/', [InicioController::class, 'inicio']);

