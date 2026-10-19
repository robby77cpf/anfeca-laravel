<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::view('/', 'login')->name('home');
Route::view('/index', 'login')->name('index');
Route::view('/bienvenida', 'bienvenida')->name('bienvenida');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'usuario' => ['required', 'string'],
        'contrasena' => ['required', 'string'],
    ]);

    $registroTemporal = $request->session()->get('registro_temporal');
    $esRegistroTemporal = is_array($registroTemporal)
        && $credentials['usuario'] === $registroTemporal['correo']
        && Hash::check($credentials['contrasena'], $registroTemporal['password']);
    
    $esUsuarioDemo = $credentials['usuario'] === 'profegodi'
        && $credentials['contrasena'] === '676767';

    if (! $esRegistroTemporal && ! $esUsuarioDemo) {
        return back()->withErrors(['usuario' => 'Usuario o contraseña incorrectos.'])->onlyInput('usuario');
    }

    return to_route('bienvenida');
})->name('login.attempt');

Route::get('/registrarse', function (Request $request) {
    $request->session()->put('registro_iniciado', true);

    return to_route('datos');
})->name('registro.iniciar');

Route::match(['get', 'post'], '/datos', function (Request $request) {
    if (! $request->session()->get('registro_iniciado')) {
        return to_route('index');
    }

    if ($request->isMethod('post')) {
        $datos = $request->validate([
            'curp' => ['required', 'string'],
            'nombres' => ['required', 'string'],
            'rfc' => ['required', 'string'],
            'apellido_p' => ['required', 'string'],
            'cedula' => ['required', 'string'],
            'apellido_m' => ['nullable', 'string'],
            'password' => ['required', 'string'],
            'password_conf' => ['required', 'same:password'],
            'calle' => ['required', 'string'],
            'estado' => ['required', 'string'],
            'num_ext' => ['required', 'string'],
            'colonia' => ['required', 'string'],
            'num_int' => ['nullable', 'string'],
            'municipio' => ['required', 'string'],
            'cp' => ['required', 'string'],
            'correo' => ['required', 'email'],
            'tel_particular' => ['required', 'string'],
            'tel_celular' => ['required', 'string'],
        ]);

        $request->session()->put('registro_temporal', [
            'correo' => $datos['correo'],
            'password' => Hash::make($datos['password']),
        ]);
        $request->session()->forget('registro_iniciado');

        return to_route('index');
    }

    return view('datos');
})->name('datos');

// Rutas actualizadas para la Formación Académica
Route::get('/formacion', fn () => view('formacion'))->name('formacion');

Route::post('/formacion', function (Request $request) {
    $validated = $request->validate([
        'origen_nivel' => ['required', 'string'],
        'origen_titulo' => ['required', 'string'],
        'doc_origen_titulo' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        'doc_origen_cedula' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        
        'ultimo_nivel' => ['required', 'string', 'in:Licenciatura,Especialidad,Maestría,Doctorado'],
        'ultimo_titulo' => ['required', 'string'],
        'doc_ultimo_titulo' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        'doc_ultimo_cedula' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
    ]);

    // Para guardar físicamente los archivos subidos, puedes usar el Storage facade posteriormente:
    // if ($request->hasFile('doc_origen_titulo')) {
    //     $path = $request->file('doc_origen_titulo')->store('documentos_academicos');
    // }

    $request->session()->put('formacion_academica', $validated);

    // Redirección al siguiente paso del formulario
    return to_route('documentos'); 
})->name('certificacion.guardarAcademica');

Route::match(['get', 'post'], '/documentos', fn () => view('documentos'))->name('documentos');
Route::match(['get', 'post'], '/docs-refrendo', fn () => view('docsRefrendo'))->name('docsRefrendo');

Route::get('/certificacion', fn () => view('certificacion'))->name('certificacion');
Route::post('/certificacion', function (Request $request) {
    $validated = $request->validate([
        'aplica_art_14' => ['required', 'in:si,no'],
        'inciso_art_14' => ['nullable', 'required_if:aplica_art_14,si', 'in:14.1,14.2,14.3,14.4'],
    ], [
        'aplica_art_14.required' => 'Selecciona si corresponde a Refrendo por Art. 14.',
        'aplica_art_14.in' => 'La respuesta de Art. 14 no es válida.',
        'inciso_art_14.required_if' => 'Selecciona el inciso del Art. 14.',
        'inciso_art_14.in' => 'El inciso seleccionado no es válido.',
    ]);

    $request->session()->put('certificacion', $validated);

    return to_route('certificacion')->with('status', 'La certificación se guardó correctamente.');
})->name('certificacion.guardar');

Route::match(['get', 'post'], '/zona', fn () => view('zona'))->name('zona');
Route::match(['get', 'post'], '/recuperar', fn () => view('recuperar'))->name('recuperar');
Route::redirect('/editarDatos', '/index')->name('editarDatos');
Route::redirect('/recContraseña', '/recuperar');