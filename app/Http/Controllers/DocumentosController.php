<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentosController extends Controller
{
    // campo del formulario => [columna en documentacion_anfeca, etiqueta]
    private const ARCHIVOS = [
        'titulo_licenciatura'   => ['doan_url_titulo_licenciatura', 'Título de Licenciatura'],
        'constancia_antiguedad' => ['doan_url_constancia_antiguedad', 'Constancia de Antigüedad'],
        'cedula_licenciatura'   => ['doan_url_cedula_licenciatura', 'Cédula de Licenciatura'],
        'comprobante_pago'      => ['doan_url_comprobante_deposito', 'Comprobante de Pago'],
        'titulo_ultimo_grado'   => ['doan_url_titulo_posgrado', 'Título de último grado'],
        'cedula_ultimo_grado'   => ['doan_url_cedula_posgrado', 'Cédula de último grado'],
    ];

    /* ---------------- Solicitante ---------------- */

    public function mostrar(Request $request)
    {
        $persona = $this->personaActual($request);

        if (! $persona) {
            return to_route($request->session()->has('usuario_id') ? 'zona' : 'index');
        }

        $solicitud = $this->solicitudBorrador($persona->pers_id_persona);
        $docs = $solicitud
            ? DB::table('documentacion_anfeca')
                ->where('doan_id_documentacion_anfeca', $solicitud->soan_id_documentacion_anfeca)
                ->first()
            : null;

        return view('documentos', ['archivos' => self::ARCHIVOS, 'docs' => $docs]);
    }

    public function guardar(Request $request)
    {
        $persona = $this->personaActual($request);

        if (! $persona) {
            return to_route('index');
        }

        $reglas = ['drive_link' => ['nullable', 'url', 'max:255']];
        foreach (array_keys(self::ARCHIVOS) as $campo) {
            $reglas[$campo] = ['nullable', 'file', 'mimes:pdf', 'max:5120'];
        }

        $request->validate($reglas, [
            '*.mimes'   => 'Solo se permiten archivos PDF.',
            '*.max'     => 'Cada PDF debe pesar máximo 5 MB.',
            '*.uploaded' => 'No se pudo subir un archivo. Revisa que no pese más de 5 MB.',
            'drive_link.url' => 'El link no es válido (debe iniciar con https://).',
        ]);

        $hayArchivo = collect(array_keys(self::ARCHIVOS))->contains(fn ($c) => $request->hasFile($c));

        if (! $hayArchivo && ! $request->filled('drive_link')) {
            return back()->withErrors(['documentos' => 'Selecciona al menos un PDF o escribe el link.']);
        }

        $viejos = DB::transaction(function () use ($request, $persona) {
            $solicitud = $this->solicitudBorrador($persona->pers_id_persona)
                ?? $this->crearBorrador($persona->pers_id_persona);

            $docs = DB::table('documentacion_anfeca')
                ->where('doan_id_documentacion_anfeca', $solicitud->soan_id_documentacion_anfeca)
                ->first();

            $carpeta = $this->carpetaDe($docs, $persona);
            $cambios = [];
            $viejos = [];

            foreach (self::ARCHIVOS as $campo => [$columna]) {
                if (! $request->hasFile($campo)) {
                    continue;
                }

                $nombre = $campo.'_'.Str::random(8).'.pdf';
                $cambios[$columna] = $request->file($campo)->storeAs($carpeta, $nombre, 'local');

                if (! empty($docs->$columna)) {
                    $viejos[] = $docs->$columna;
                }
            }

            if ($request->filled('drive_link')) {
                $cambios['doan_url_documentos_probatorios'] = $request->input('drive_link');
            }

            if ($cambios) {
                DB::table('documentacion_anfeca')
                    ->where('doan_id_documentacion_anfeca', $docs->doan_id_documentacion_anfeca)
                    ->update($cambios);

                DB::table('solicitud_anfeca')
                    ->where('soan_id_solicitud_anfeca', $solicitud->soan_id_solicitud_anfeca)
                    ->update(['soan_fecha_actualizacion' => now()->toDateString()]);
            }

            return $viejos;
        });

        // Los archivos reemplazados se borran hasta que la base ya guardó los nuevos
        foreach ($viejos as $ruta) {
            Storage::disk('local')->delete($ruta);
        }

        return to_route('documentos')->with('status', 'Documentos guardados correctamente.');
    }

    // El solicitante abre sus propios PDF
    public function ver(Request $request, string $campo)
    {
        abort_unless(isset(self::ARCHIVOS[$campo]), 404);

        $persona = $this->personaActual($request);
        abort_unless($persona, 403);

        $solicitud = $this->solicitudBorrador($persona->pers_id_persona);
        abort_unless($solicitud, 404);

        return $this->responderPdf($solicitud->soan_id_documentacion_anfeca, $campo);
    }

    /* ---------------- Coordinador ---------------- */

    public function revisionLista(Request $request)
    {
        $buscar = trim((string) $request->query('buscar', ''));

        $consulta = DB::table('solicitud_anfeca as s')
            ->join('persona as p', 'p.pers_id_persona', '=', 's.soan_id_persona')
            ->join('documentacion_anfeca as d', 'd.doan_id_documentacion_anfeca', '=', 's.soan_id_documentacion_anfeca')
            ->select('s.soan_id_solicitud_anfeca', 's.soan_estatus', 's.soan_fecha_actualizacion',
                'p.pers_nombre', 'p.pers_apellido_paterno', 'p.pers_apellido_materno', 'd.*')
            ->orderBy('p.pers_apellido_paterno');

        if ($buscar !== '') {
            $consulta->whereRaw(
                "(p.pers_nombre || ' ' || p.pers_apellido_paterno || ' ' || coalesce(p.pers_apellido_materno, '')) ILIKE ?",
                ['%'.$buscar.'%']
            );
        }

        $solicitudes = $consulta->get()->map(function ($s) {
            $s->cargados = collect(self::ARCHIVOS)->filter(fn ($a) => ! empty($s->{$a[0]}))->count();

            return $s;
        });

        return view('revision', [
            'solicitudes' => $solicitudes,
            'buscar' => $buscar,
            'total' => count(self::ARCHIVOS),
        ]);
    }

    public function revisionDetalle($solicitud)
    {
        $s = DB::table('solicitud_anfeca as s')
            ->join('persona as p', 'p.pers_id_persona', '=', 's.soan_id_persona')
            ->join('documentacion_anfeca as d', 'd.doan_id_documentacion_anfeca', '=', 's.soan_id_documentacion_anfeca')
            ->where('s.soan_id_solicitud_anfeca', $solicitud)
            ->select('s.soan_id_solicitud_anfeca', 's.soan_estatus',
                'p.pers_nombre', 'p.pers_apellido_paterno', 'p.pers_apellido_materno', 'd.*')
            ->first();

        abort_unless($s, 404);

        $carpeta = null;
        foreach (self::ARCHIVOS as [$columna]) {
            if (! empty($s->$columna)) {
                $carpeta = dirname($s->$columna);
                break;
            }
        }

        return view('revision_detalle', [
            's' => $s,
            'archivos' => self::ARCHIVOS,
            'carpeta' => $carpeta,
        ]);
    }

    public function revisionVer($solicitud, string $campo)
    {
        abort_unless(isset(self::ARCHIVOS[$campo]), 404);

        $docId = DB::table('solicitud_anfeca')
            ->where('soan_id_solicitud_anfeca', $solicitud)
            ->value('soan_id_documentacion_anfeca');

        abort_unless($docId, 404);

        return $this->responderPdf($docId, $campo);
    }

    /* ---------------- Auxiliares ---------------- */

    private function personaActual(Request $request)
    {
        $usuarioId = $request->session()->get('usuario_id');

        if (! $usuarioId) {
            return null;
        }

        return DB::table('usuario')
            ->join('persona', 'persona.pers_id_persona', '=', 'usuario.usua_id_persona')
            ->where('usuario.usua_id_usuario', $usuarioId)
            ->select('persona.*')
            ->first();
    }

    private function solicitudBorrador($personaId)
    {
        return DB::table('solicitud_anfeca')
            ->where('soan_id_persona', $personaId)
            ->where('soan_estatus', 'Borrador')
            ->orderByDesc('soan_id_solicitud_anfeca')
            ->first();
    }

    private function crearBorrador($personaId)
    {
        $docId = DB::table('documentacion_anfeca')
            ->insertGetId(['doan_url_carta_solicitud_art' => null], 'doan_id_documentacion_anfeca');

        $id = DB::table('solicitud_anfeca')->insertGetId([
            'soan_id_documentacion_anfeca' => $docId,
            'soan_id_persona' => $personaId,
            'soan_estatus' => 'Borrador',
            'soan_fecha_solicitud' => now()->toDateString(),
            'soan_fecha_actualizacion' => now()->toDateString(),
        ], 'soan_id_solicitud_anfeca');

        return DB::table('solicitud_anfeca')->where('soan_id_solicitud_anfeca', $id)->first();
    }

    // Si el solicitante ya tiene archivos, se reutiliza su carpeta; si no, se crea una nueva
    private function carpetaDe($docs, $persona): string
    {
        foreach (self::ARCHIVOS as [$columna]) {
            if (! empty($docs->$columna)) {
                return dirname($docs->$columna);
            }
        }

        $nombre = trim($persona->pers_nombre.' '.$persona->pers_apellido_paterno.' '.($persona->pers_apellido_materno ?? ''));

        return 'documentos/'.$persona->pers_id_persona.'_'.Str::slug($nombre);
    }

    private function responderPdf($docId, string $campo)
    {
        $columna = self::ARCHIVOS[$campo][0];

        $ruta = DB::table('documentacion_anfeca')
            ->where('doan_id_documentacion_anfeca', $docId)
            ->value($columna);

        abort_unless($ruta && Storage::disk('local')->exists($ruta), 404);

        return Storage::disk('local')->response($ruta);
    }
}