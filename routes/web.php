<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::view('/', 'login')->name('home');
Route::view('/index', 'login')->name('index');

Route::get('/bienvenida', function (Request $request) {
    $usuarioId = $request->session()->get('usuario_id');

    // Si no hay sesión iniciada, regresa al login
    if (! $usuarioId) {
        return to_route('index');
    }

    $persona = DB::table('usuario')
        ->join('persona', 'persona.pers_id_persona', '=', 'usuario.usua_id_persona')
        ->join('domicilio', 'domicilio.domi_id_domicilio', '=', 'persona.pers_id_domicilio')
        ->join('colonia', 'colonia.colo_id_colonia', '=', 'domicilio.domi_id_colonia')
        ->join('alcaldia', 'alcaldia.alca_id_alcaldia', '=', 'domicilio.domi_id_alcaldia')
        ->join('estado', 'estado.esta_id_estado', '=', 'domicilio.domi_id_estado')
        ->where('usuario.usua_id_usuario', $usuarioId)
        ->select(
            'persona.*',
            'domicilio.domi_calle',
            'domicilio.domi_numero_exterior',
            'domicilio.domi_numero_interior',
            'domicilio.domi_codigo_postal',
            'colonia.colo_nombre',
            'alcaldia.alca_nombre',
            'estado.esta_nombre'
        )
        ->first();

    if (! $persona) {
        // Usuarios sin persona (coordinador / superadministrador) van a su pantalla
        $rol = DB::table('usuario')
            ->join('rol', 'rol.rol_id_rol', '=', 'usuario.usual_id_rol')
            ->where('usuario.usua_id_usuario', $usuarioId)
            ->value('rol.rol_nombre');

        if ($rol && in_array(strtolower($rol), ['coordinador', 'superadministrador'], true)) {
            return to_route('zona');
        }

        // El usuario ya no existe en la base: cierra la sesión
        $request->session()->forget('usuario_id');

        return to_route('index');
    }

    // Los teléfonos se guardaron en orden: primero particular, luego celular
    $telefonos = DB::table('contacto')
        ->where('cont_id_persona', $persona->pers_id_persona)
        ->orderByRaw('ctid')
        ->pluck('cont_contacto');

    $nombreCompleto = trim(
        $persona->pers_nombre.' '.$persona->pers_apellido_paterno.' '.($persona->pers_apellido_materno ?? '')
    );

    return view('bienvenida', [
        'persona' => $persona,
        'nombreCompleto' => $nombreCompleto,
        'telParticular' => $telefonos[0] ?? '',
        'telCelular' => $telefonos[1] ?? '',
    ]);
})->name('bienvenida');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'usuario' => ['required', 'string'],
        'contrasena' => ['required', 'string'],
    ]);

    $usuario = DB::table('usuario')->where('usua_nombre', $credentials['usuario'])->first();

    if (! $usuario || ! Hash::check($credentials['contrasena'], $usuario->usua_contrasenia)) {
        return back()->withErrors(['usuario' => 'Usuario o contraseña incorrectos.'])->onlyInput('usuario');
    }

    $request->session()->regenerate();
    $request->session()->put('usuario_id', $usuario->usua_id_usuario);
    $request->session()->put('usuario_nombre', $usuario->usua_nombre);

    $rol = DB::table('rol')->where('rol_id_rol', $usuario->usual_id_rol)->value('rol_nombre');

    return in_array(strtolower((string) $rol), ['coordinador', 'superadministrador'], true)
        ? to_route('zona')
        : to_route('bienvenida');
})->name('login.attempt');

Route::get('/salir', function (Request $request) {
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return to_route('index');
})->name('logout');

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
        // 1. Obtener los IDs como ya lo hacias
        $personaId = DB::selectOne("SELECT nextval('persona_pers_id_persona_seq') AS id")->id;
        $usuarioId = DB::selectOne("SELECT nextval('usuario_usua_id_usuario_seq') AS id")->id;

        // 2. Colonia y Domicilio (Se queda igual)
        $colonia = DB::table('colonia')->where('colo_nombre', $datos['colonia'])->first();
        $coloniaId = $colonia?->colo_id_colonia
            ?? DB::table('colonia')->insertGetId(['colo_nombre' => $datos['colonia']], 'colo_id_colonia');

        $domicilioId = DB::table('domicilio')->insertGetId([
            'domi_id_estado' => $datos['estado'],
            'domi_id_alcaldia' => $datos['municipio'],
            'domi_id_colonia' => $coloniaId,
            'domi_calle' => $datos['calle'],
            'domi_numero_exterior' => $datos['num_ext'],
            'domi_numero_interior' => $datos['num_int'] ?? null,
            'domi_codigo_postal' => $datos['cp'],
        ], 'domi_id_domicilio');

        $rolId = DB::table('rol')->where('rol_nombre', 'Solicitante')->value('rol_id_rol');

        // ---------------- CAMBIO CRÍTICO DE ORDEN ---------------- //

        // 3. INSERTAR PERSONA PRIMERO 
        // (Omitimos 'pers_id_usuario' o lo mandamos como null para que no marque error)
        DB::table('persona')->insert([
            'pers_id_persona' => $personaId,
            'pers_id_domicilio' => $domicilioId,
            'pers_id_usuario' => null, // <-- NULO TEMPORALMENTE
            'pers_nombre' => $datos['nombres'],
            'pers_apellido_paterno' => $datos['apellido_p'],
            'pers_apellido_materno' => $datos['apellido_m'] ?? null,
            'pers_curp' => $datos['curp'],
            'pers_rfc' => $datos['rfc'],
            'pers_cedula_profesional' => $datos['cedula'],
            'pers_correo_electronico' => $datos['correo'],
        ]);

        // 4. INSERTAR USUARIO DESPUÉS
        // (Ahora sí podemos asignarle la persona porque ya existe en la BD)
        DB::table('usuario')->insert([
            'usua_id_usuario' => $usuarioId,
            'usua_id_persona' => $personaId,
            'usual_id_rol' => $rolId,
            'usua_nombre' => $datos['correo'],
            'usua_contrasenia' => Hash::make($datos['password']),
            'usua_estatus' => 'Activo',
        ]);

        // 5. ACTUALIZAR LA PERSONA
        // (Le devolvemos a la persona el ID de su usuario)
        DB::table('persona')
            ->where('pers_id_persona', $personaId)
            ->update(['pers_id_usuario' => $usuarioId]);

        // ---------------- FIN DEL CAMBIO ---------------- //

        // 6. Insertar Contactos (Se queda igual)
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

    return to_route('documentos')->with('status', 'La certificación se guardó correctamente.');
})->name('certificacion.guardar');

// Solo Coordinador y Superadministrador
Route::middleware('rol:coordinador,superadministrador')->group(function () {
    Route::get('/zona', fn () => view('zona'))->name('zona');
});

Route::match(['get', 'post'], '/recuperar', fn () => view('recuperar'))->name('recuperar');
Route::redirect('/editarDatos', '/index')->name('editarDatos');
Route::redirect('/recContraseña', '/recuperar');