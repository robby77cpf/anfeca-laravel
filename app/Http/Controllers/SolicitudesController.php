<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SolicitudesController extends Controller
{
    public function index()
    {
        $solicitudes = DB::table('solicitud_anfeca as s')
            ->join('persona as p', 'p.pers_id_persona', '=', 's.soan_id_persona')
            ->leftJoin('institucion as i', 'i.inst_id_institucion', '=', 'p.pers_id_institucion')
            ->leftJoin('zona as z', 'z.zona_id_zona', '=', 'i.inst_id_zona')
            ->leftJoin('area_certificacion as a', 'a.area_id_area_certificacion', '=', 's.soan_id_area_certificacion')
            ->where('s.soan_estatus', '<>', 'Borrador')
            ->orderBy('s.soan_id_solicitud_anfeca')
            ->select(
                's.soan_id_solicitud_anfeca',
                's.soan_folio',
                's.soan_evaluacion',
                's.soan_aprobado',
                's.soan_id_refrendo_certificacion',
                's.soan_nombre_certificacion',
                'p.pers_nombre',
                'p.pers_apellido_paterno',
                'p.pers_apellido_materno',
                'a.area_descripcion',
                'i.inst_descripcion',
                'z.zona_siglas'
            )
            ->get();

        return view('zona', ['solicitudes' => $solicitudes]);
    }

    public function guardar(Request $request, int $solicitud)
    {
        abort_unless(
            DB::table('solicitud_anfeca')->where('soan_id_solicitud_anfeca', $solicitud)->exists(),
            404
        );

        $datos = $request->validate([
            'folio' => [
                'nullable', 'string', 'max:20',
                Rule::unique('solicitud_anfeca', 'soan_folio')->ignore($solicitud, 'soan_id_solicitud_anfeca'),
            ],
            'evaluacion' => ['nullable', 'integer', 'min:0', 'max:99999'],
            'aprobado' => ['nullable', 'boolean'],
        ], [
            'folio.unique' => 'Ese folio ya está asignado a otra solicitud.',
            'evaluacion.integer' => 'La evaluación debe ser un número entero.',
        ]);

        $evaluacion = $datos['evaluacion'] ?? null;
        $aprobado = (bool) ($datos['aprobado'] ?? false);

        // Regla de negocio: 2000 o más aprueba automáticamente
        if ($evaluacion !== null && $evaluacion >= 2000) {
            $aprobado = true;
        }

        DB::table('solicitud_anfeca')
            ->where('soan_id_solicitud_anfeca', $solicitud)
            ->update([
                'soan_folio' => $datos['folio'] ?? null,
                'soan_evaluacion' => $evaluacion,
                'soan_aprobado' => $aprobado,
                'soan_fecha_actualizacion' => now()->toDateString(),
            ]);

        return response()->json(['ok' => true, 'aprobado' => $aprobado]);
    }

    public function persona(int $solicitud)
    {
        $p = DB::table('solicitud_anfeca as s')
            ->join('persona as p', 'p.pers_id_persona', '=', 's.soan_id_persona')
            ->leftJoin('domicilio as d', 'd.domi_id_domicilio', '=', 'p.pers_id_domicilio')
            ->leftJoin('colonia as c', 'c.colo_id_colonia', '=', 'd.domi_id_colonia')
            ->leftJoin('alcaldia as al', 'al.alca_id_alcaldia', '=', 'd.domi_id_alcaldia')
            ->leftJoin('estado as e', 'e.esta_id_estado', '=', 'd.domi_id_estado')
            ->where('s.soan_id_solicitud_anfeca', $solicitud)
            ->select(
                'p.pers_id_persona', 'p.pers_nombre', 'p.pers_apellido_paterno', 'p.pers_apellido_materno',
                'p.pers_curp', 'p.pers_rfc', 'p.pers_cedula_profesional', 'p.pers_correo_electronico',
                'd.domi_calle', 'd.domi_numero_exterior', 'd.domi_numero_interior', 'd.domi_codigo_postal',
                'c.colo_nombre', 'al.alca_nombre', 'e.esta_nombre'
            )
            ->first();

        abort_unless($p, 404);

        // Se guardaron en orden: primero particular, luego celular
        $tel = DB::table('contacto')
            ->where('cont_id_persona', $p->pers_id_persona)
            ->orderByRaw('ctid')
            ->pluck('cont_contacto');

        return response()->json([
            'Nombre(s)' => $p->pers_nombre,
            'Apellido paterno' => $p->pers_apellido_paterno,
            'Apellido materno' => $p->pers_apellido_materno,
            'CURP' => $p->pers_curp,
            'RFC' => $p->pers_rfc,
            'Cédula profesional' => $p->pers_cedula_profesional,
            'Correo electrónico' => $p->pers_correo_electronico,
            'Teléfono particular' => $tel[0] ?? null,
            'Teléfono celular' => $tel[1] ?? null,
            'Calle' => $p->domi_calle,
            'Número exterior' => $p->domi_numero_exterior,
            'Número interior' => $p->domi_numero_interior,
            'Colonia' => $p->colo_nombre,
            'Alcaldía / Municipio' => $p->alca_nombre,
            'Entidad federativa' => $p->esta_nombre,
            'Código postal' => $p->domi_codigo_postal,
        ]);
    }
}