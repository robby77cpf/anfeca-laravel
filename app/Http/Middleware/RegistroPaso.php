<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistroPaso
{
    private const ORDEN = [
        'bienvenida'    => 0,
        'formacion'     => 1,
        'certificacion' => 2,
        'documentos'    => 3,
        'docsRefrendo'  => 4,
        'completo'      => 5,
    ];

    public function handle(Request $request, Closure $next, string $paso)
    {
        $usuarioId = $request->session()->get('usuario_id');

        if (! $usuarioId) {
            return redirect()->route('index');
        }

        // Solo se registra el avance al ENTRAR a la pantalla, y nunca se retrocede
        if ($request->isMethod('get')) {
            $actual = DB::table('usuario')
                ->where('usua_id_usuario', $usuarioId)
                ->value('usua_paso_registro') ?? 'bienvenida';

            if (($actual !== 'completo')
                && (self::ORDEN[$paso] ?? 0) > (self::ORDEN[$actual] ?? 0)) {
                DB::table('usuario')
                    ->where('usua_id_usuario', $usuarioId)
                    ->update(['usua_paso_registro' => $paso]);
            }
        }

        return $next($request);
    }
}