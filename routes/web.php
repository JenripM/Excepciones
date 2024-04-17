<?php

use App\Http\Controllers\conexion;
use App\Http\Controllers\conexionController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RolController;
use App\Http\Middleware\StoreDsnInRequest;
use Illuminate\Support\Facades\Route;

//RUTA PAGINA INICIO
Route::get('/', function () {
    return view('login.login');
});

/*SECCION ROL*/
Route::resource("rol",RolController::class);
Route::get("rol/{id}/confirmar",[RolController::class,"confirmar"])->name('rol.confirmar');
Route::get("cancelar/rol",function(){
    return redirect()->route('rol.index')->with('datos','Acción Cancelada...!');
})->name('cancelar.rol');

/*SECCION USER*/
Route::resource("user",LoginController::class);
Route::put("user/{id}/update2",[LoginController::class,"update2"])->name('user.update2');
Route::get("user/{id}/perfil",[LoginController::class,"perfil"])->name('user.perfil');
Route::get("user/{id}/confirmar",[LoginController::class,"confirmar"])->name('user.confirmar');
Route::get("cancelar/user",function(){
    return redirect()->route('user.index')->with('datos','Acción Cancelada...!');
})->name('cancelar.user');



//LOGIN
Route::view('login',"/login/login")->name('login');
Route::view('registro',"/login/register")->name('registro');
Route::view('master',"/layout/plantilla")->name('master');

Route::post('/validar-registro',[LoginController::class,'register'])->name('validar-registro');
Route::post('/inicia-sesion',[LoginController::class,'login'])->name('inicia-sesion');
Route::get('/logout',[LoginController::class,'logout'])->name('logout');

//Ruta conexion
Route::POST('conexionSQL/', [conexion::class, 'conexion'])->name('conexionSQL');
Route::get('datosConexion/',[conexion::class,'datosConexion'])->name('datosConexion');
Route::get('datosConexion2/',[conexion::class,'tuMetodo'])->name('tuMetodo');
route::get('desconexion/',[conexion::class ,'desconexion'])->name('desconexion');
Route::get('tablasMostrar/', [conexion::class, 'tablasMostrar'])->name('tablasMostrar');
Route::get('mostrarModal/{tabla}', [conexion::class, 'mostrarModal'])->name('mostrarModal');

//Ruta excepcion SECUENCIALIDAD
Route::get('secuencialTablas', [conexion::class, 'secuencialTablas'])->name('secuencialTablas');
Route::get('secuenciasTablaShowColumn/{tabla}', [conexion::class, 'secuenciasTablaShowColumn'])->name('secuenciasTablaShowColumn');
Route::get('showContenidoColumn/{tabla}/{columna}', [conexion::class, 'showContenidoColumn'])->name('showContenidoColumn');
Route::get('evaluaSecuencialEXP1/{sel}', [conexion::class, 'evaluaSecuencialEXP1'])->name('evaluaSecuencialEXP1');
Route::get('guardarConsulta__1/{tabla}/{columna}/{sel}', [conexion::class, 'guardarConsulta__1'])->name('guardarConsulta__1');
Route::get('guardarConsulta__3/{tabla}/{columna}/{sel}/{sel2}/{sel3}', [conexion::class, 'guardarConsulta__3'])->name('guardarConsulta__3');
Route::get('mostrarConsulta__1', [conexion::class, 'mostrarConsulta__1'])->name('mostrarConsulta__1');
//Ruta excepcion CAMPOS
Route::get('integridadCampoTablas', [conexion::class, 'integridadCampoTablas'])->name('integridadCampoTablas');
Route::get('secuenciasTablaShowColumnParam/{tabla}', [conexion::class, 'secuenciasTablaShowColumnParam'])->name('secuenciasTablaShowColumnParam');
Route::get('obtieneTipoDatoParam/{tabla}/{tabla2}', [conexion::class, 'obtieneTipoDatoParam'])->name('obtieneTipoDatoParam');
Route::get('showOptionParam', [conexion::class, 'showOptionParam'])->name('showOptionParam');
Route::get('mostrarParametrosIntegridadCampo/{tabla}', [conexion::class, 'mostrarParametrosIntegridadCampo'])->name('mostrarParametrosIntegridadCampo');
Route::get('evaluaIntegridadCampos/{tabla}/{columna}/{nullOp}/{camposColumn}/{tipoDato}', [conexion::class, 'evaluaIntegridadCampos'])->name('evaluaIntegridadCampos');
//subrutas ↑ 
route::get('parametroNULL/{tabla}/{columna}',[conexion::class,'parametroNULL'])->name('parametroNULL');
route::get('parametrovalores/{tabla}/{columna}/{valores}',[conexion::class,'parametrovalores'])->name('parametrovalores');
route::get('parametroTipoDato/{tabla}/{columna}/{valores}',[conexion::class,'parametroTipoDato'])->name('parametroTipoDato');
route::get('parametroRango/{tabla}/{columna}/{valores}/{val}',[conexion::class,'parametroRango'])->name('parametroRango');
route::get('parametroRangoFecha/{tabla}/{columna}/{valores}/{val}',[conexion::class,'parametroRangoFecha'])->name('parametroRangoFecha');

