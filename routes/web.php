<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::view('/', 'login')->name('home');
Route::view('/index', 'login')->name('index');
Route::view('/bienvenida', 'bienvenida')->name('bienvenida');
Route::post('/login', function (Request $request) {
	$credentials = $request->validate([
		'usuario' => ['required', 'string'],
		'contrasena' => ['required', 'string'],
	]);

	if ($credentials['usuario'] !== 'profegodi' || $credentials['contrasena'] !== '676767') {
		return back()->withErrors(['usuario' => 'Usuario o contraseña incorrectos.'])->onlyInput('usuario');
	}

	return to_route('bienvenida');
})->name('login.attempt');
Route::get('/registrarse', function (Request $request) {
	$request->session()->put('registro_iniciado', true);

	return to_route('datos');
})->name('registro.iniciar');
Route::match(['get', 'post'], '/datos', function (Request $request) {
	if (!$request->session()->get('registro_iniciado')) {
		return to_route('index');
	}

	return view('datos');
})->name('datos');
Route::match(['get', 'post'], '/formacion', fn() => view('formacion'))->name('formacion');
Route::match(['get', 'post'], '/documentos', fn() => view('documentos'))->name('documentos');
Route::match(['get', 'post'], '/zona', fn() => view('zona'))->name('zona');
Route::match(['get', 'post'], '/recuperar', fn() => view('recuperar'))->name('recuperar');
Route::redirect('/editarDatos', '/index')->name('editarDatos');
Route::redirect('/recContraseña', '/recuperar');
