<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ZonaUsuario
{
    /** Zona (1 a 7) del usuario en sesión. null = sin restricción (superadministrador). */
    public static function actual(): ?int
    {
        $id = session('usuario_id');
        if (! $id) {
            return null;
        }

        $zona = DB::table('usuario')->where('usua_id_usuario', $id)->value('usua_zona');

        return $zona ? (int) $zona : null;
    }

    /** Número de zona a partir de las siglas (o del id si no traen número). */
    public static function numero($siglas, $idZona = null): int
    {
        return preg_match('/\d+/', (string) $siglas, $m) ? (int) $m[0] : (int) ($idZona ?? 0);
    }

    /** Número de zona de una solicitud. */
    public static function deSolicitud(int $solicitud): int
    {
        $z = DB::table('solicitud_anfeca as s')
            ->join('persona as p', 'p.pers_id_persona', '=', 's.soan_id_persona')
            ->leftJoin('institucion as i', 'i.inst_id_institucion', '=', 'p.pers_id_institucion')
            ->leftJoin('zona as z', 'z.zona_id_zona', '=', 'i.inst_id_zona')
            ->where('s.soan_id_solicitud_anfeca', $solicitud)
            ->first(['z.zona_id_zona', 'z.zona_siglas']);

        return $z ? self::numero($z->zona_siglas, $z->zona_id_zona) : 0;
    }
}