//Ruta excepcion cabecera
Route::get('cabeceraTablas', [conexion::class, 'cabeceraTablas'])->name('cabeceraTablas');
Route::get('cabeShowCmbSel/{tabla}/{sel}', [conexion::class, 'cabeShowCmbSel'])->name('cabeShowCmbSel');
Route::get('detaRdbShow/{tabla}/{tablac}/{sel}', [conexion::class, 'detaRdbShow'])->name('detaRdbShow');
Route::get('evaluaCabeDetalle/{tablaC}/{tablaD}/{columnaC}/{columnaD}/{sel}', [conexion::class, 'evaluaCabeDetalle'])->name('evaluaCabeDetalle');
//REPORTE
Route::get('secuencialidadREPORTE/', [conexion::class, 'secuencialidadREPORTE'])->name('secuencialidadREPORTE');
Route::get('camposREPORTE/', [conexion::class, 'camposREPORTE'])->name('camposREPORTE');
Route::get('cabeceraREPORTE/', [conexion::class, 'cabeceraREPORTE'])->name('cabeceraREPORTE');

Route::get('conexion', [conexionController::class, 'inicioConexion'])->name('conexion');
Route::post('connection', [conexionController::class, 'conection'])->name('conectado');
Route::get('listadoTablas', [conexionController::class, 'tablas'])->name('tablas');
Route::get('listadoTablas2', [conexionController::class, 'tablasCombo'])->name('tablasCombo');
Route::get('tablasExcepcionCampo', [conexionController::class, 'tablasExcepcionCampo'])->name('tablasExcepcionCampo');
Route::get('cabeceraExcepcion', [conexionController::class, 'cabeceraExcepcion'])->name('cabeceraExcepcion');

Route::get('tabla', [conexionController::class, 'hola'])->name('hola');
Route::get('columnas/{tabla}', [conexionController::class, 'mostrarColumnas'])->name('columnas');
Route::get('actualizarCombo2/{opcionSeleccionada}', [conexionController::class, 'actualizarCombo2'])->name('actualizarCombo2');
Route::get('actualizarTabla/{tabla}', [conexionController::class, 'actualizarTabla'])->name('actualizarTabla');
Route::get('mostrarColumnaParametro/{tabla}', [conexionController::class, 'mostrarColumnaParametro'])->name('mostrarColumnaParametro');
Route::get('evaluaSecuencial/', [conexionController::class, 'evaluaSecuencial'])->name('evaluaSecuencial');
Route::get('mostrarComboCabecera2/{tabla}', [conexionController::class, 'mostrarComboCabecera2'])->name('mostrarComboCabecera2');
Route::get('mostrarComboDetalle2/{tabla}', [conexionController::class, 'mostrarComboDetalle2'])->name('mostrarComboDetalle2');

Route::get('evaluaCampos/{columna}/{nullOp}/{camposColumn}/{tipoDato}', [conexionController::class, 'evaluaCampos'])->name('evaluaCampos');
route::Get('sqlDinamico',[conexion::class, 'sqlDinamico'])->name('sqlDinamico');
route::Get('sqlDinamico2/{sqlconsulta}',[conexion::class, 'sqlDinamico2'])->name('sqlDinamico2');

