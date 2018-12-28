<?php

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

Route::auth();
Route::get('/', function () {
    return view('welcome');
});



Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>'auth'], function(){
    
    Route::get('/', 'UsuariosController@panel');
    Route::get('usuarios', 'UsuariosController@index')->name('usuarios.index');
    Route::get('usuarios/crear','UsuariosController@crear')->name('usuarios.crear');
    Route::post('RegistrarUsuario', 'UsuariosController@almacenarUsuario')->name('almacenarUsuario');

});


Route::group(['prefix'=>'trabajos','namespace'=>'Trabajos','middleware'=>'auth'], function(){

    //Rutas de las ordenes
    Route::get('ordenes/crear','OrdenesController@crear')->name('ordenes.crear');
    Route::post('ordenes/almacenar','OrdenesController@almacenar')->name('ordenes.almacenar');
    Route::get('ordenes/cotizadas', 'OrdenesController@cotizadas')->name('ordenes.cotizadas');
    Route::get('detalleOrden/{orden_id}','OrdenesController@detalleCotizadas')->name('ordenes.detalle');
    Route::post('asignarUsuarioOrden/', 'OrdenesController@asignarUsuarioOrden')->name('ordenes.asignarUsuarioOrden');
    Route::get('ordenes/asignadas', 'OrdenesController@asignadas')->name('ordenes.asignadas');
    Route::get('detalleAsignada/{orden_id}', 'OrdenesController@detalleAsignada')->name('detalle.asignadas');
    Route::post('ordenes/actualizar','OrdenesController@update')->name('ordenes.update');

    //Rutas de las sedes
    Route::get('sedes','SedesController@index')->name('sedes.index');
    Route::get('sedes/crear','SedesController@crear')->name('sedes.crear');
    Route::post('sedes/almacenar','SedesController@almacenar')->name('trabajos.sedes.almacenar');
    Route::post('sedes/{sede_id}','SedesController@actualizar')->name('trabajos.sedes.update');

    //Variables Editables -----------------------------------------------------------
    Route::get('variables', 'VariableController@index')->name('variables.index');
    Route::post('variables/{variable_id}', 'VariableController@update')->name('trabajos.variables.update');

});

    
