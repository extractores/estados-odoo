<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('export', 'ClienteController@export')->name('export');
Route::get('clientes/info','ClienteController@info');
Route::put('clientes/crear','ClienteController@crear');
Route::get('clientes/listado','ClienteController@listado');
Route::get('clientes/sic_proveedores','ClienteController@sic_proveedores');
Route::get('clientes/stock','ClienteController@stock');
Route::get('clientes/pedidos','ClienteController@pedidos');
Route::get('clientes/compras','ClienteController@compras');
Route::get('clientes/stocdos','ClienteController@stocdos');
Route::get('clientes/giros','ClienteController@giros');
Route::get('clientes/productos','ClienteController@productos');
Route::get('clientes/rechazos','ClienteController@rechazos');
Route::get('clientes/token','ClienteController@token');
Route::get('clientes/supervisores','ClienteController@supervisores');
Route::get('clientes/almacenes','ClienteController@almacenes');
Route::get('clientes/productosdos','ClienteController@productosdos');
Route::get('clientes/estadopedidos','ClienteController@estadopedidos');
Route::resource('clientes','ClienteController');
