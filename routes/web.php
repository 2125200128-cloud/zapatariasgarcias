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
Route::post('/empleado/guardar', [EmpleadoController::class, 'guardar']);
Route::get('/empleado/editar/{id}', [EmpleadoController::class, 'editar']);
Route::post('/empleado/actualizar/{id}', [EmpleadoController::class, 'actualizar']);
Route::get('/empleado/mostrar/{id}', [EmpleadoController::class, 'mostrar']);
Route::post('/empleado/eliminar/{id}', [EmpleadoController::class, 'eliminar']);
//------------------------------------->PROVEEDOR
Route::get('/proveedor', [ProveedorController::class, 'inicio']);
Route::get('/proveedor/formulario', [ProveedorController::class, 'formulario']);
Route::post('/proveedor/guardar', [ProveedorController::class, 'guardar']);
Route::get('/proveedor/editar/{id}', [ProveedorController::class, 'editar']);
Route::post('/proveedor/actualizar/{id}', [ProveedorController::class, 'actualizar']);
Route::get('/proveedor/mostrar/{id}', [ProveedorController::class, 'mostrar']);
Route::post('/proveedor/eliminar/{id}', [ProveedorController::class, 'eliminar']);
//------------------------------------->CLIENTE
Route::get('/cliente', [ClienteController::class, 'inicio']);
Route::get('/cliente/formulario', [ClienteController::class, 'formulario']);
Route::post('/cliente/guardar', [ClienteController::class, 'guardar']);
Route::get('/cliente/editar/{id}', [ClienteController::class, 'editar']);
Route::post('/cliente/actualizar/{id}', [ClienteController::class, 'actualizar']);
Route::get('/cliente/mostrar/{id}', [ClienteController::class, 'mostrar']);
Route::post('/cliente/eliminar/{id}', [ClienteController::class, 'eliminar']);
Route::post('/cliente/cambiarEstado/{id}', [ClienteController::class, 'cambiarEstado']);
//------------------------------------->PEDIDO
Route::get('/pedido', [PedidoController::class, 'inicio']);
Route::get('/pedido/formulario', [PedidoController::class, 'formulario']);
Route::post('/pedido/guardar', [PedidoController::class, 'guardar']);
Route::get('/pedido/editar/{id}', [PedidoController::class, 'editar']);
Route::post('/pedido/actualizar/{id}', [PedidoController::class, 'actualizar']);
Route::get('/pedido/mostrar/{id}', [PedidoController::class, 'mostrar']);
Route::post('/pedido/eliminar/{id}', [PedidoController::class, 'eliminar']);
//------------------------------------->PRODUCTO
Route::get('/producto', [ProductoController::class, 'inicio']);
Route::get('/producto/formulario', [ProductoController::class, 'formulario']);
Route::post('/producto/guardar', [ProductoController::class, 'guardar']);
Route::get('/producto/editar/{id}', [ProductoController::class, 'editar']);
Route::post('/producto/actualizar/{id}', [ProductoController::class, 'actualizar']);
Route::get('/producto/mostrar/{id}', [ProductoController::class, 'mostrar']);
Route::post('/producto/eliminar/{id}', [ProductoController::class, 'eliminar']);
//------------------------------------->SUCURSAL
Route::get('/sucursal', [SucursalController::class, 'inicio']);
Route::get('/sucursal/formulario', [SucursalController::class, 'formulario']);
Route::post('/sucursal/guardar', [SucursalController::class, 'guardar']);
Route::get('/sucursal/editar/{id}', [SucursalController::class, 'editar']);
Route::post('/sucursal/actualizar/{id}', [SucursalController::class, 'actualizar']);
Route::get('/sucursal/mostrar/{id}', [SucursalController::class, 'mostrar']);
Route::post('/sucursal/eliminar/{id}', [SucursalController::class, 'eliminar']);
//------------------------------------->CARRO
Route::get('/carro', [CarroController::class, 'inicio']);
Route::get('/carro/formulario', [CarroController::class, 'formulario']);
Route::post('/carro/guardar', [CarroController::class, 'guardar']);
Route::get('/carro/editar/{id}', [CarroController::class, 'editar']);
Route::post('/carro/actualizar/{id}', [CarroController::class, 'actualizar']);
Route::get('/carro/mostrar/{id}', [CarroController::class, 'mostrar']);
Route::post('/carro/eliminar/{id}', [CarroController::class, 'eliminar']);
//------------------------------------->CHOFER
Route::get('/chofer', [ChoferController::class, 'inicio']);
Route::get('/chofer/formulario', [ChoferController::class, 'formulario']);
Route::post('/chofer/guardar', [ChoferController::class, 'guardar']);
Route::get('/chofer/editar/{id}', [ChoferController::class, 'editar']);
Route::post('/chofer/actualizar/{id}', [ChoferController::class, 'actualizar']);
Route::get('/chofer/mostrar/{id}', [ChoferController::class, 'mostrar']);
Route::post('/chofer/eliminar/{id}', [ChoferController::class, 'eliminar']);
//------------------------------------->TRAYECTO
Route::get('/trayecto', [TrayectoController::class, 'inicio']);
Route::get('/trayecto/formulario', [TrayectoController::class, 'formulario']);
Route::get('/trayecto/lista', [TrayectoController::class, 'listado']);
Route::get('/trayecto/flota', [TrayectoController::class, 'flota']);
Route::get('/trayecto/flota/ubicaciones', [TrayectoController::class, 'ubicacionesFlota']);
Route::get('/trayecto/{id}/compartir', [TrayectoController::class, 'compartirUbicacion']);
Route::post('/trayecto/{id}/ubicacion', [TrayectoController::class, 'registrarUbicacion']);
//------------------------------------->INVENTARIO
Route::get('/inventario', [InventarioController::class, 'inicio']);
Route::get('/inventario/formulario', [InventarioController::class, 'formulario']);
Route::post('/inventario/guardar', [InventarioController::class, 'guardar']);
Route::get('/inventario/editar/{id}', [InventarioController::class, 'editar']);
Route::post('/inventario/actualizar/{id}', [InventarioController::class, 'actualizar']);
Route::get('/inventario/mostrar/{id}', [InventarioController::class, 'mostrar']);
Route::post('/inventario/eliminar/{id}', [InventarioController::class, 'eliminar']);
//------------------------------------->MARCA
Route::get('/marca', [MarcaController::class, 'inicio']);
Route::get('/marca/formulario', [MarcaController::class, 'formulario']);
Route::post('/marca/guardar', [MarcaController::class, 'guardar']);
Route::get('/marca/editar/{id}', [MarcaController::class, 'editar']);
Route::post('/marca/actualizar/{id}', [MarcaController::class, 'actualizar']);
Route::get('/marca/mostrar/{id}', [MarcaController::class, 'mostrar']);
Route::post('/marca/eliminar/{id}', [MarcaController::class, 'eliminar']);

//----------------------------------->INICIO
Route::get('/', [InicioController::class, 'inicio']);
