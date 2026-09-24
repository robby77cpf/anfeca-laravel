<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'login');
Route::match(['get', 'post'], '/datos', fn() => view('datos'));
Route::match(['get', 'post'], '/formacion', fn() => view('formacion'));
Route::match(['get', 'post'], '/documentos', fn() => view('documentos'));
Route::match(['get', 'post'], '/zona', fn() => view('zona'));
Route::match(['get', 'post'], '/recuperar', fn() => view('recuperar'));
Route::redirect('/recContraseña', '/recuperar');
