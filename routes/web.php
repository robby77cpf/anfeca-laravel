<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    $usuario = DB::table('usuario')->where('usua_nombre', $credentials['usuario'])->first();

    if (! $usuario || ! Hash::check($credentials['contrasena'], $usuario->usua_contrasenia)) {
        return back()->withErrors(['usuario' => 'Usuario o contraseña incorrectos.'])->onlyInput('usuario');
    }

    $request->session()->put('usuario_id', $usuario->usua_id_usuario);

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
            'curp' => ['required', 'string', 'max:18'],
            'nombres' => ['required', 'string'],
            'rfc' => ['required', 'string', 'max:13'],
            'apellido_p' => ['required', 'string'],
            'cedula' => ['required', 'string', 'max:20'],
            'apellido_m' => ['nullable', 'string'],
            'password' => ['required', 'string', 'min:8'],
            'password_conf' => ['required', 'same:password'],
            'calle' => ['required', 'string'],
            'estado' => ['required', 'integer', 'exists:estado,esta_id_estado'],
            'num_ext' => ['required', 'string'],
            'colonia' => ['required', 'string'],
            'num_int' => ['nullable', 'string'],
            'municipio' => ['required', 'integer', 'exists:alcaldia,alca_id_alcaldia'],
            'cp' => ['required', 'string', 'max:5'],
            'correo' => ['required', 'email', 'unique:persona,pers_correo_electronico'],
            'tel_particular' => ['required', 'string'],
            'tel_celular' => ['required', 'string'],
        ]);

        DB::transaction(function () use ($datos) {
            // 1. Colonia
            $colonia = DB::table('colonia')->where('colo_nombre', $datos['colonia'])->first();
            $coloniaId = $colonia?->colo_id_colonia
                ?? DB::table('colonia')->insertGetId(['colo_nombre' => $datos['colonia']], 'colo_id_colonia');

            // 2. Domicilio
            $domicilioId = DB::table('domicilio')->insertGetId([
                'domi_id_estado' => $datos['estado'],
                'domi_id_alcaldia' => $datos['municipio'],
                'domi_id_colonia' => $coloniaId,
                'domi_calle' => $datos['calle'],
                'domi_numero_exterior' => $datos['num_ext'],
                'domi_numero_interior' => $datos['num_int'] ?? null,
                'domi_codigo_postal' => $datos['cp'],
            ], 'domi_id_domicilio');

            // 3. Insertar Persona PRIMERO (usando la corrección de insertGetId)
            // Se omite temporalmente 'pers_id_usuario' para no romper la llave foránea
            $personaId = DB::table('persona')->insertGetId([
                'pers_id_domicilio' => $domicilioId,
                'pers_nombre' => $datos['nombres'],
                'pers_apellido_paterno' => $datos['apellido_p'],
                'pers_apellido_materno' => $datos['apellido_m'] ?? null,
                'pers_curp' => $datos['curp'],
                'pers_rfc' => $datos['rfc'],
                'pers_cedula_profesional' => $datos['cedula'],
                'pers_correo_electronico' => $datos['correo'],
            ], 'pers_id_persona');

            // 4. Obtener el ID del Rol
            $rolId = DB::table('rol')->where('rol_nombre', 'Solicitante')->value('rol_id_rol');

            // 5. Insertar Usuario DESPUÉS, vinculándolo a la persona ya existente
            $usuarioId = DB::table('usuario')->insertGetId([
                'usua_id_persona' => $personaId,
                'usual_id_rol' => $rolId,
                'usua_nombre' => $datos['correo'],
                'usua_contrasenia' => Hash::make($datos['password']),
                'usua_estatus' => 'Activo',
            ], 'usua_id_usuario');

            // 6. Actualizar la Persona con el ID del Usuario recién creado
            DB::table('persona')
                ->where('pers_id_persona', $personaId)
                ->update(['pers_id_usuario' => $usuarioId]);

            // 7. Insertar Contactos
            $tipoTelefonoId = DB::table('tipo_contacto')->where('tico_nombre', 'Teléfono')->value('tico_id_tipo_contacto');
            DB::table('contacto')->insert([
                ['cont_id_persona' => $personaId, 'cont_id_tipo_contacto' => $tipoTelefonoId, 'cont_contacto' => $datos['tel_particular']],
                ['cont_id_persona' => $personaId, 'cont_id_tipo_contacto' => $tipoTelefonoId, 'cont_contacto' => $datos['tel_celular']],
            ]);
        });

        $request->session()->forget('registro_iniciado');

        return to_route('index')->with('status', 'Registro guardado. Ya puedes iniciar sesión con tu correo y contraseña.');
    }

    return view('datos', [
        'estados' => DB::table('estado')->orderBy('esta_nombre')->get(),
        'alcaldias' => DB::table('alcaldia')->orderBy('alca_nombre')->get(),
    ]);
})->name('datos');

Route::match(['get', 'post'], '/formacion', fn () => view('formacion'))->name('formacion');
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