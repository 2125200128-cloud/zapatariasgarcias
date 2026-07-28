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
// Route::view('login','/login/inicio');
//----------------------------------->Login<--------------------------------------\\
Route::get('login', [InicioController::class, 'login']);





//------------------------------------->EMPLEADO
Route::get('/empleado', [EmpleadoController::class, 'inicio']);
Route::get('/empleado/formulario', [EmpleadoController::class, 'formulario']);
Route::get('/empleado/lista', [EmpleadoController::class, 'listado']);
Route::post('/empleado/guardar', [EmpleadoController::class, 'guardar']);
Route::get('/empleado/edicion/{id}', [EmpleadoController::class, 'editar']);
Route::post('/empleado/actualizar/{id}', [EmpleadoController::class, 'actualizar']);
Route::get('/empleado/mostrar/{id}', [EmpleadoController::class, 'mostrar']);
//------------------------------------->PROVEEDOR
Route::get('/proveedor', [ProveedorController::class, 'inicio']);
Route::get('/proveedor/formulario', [ProveedorController::class, 'formulario']);
Route::get('/proveedor/lista', [ProveedorController::class, 'listado']);
Route::post('/proveedor/guardar', [ProveedorController::class, 'guardar']);
Route::get('/proveedor/edicion/{id}', [ProveedorController::class, 'editar']);
Route::post('/proveedor/actualizar/{id}', [ProveedorController::class, 'actualizar']);
Route::get('/proveedor/mostrar/{id}', [ProveedorController::class, 'mostrar']);
//------------------------------------->CLIENTE
Route::get('/cliente', [ClienteController::class, 'inicio']);
Route::get('/cliente/formulario', [ProveedorController::class, 'formulario']);
Route::get('/cliente/lista',[ClienteController::class,'listado']);
Route::post('/cliente/guardar', [ClienteController::class, 'guardar']);
Route::get('/cliente/edicion/{id}', [ClienteController::class, 'editar']);
Route::post('/cliente/actualizar/{id}', [ClienteController::class, 'actualizar']);
Route::get('/cliente/mostrar/{id}', [ClienteController::class, 'mostrar']);

Route::get('/cliente/borrado/{id}', [ClienteController::class, 'mostrar']);
Route::post('/cliente/eliminar/{id}', [ClienteController::class, 'eliminar']);
//------------------------------------->PEDIDO
Route::get('/pedido', [PedidoController::class, 'inicio']);
Route::get('/pedido/formulario', [PedidoController::class, 'formulario']);
Route::get('/pedido/lista', [PedidoController::class, 'listado']);
Route::post('/pedido/guardar', [PedidoController::class, 'guardar']);
Route::get('/pedido/edicion/{id}', [PedidoController::class, 'editar']);
Route::post('/pedido/actualizar/{id}', [PedidoController::class, 'actualizar']);
Route::get('/pedido/mostrar/{id}', [PedidoController::class, 'mostrar']);
//------------------------------------->PRODUCTO
Route::get('/producto', [ProductoController::class, 'inicio']);
Route::get('/producto/formulario', [ProductoController::class, 'formulario']);
Route::get('/producto/lista', [ProductoController::class, 'listado']);
Route::post('/producto/guardar', [ProductoController::class, 'guardar']);
Route::get('/producto/edicion/{id}', [ProductoController::class, 'editar']);
Route::post('/producto/actualizar/{id}', [ProductoController::class, 'actualizar']);
Route::get('/producto/mostrar/{id}', [ProductoController::class, 'mostrar']);
//------------------------------------->SUCURSAL
Route::get('/sucursal', [SucursalController::class, 'inicio']);
Route::get('/sucursal/formulario', [SucursalController::class, 'formulario']);
Route::get('/sucursal/lista', [SucursalController::class, 'listado']);
Route::post('/sucursal/guardar', [SucursalController::class, 'guardar']);
Route::get('/sucursal/edicion/{id}', [SucursalController::class, 'editar']);
Route::post('/sucursal/actualizar/{id}', [SucursalController::class, 'actualizar']);
Route::get('/sucursal/mostrar/{id}', [SucursalController::class, 'mostrar']);
//------------------------------------->CARRO
Route::get('/carro', [CarroController::class, 'inicio']);
Route::get('/carro/formulario', [CarroController::class, 'formulario']);
Route::get('/carro/lista', [CarroController::class, 'listado']);
Route::post('/carro/guardar', [CarroController::class, 'guardar']);
Route::get('/carro/edicion/{id}', [CarroController::class, 'editar']);
Route::post('/carro/actualizar/{id}', [CarroController::class, 'actualizar']);
Route::get('/carro/mostrar/{id}', [CarroController::class, 'mostrar']);
//------------------------------------->CHOFER
Route::get('/chofer', [ChoferController::class, 'inicio']);
Route::get('/chofer/formulario', [ChoferController::class, 'formulario']);
Route::get('/chofer/lista', [ChoferController::class, 'listado']);
Route::post('/chofer/guardar', [ChoferController::class, 'guardar']);
Route::get('/chofer/edicion/{id}', [ChoferController::class, 'editar']);
Route::post('/chofer/actualizar/{id}', [ChoferController::class, 'actualizar']);
Route::get('/chofer/mostrar/{id}', [ChoferController::class, 'mostrar']);
//------------------------------------->TRAYECTO
Route::get('/trayecto', [TrayectoController::class, 'inicio']);
Route::get('/trayecto/formulario', [TrayectoController::class, 'formulario']);
Route::get('/trayecto/lista', [TrayectoController::class, 'listado']);
Route::post('/trayecto/guardar', [TrayectoController::class, 'guardar']);
Route::get('/trayecto/edicion/{id}', [TrayectoController::class, 'editar']);
Route::post('/trayecto/actualizar/{id}', [TrayectoController::class, 'actualizar']);
Route::get('/trayecto/mostrar/{id}', [TrayectoController::class, 'mostrar']);
//------------------------------------->INVENTARIO  
Route::get('/inventario', [InventarioController::class, 'inicio']);
Route::get('/inventario/formulario', [InventarioController::class, 'formulario']);
Route::get('/inventario/lista', [InventarioController::class, 'listado']);
Route::post('/inventario/guardar', [InventarioController::class, 'guardar']);
Route::get('/inventario/edicion/{id}', [InventarioController::class, 'editar']);
Route::post('/inventario/actualizar/{id}', [InventarioController::class, 'actualizar']);
Route::get('/inventario/mostrar/{id}', [InventarioController::class, 'mostrar']);
//------------------------------------->MARCA
Route::get('/marca', [MarcaController::class, 'inicio']);  
Route::get('/marca/formulario', [MarcaController::class, 'formulario']);
Route::get('/marca/lista', [MarcaController::class, 'listado']);
Route::post('/marca/guardar', [MarcaController::class, 'guardar']);
Route::get('/marca/edicion/{id}', [MarcaController::class, 'editar']);
Route::post('/marca/actualizar/{id}', [MarcaController::class, 'actualizar']);
Route::get('/marca/mostrar/{id}', [MarcaController::class, 'mostrar']);

//----------------------------------->INICIO<--------------------------------------\\
Route::get('/', [InicioController::class, 'inicio']);

