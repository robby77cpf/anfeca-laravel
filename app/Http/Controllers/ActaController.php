<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ActaController extends Controller
{
    public function index()
    {
        $usuario = DB::table('usuario')->where('usua_nombre', session('usuario_nombre'))->first();

        // Número de zona de quien entró: el de su usuario (coordinadores)
        // o el de la zona de su institución (solicitantes)
        $numZona = $usuario->usua_zona ?? null;
        if (! $numZona && $usuario) {
            $siglas = DB::table('persona as p')
                ->join('institucion as i', 'i.inst_id_institucion', '=', 'p.pers_id_institucion')
                ->join('zona as z', 'z.zona_id_zona', '=', 'i.inst_id_zona')
                ->where('p.pers_id_usuario', $usuario->usua_id_usuario)
                ->value('z.zona_siglas');
            $numZona = $siglas !== null && preg_match('/\d+/', (string) $siglas, $m) ? (int) $m[0] : null;
        }

        // Superadministrador: coordinador sin zona (coordinador1)
        $esSuper = $usuario && (int) $usuario->usual_id_rol === 2 && empty($usuario->usua_zona);

        // Coordinador de cada zona: número de zona => nombre de usuario
        $coordinadores = DB::table('usuario')
            ->where('usual_id_rol', 2)
            ->whereNotNull('usua_zona')
            ->pluck('usua_nombre', 'usua_zona')
            ->all();

        $coordinador = $numZona ? ($coordinadores[$numZona] ?? '') : '';

        // Instituciones de esa zona (la tabla zona las identifica por sus siglas)
        $zonaId = $numZona
            ? DB::table('zona')->where('zona_siglas', (string) $numZona)->value('zona_id_zona')
            : null;

        $instituciones = DB::table('institucion')
            ->when($zonaId, fn ($q) => $q->where('inst_id_zona', $zonaId))
            ->orderBy('inst_descripcion')
            ->get(['inst_id_institucion', 'inst_descripcion']);

        // Si esa zona aún no tiene instituciones, se muestran todas
        if ($instituciones->isEmpty()) {
            $instituciones = DB::table('institucion')->orderBy('inst_descripcion')
                ->get(['inst_id_institucion', 'inst_descripcion']);
        }

        $estados = DB::table('estado')->orderBy('esta_nombre')->get(['esta_id_estado', 'esta_nombre']);

        return view('acta', [
            'coordinador'   => $coordinador,
            'coordinadores' => $coordinadores,
            'numZona'       => $numZona ?? '',
            'esSuper'       => $esSuper,
            'instituciones' => $instituciones,
            'estados'       => $estados,
        ]);
    }
}