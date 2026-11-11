<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisión del solicitante - ANFECA</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/zona.css') }}">
    <style>
        .rev-grid { display:grid; grid-template-columns: 320px 1fr; gap:16px; margin-top:12px; }
        @media (max-width: 900px) { .rev-grid { grid-template-columns: 1fr; } }

        .panel { background:#fff; border:1px solid #ccc; border-radius:6px; padding:12px; }
        .panel h3 { margin:0 0 10px; font-size:15px; color:#173459; }

        .doc-fila { display:flex; justify-content:space-between; align-items:center; gap:8px;
                    padding:9px 10px; border:1px solid #ddd; border-radius:5px; margin-bottom:6px;
                    cursor:pointer; background:#fafafa; }
        .doc-fila:hover { background:#eef3fa; }
        .doc-fila.activo { border-color:#173459; background:#e3ecf8; font-weight:bold; }
        .doc-fila.sin-cargar { cursor:default; color:#999; background:#f3f3f3; }

        .badge { font-size:11px; padding:2px 8px; border-radius:10px; white-space:nowrap; }
        .badge-ok { background:#d9f2e0; color:#1a7f37; }
        .badge-falta { background:#fde8c8; color:#9a5b00; }

        .visor-barra { display:flex; justify-content:space-between; align-items:center; gap:8px; margin-bottom:8px; flex-wrap:wrap; }
        .visor-titulo { font-weight:bold; color:#173459; }
        .visor-botones { display:flex; gap:6px; }
        .visor-botones button, .visor-botones a { padding:6px 12px; background:#173459; color:#fff; border:0;
                    border-radius:4px; cursor:pointer; text-decoration:none; font-size:13px; }
        .visor-botones button:disabled { background:#aaa; cursor:not-allowed; }

        .visor-marco { width:100%; height:72vh; border:1px solid #bbb; border-radius:4px; background:#f5f5f5; }
        .visor-vacio { display:none; height:72vh; align-items:center; justify-content:center;
                       border:1px dashed #bbb; border-radius:4px; color:#888; text-align:center; padding:20px; }

        .meta { font-size:13px; color:#555; margin:6px 0 0; }
        .link-drive { display:block; margin-top:12px; padding-top:10px; border-top:1px solid #ddd; font-size:13px; }
    </style>
</head>
<body>
@include('partials.header')

@php
    $nombre = trim($s->pers_nombre.' '.$s->pers_apellido_paterno.' '.($s->pers_apellido_materno ?? ''));

    $lista = [];
    foreach ($archivos as $campo => $info) {
        $lista[] = [
            'titulo'  => $info[1],
            'cargado' => ! empty($s->{$info[0]}),
            'url'     => route('revision.ver', [$s->soan_id_solicitud_anfeca, $campo]),
        ];
    }
    $cargados = collect($lista)->where('cargado', true)->count();
@endphp

<div class="zona-container">

    <!-- Barra superior -->
    <div class="nav-bar-superior">
        <div class="menu-botones-left">
            <a href="{{ route('zona') }}" class="btn-top">Zona</a>
            <a href="{{ route('revision.index') }}" class="btn-top">&larr; Lista de solicitantes</a>
        </div>

        <div class="user-actions-right">
            <a href="{{ route('logout') }}" class="btn-top btn-cerrar-sesion">Cerrar Sesión</a>
            <span class="user-label">Usuario: <strong>{{ session('usuario_nombre') }}</strong></span>
        </div>
    </div>

    <div class="contenido-principal">

        <!-- Encabezado -->
        <div class="header-seccion">
            <div class="zona-titulo-container">
                <span class="sub-zona">Solicitante</span>
                <h2 class="titulo-solicitudes">{{ $nombre }}</h2>
                <p class="meta">
                    Estatus: <strong>{{ $s->soan_estatus }}</strong>
                    · Documentos: <strong>{{ $cargados }} de {{ count($lista) }}</strong>
                    @if ($carpeta) · Carpeta: <code>{{ $carpeta }}</code> @endif
                </p>
            </div>
        </div>

        <div class="rev-grid">

            <!-- Columna izquierda: lista de documentos -->
            <div class="panel">
                <h3>Documentos</h3>

                @foreach ($lista as $i => $d)
                    <div class="doc-fila {{ $d['cargado'] ? '' : 'sin-cargar' }}" data-i="{{ $i }}">
                        <span>{{ $d['titulo'] }}</span>
                        <span class="badge {{ $d['cargado'] ? 'badge-ok' : 'badge-falta' }}">
                            {{ $d['cargado'] ? 'Cargado' : 'Falta' }}
                        </span>
                    </div>
                @endforeach

                <div class="link-drive">
                    <strong>Documentos probatorios (link):</strong><br>
                    @if (! empty($s->doan_url_documentos_probatorios))
                        <a href="{{ $s->doan_url_documentos_probatorios }}" target="_blank" rel="noopener noreferrer">
                            Abrir enlace de Drive
                        </a>
                    @else
                        <span style="color:#9a5b00;">Sin cargar</span>
                    @endif
                </div>
            </div>

            <!-- Columna derecha: visor -->
            <div class="panel">
                <div class="visor-barra">
                    <span class="visor-titulo" id="visorTitulo">Selecciona un documento</span>
                    <div class="visor-botones">
                        <button type="button" id="btnAnterior">&larr; Anterior</button>
                        <button type="button" id="btnSiguiente">Siguiente &rarr;</button>
                        <a href="#" id="btnNuevaPestana" target="_blank" rel="noopener">Abrir en pestaña</a>
                    </div>
                </div>

                <iframe id="visor" class="visor-marco" title="Visor de PDF"></iframe>
                <div id="visorVacio" class="visor-vacio">
                    Este documento todavía no ha sido cargado por el solicitante.
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.footer')

<script>
    const docs = @json($lista);
    const filas = document.querySelectorAll('.doc-fila');
    const visor = document.getElementById('visor');
    const vacio = document.getElementById('visorVacio');
    const titulo = document.getElementById('visorTitulo');
    const btnAnt = document.getElementById('btnAnterior');
    const btnSig = document.getElementById('btnSiguiente');
    const btnTab = document.getElementById('btnNuevaPestana');
    let actual = -1;

    // Índices de los documentos que sí están cargados
    const cargados = docs.map((d, i) => d.cargado ? i : -1).filter(i => i >= 0);

    function mostrar(i) {
        if (!docs[i] || !docs[i].cargado) return;
        actual = i;

        filas.forEach(f => f.classList.toggle('activo', Number(f.dataset.i) === i));
        titulo.textContent = docs[i].titulo;
        visor.style.display = 'block';
        vacio.style.display = 'none';
        visor.src = docs[i].url;
        btnTab.href = docs[i].url;

        const pos = cargados.indexOf(i);
        btnAnt.disabled = pos <= 0;
        btnSig.disabled = pos >= cargados.length - 1;
    }

    filas.forEach(f => f.addEventListener('click', () => mostrar(Number(f.dataset.i))));
    btnAnt.addEventListener('click', () => mostrar(cargados[cargados.indexOf(actual) - 1]));
    btnSig.addEventListener('click', () => mostrar(cargados[cargados.indexOf(actual) + 1]));

    if (cargados.length) {
        mostrar(cargados[0]);
    } else {
        visor.style.display = 'none';
        vacio.style.display = 'flex';
        vacio.textContent = 'Este solicitante todavía no ha cargado documentos PDF.';
        btnAnt.disabled = true;
        btnSig.disabled = true;
        btnTab.style.display = 'none';
    }
</script>
</body>
</html>