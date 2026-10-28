<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolRequerido
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $idUsuario = $request->session()->get('usuario_id');

        if (!$idUsuario) {
            return redirect()->route('index');
        }

        $rol = DB::table('usuario')
            ->join('rol', 'rol.rol_id_rol', '=', 'usuario.usual_id_rol')
            ->where('usuario.usua_id_usuario', $idUsuario)
            ->value('rol.rol_nombre');

        $permitidos = array_map('strtolower', $roles);

        if (!$rol || !in_array(strtolower($rol), $permitidos, true)) {
            abort(403, 'No tienes permiso para acceder a esta pantalla.');
        }

        return $next($request);
    }
}