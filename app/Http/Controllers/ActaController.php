<?php

namespace App\Http\Controllers;

use App\Services\ActaExcel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActaController extends Controller
{
    /**
     * Pantalla del Acta.
     */
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

    /**
     * Descarga el reporte (.xls) con las solicitudes registradas (no Borrador).
     * El No. de Certificado se asigna consecutivo por zona (1 a 7) entre las
     * solicitudes aprobadas. Para continuar un conteo anterior: /reporte?inicio=11
     */
    public function descargar(Request $request)
    {
        $inicio = max(1, (int) $request->query('inicio', 1));

        $solicitudes = DB::table('solicitud_anfeca as s')
            ->join('persona as p', 'p.pers_id_persona', '=', 's.soan_id_persona')
            ->leftJoin('institucion as i', 'i.inst_id_institucion', '=', 'p.pers_id_institucion')
            ->leftJoin('zona as z', 'z.zona_id_zona', '=', 'i.inst_id_zona')
            ->leftJoin('area_certificacion as a', 'a.area_id_area_certificacion', '=', 's.soan_id_area_certificacion')
            ->leftJoin('articulo_certificacion as ar', 'ar.arce_id_articulo', '=', 's.soan_id_articulo')
            ->leftJoin('refrendo_certificacion as r', 'r.rece_id_refrendo_certificacion', '=', 's.soan_id_refrendo_certificacion')
            ->leftJoin('documentacion_anfeca as d', 'd.doan_id_documentacion_anfeca', '=', 's.soan_id_documentacion_anfeca')
            ->whereRaw("COALESCE(s.soan_estatus, '') <> 'Borrador'")
            ->orderBy('s.soan_id_solicitud_anfeca')
            ->select(
                's.soan_id_solicitud_anfeca', 's.soan_folio', 's.soan_evaluacion', 's.soan_aprobado',
                's.soan_id_refrendo_certificacion', 's.soan_id_articulo', 's.soan_nombre_certificacion',
                'p.pers_id_persona', 'p.pers_nombre', 'p.pers_apellido_paterno', 'p.pers_apellido_materno',
                'p.pers_curp', 'p.pers_correo_electronico',
                'a.area_descripcion', 'i.inst_descripcion', 'z.zona_id_zona', 'z.zona_siglas',
                'ar.arce_subindice', 'r.rece_numero_refrendo', 'd.doan_url_documentos_probatorios'
            )
            ->get();

        $ids = $solicitudes->pluck('pers_id_persona')->unique()->all();

        // Teléfonos: primero particular, luego celular (orden de captura)
        $telefonos = DB::table('contacto')
            ->whereIn('cont_id_persona', $ids)
            ->orderByRaw('ctid')
            ->get(['cont_id_persona', 'cont_contacto'])
            ->groupBy('cont_id_persona');

        // Estudios por grado académico
        $estudios = DB::table('persona_carrera as pc')
            ->join('estudio_profesional as e', 'e.espr_id_estudio_profesional', '=', 'pc.peca_id_estudio_profesional')
            ->join('grado_academico as g', 'g.grac_id_grado_academico', '=', 'e.espr_id_grado_academico')
            ->whereIn('pc.peca_id_persona', $ids)
            ->get(['pc.peca_id_persona', 'g.grac_descripcion', 'e.espr_descripcion'])
            ->groupBy('peca_id_persona');

        $filas = [];
        foreach ($solicitudes as $s) {
            $zona = preg_match('/\d+/', (string) $s->zona_siglas, $m) ? (int) $m[0] : (int) ($s->zona_id_zona ?? 0);
            $aprobado = (bool) $s->soan_aprobado;
            $esRefrendo = $s->soan_id_refrendo_certificacion || $s->soan_id_articulo;

            $grados = ['lic' => [], 'mae' => [], 'doc' => []];
            foreach ($estudios[$s->pers_id_persona] ?? [] as $e) {
                $g = mb_strtolower((string) $e->grac_descripcion);
                $k = str_contains($g, 'licen') ? 'lic' : (str_contains($g, 'maest') ? 'mae' : (str_contains($g, 'doct') ? 'doc' : null));
                if ($k) {
                    $grados[$k][] = $e->espr_descripcion;
                }
            }

            $tel = $telefonos[$s->pers_id_persona] ?? collect();

            $filas[] = [
                'orden' => $s->soan_id_solicitud_anfeca,
                // Sin zona asignada no entra al conteo de certificados
                'concluido' => $aprobado && $zona > 0,
                'folio' => $s->soan_folio,
                'zona' => $zona ?: '',
                'nombre' => trim($s->pers_nombre.' '.$s->pers_apellido_paterno.' '.($s->pers_apellido_materno ?? '')),
                'curp' => $s->pers_curp,
                'no_afiliacion' => '',
                'institucion' => $s->inst_descripcion,
                'facultad' => '',
                'campus' => '',
                'certificacion' => $s->area_descripcion ?? $s->soan_nombre_certificacion,
                'estatus' => $esRefrendo ? 'Refrendo' : 'Primera vez',
                'no_refrendo' => $s->rece_numero_refrendo,
                'puntaje' => $s->soan_evaluacion,
                'art14' => $s->soan_id_articulo ? $s->arce_subindice : '',
                'dictamen' => $aprobado ? 'Aprobada' : ($s->soan_evaluacion !== null ? 'Rechazada' : ''),
                'licenciatura' => implode(' / ', $grados['lic']),
                'maestria' => implode(' / ', $grados['mae']),
                'doctorado' => implode(' / ', $grados['doc']),
                'tel_particular' => $tel[0]->cont_contacto ?? '',
                'tel_celular' => $tel[1]->cont_contacto ?? '',
                'correo' => $s->pers_correo_electronico,
                'url_docs' => $s->doan_url_documentos_probatorios,
                'tipo_certificacion' => 'ANFECA',
            ];
        }

        $nombre = 'Reporte_'.now()->format('dmY-His').'.xls';

        return response(ActaExcel::html($filas, $inicio), 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$nombre.'"',
        ]);
    }
}