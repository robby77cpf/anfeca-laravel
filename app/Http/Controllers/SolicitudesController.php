<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use Carbon\Carbon;
=======
use App\Services\ZonaUsuario;
>>>>>>> e608e7b20ffb3a298512afa71c74a0151f239c36
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use PhpOffice\PhpWord\TemplateProcessor;

class SolicitudesController extends Controller
{
    public function index()
    {
        $solicitudes = DB::table('solicitud_anfeca as s')
            ->join('persona as p', 'p.pers_id_persona', '=', 's.soan_id_persona')
            ->leftJoin('institucion as i', 'i.inst_id_institucion', '=', 'p.pers_id_institucion')
            ->leftJoin('zona as z', 'z.zona_id_zona', '=', 'i.inst_id_zona')
            ->leftJoin('area_certificacion as a', 'a.area_id_area_certificacion', '=', 's.soan_id_area_certificacion')
            ->leftJoin('articulo_certificacion as ar', 'ar.arce_id_articulo', '=', 's.soan_id_articulo')
            ->orderBy('s.soan_id_solicitud_anfeca')
            ->select(
                's.soan_id_solicitud_anfeca',
                's.soan_estatus',
                's.soan_folio',
                's.soan_evaluacion',
                's.soan_aprobado',
                's.soan_id_refrendo_certificacion',
                's.soan_id_articulo',
                's.soan_nombre_certificacion',
                'p.pers_nombre',
                'p.pers_apellido_paterno',
                'p.pers_apellido_materno',
                'a.area_descripcion',
                'i.inst_descripcion',
                'z.zona_id_zona',
                'z.zona_siglas',
                'ar.*'
            )
            ->get();

        // Coordinador de zona: solo ve las solicitudes de su zona (superadministrador ve todas)
        $zonaUsuario = ZonaUsuario::actual();
        if ($zonaUsuario) {
            $solicitudes = $solicitudes
                ->filter(fn ($s) => ZonaUsuario::numero($s->zona_siglas, $s->zona_id_zona) === $zonaUsuario)
                ->values();
        }

        $zonas = DB::table('zona')->orderBy('zona_id_zona')->get();

        return view('zona', ['solicitudes' => $solicitudes, 'zonas' => $zonas, 'zonaUsuario' => $zonaUsuario]);
    }

    /** Un coordinador solo puede tocar solicitudes de su zona. */
    private function autorizarZona(int $solicitud): void
    {
        $zonaUsuario = ZonaUsuario::actual();
        abort_if($zonaUsuario && ZonaUsuario::deSolicitud($solicitud) !== $zonaUsuario, 403);
    }

    public function guardar(Request $request, int $solicitud)
    {
        $this->autorizarZona($solicitud);

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
        $this->autorizarZona($solicitud);

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

    public function dictamen(int $solicitud)
    {
        $s = DB::table('solicitud_anfeca as s')
            ->join('persona as p', 'p.pers_id_persona', '=', 's.soan_id_persona')
            ->leftJoin('institucion as i', 'i.inst_id_institucion', '=', 'p.pers_id_institucion')
            ->leftJoin('zona as z', 'z.zona_id_zona', '=', 'i.inst_id_zona')
            ->leftJoin('area_certificacion as a', 'a.area_id_area_certificacion', '=', 's.soan_id_area_certificacion')
            ->where('s.soan_id_solicitud_anfeca', $solicitud)
            ->select(
                's.soan_id_solicitud_anfeca',
                's.soan_folio',
                's.soan_evaluacion',
                's.soan_aprobado',
                's.soan_nombre_certificacion',
                'p.pers_nombre',
                'p.pers_apellido_paterno',
                'p.pers_apellido_materno',
                'a.area_descripcion',
                'i.inst_descripcion',
                'z.zona_siglas'
            )
            ->first();

        abort_unless($s, 404);

        $nombre = trim($s->pers_nombre . ' ' . $s->pers_apellido_paterno . ' ' . ($s->pers_apellido_materno ?? ''));
        $certificacion = $s->area_descripcion ?? $s->soan_nombre_certificacion ?? '—';

        Carbon::setLocale('es');
        $fecha = Carbon::now();

        // 1. Cargar la plantilla Word y reemplazar marcadores
        $template = new TemplateProcessor(storage_path('app/plantillas/DICTAMEN_CERTIF_ACADE_PLANTILLA.docx'));
        $template->setValue('folio', $s->soan_folio ?: 'S/F');
        $template->setValue('nombre', $nombre);
        $template->setValue('institucion', $s->inst_descripcion ?? '—');
        $template->setValue('certificacion', $certificacion);
        $template->setValue('zona', $s->zona_siglas ?? '—');
        $template->setValue('resultado', $s->soan_aprobado ? 'APROBADA' : 'NO APROBADA');
        $template->setValue('evaluacion', $s->soan_evaluacion ?? '0');
        $template->setValue('dia', $fecha->day);
        $template->setValue('mes', $fecha->translatedFormat('F'));
        $template->setValue('anio', $fecha->year);
        $template->setValue('coordinador_regional', 'Coordinación Regional de Certificación Académica');

        // 2. Guardar Word temporal y convertir a PDF
        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $docxPath = "{$tempDir}/dictamen_{$solicitud}.docx";
        $pdfPath  = "{$tempDir}/dictamen_{$solicitud}.pdf";
        $template->saveAs($docxPath);

        exec(
            'libreoffice --headless --convert-to pdf '
            . escapeshellarg($docxPath)
            . ' --outdir '
            . escapeshellarg($tempDir)
        );

        @unlink($docxPath);

        abort_unless(file_exists($pdfPath), 500, 'No se pudo generar el PDF.');

        return response()->file($pdfPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Dictamen_' . ($s->soan_folio ?: $solicitud) . '.pdf"',
        ])->deleteFileAfterSend(true);
    }

}