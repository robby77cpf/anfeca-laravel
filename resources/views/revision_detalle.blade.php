<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisión del solicitante - ANFECA</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/zona.css') }}">
    <style>
    :root {
        --an-vino: #8c1d2c;
        --an-vino-osc: #6b1421;
        --an-rosa: #d8887f;
        --an-rosa-osc: #cf7a72;
        --an-rosa-claro: #fbeeed;
        --an-borde: #eadcda;
        --an-texto: #2b2b2b;
        --an-verde: #1fb141;
        --an-verde-osc: #178a32;
        --an-sombra: 0 8px 24px rgba(90, 20, 30, .14);
    }

    @keyframes an-aparece { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }

    .zona-container { max-width: 1480px; margin: 0 auto; padding: 0 20px 28px; }

    .nav-bar-superior { display: flex; justify-content: space-between; align-items: center;
                        flex-wrap: wrap; gap: 14px; padding: 22px 4px; }
    .menu-botones-left, .user-actions-right { display: flex; align-items: center; flex-wrap: wrap; gap: 12px; }

    .btn-top { display: inline-flex; align-items: center; gap: 8px; border: 0; cursor: pointer;
               text-decoration: none; color: #fff; font-weight: 600; font-size: 15px;
               padding: 11px 24px; border-radius: 999px;
               background: linear-gradient(135deg, var(--an-vino), var(--an-vino-osc));
               box-shadow: 0 4px 12px rgba(140, 29, 44, .28);
               transition: transform .15s ease, box-shadow .15s ease, filter .15s ease; }
    .btn-top:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(140, 29, 44, .35); filter: brightness(1.08); }
    .btn-top:active { transform: translateY(0); }

    .user-label { background: #fff; color: var(--an-texto); padding: 9px 18px; border-radius: 999px;
                  border: 1px solid #e6c9cb; box-shadow: 0 3px 10px rgba(90, 20, 30, .08); font-size: 14px; }

    .contenido-principal { background: linear-gradient(180deg, #dc938a 0%, var(--an-rosa) 45%, var(--an-rosa-osc) 100%);
                           border-radius: 22px; padding: 28px; box-shadow: var(--an-sombra);
                           animation: an-aparece .35s ease both; }

    .header-seccion { display: flex; justify-content: space-between; align-items: center;
                      flex-wrap: wrap; gap: 16px; margin-bottom: 18px; }
    .zona-titulo-container { display: flex; align-items: center; flex-wrap: wrap; gap: 14px; }
    .sub-zona { background: rgba(255, 255, 255, .22); color: #fff; font-weight: 700; font-size: 13px;
                padding: 5px 14px; border-radius: 999px; letter-spacing: .4px; }
    .titulo-solicitudes { margin: 0; color: #fff; font-size: 26px; font-weight: 700;
                          letter-spacing: .2px; text-shadow: 0 2px 6px rgba(90, 20, 30, .25); }

    .badge { display: inline-block; padding: 3px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; white-space: nowrap; }
    .badge-ok { background: #dcf5e3; color: #17763a; }
    .badge-falta { background: #fdebd0; color: #965600; }

    /* ---- Específico de Detalle ---- */
    .rev-grid { display: grid; grid-template-columns: 340px 1fr; gap: 18px; margin-top: 4px; }
    @media (max-width: 900px) { .rev-grid { grid-template-columns: 1fr; } }

    .panel { background: #fff; border-radius: 18px; padding: 18px; box-shadow: 0 10px 28px rgba(90, 20, 30, .18); }
    .panel h3 { margin: 0 0 14px; font-size: 16px; color: var(--an-vino); }

    .doc-fila { display: flex; justify-content: space-between; align-items: center; gap: 8px;
                padding: 12px 14px; margin-bottom: 8px; cursor: pointer; font-size: 14px;
                border: 1px solid var(--an-borde); border-radius: 12px; background: #fffaf9;
                transition: background .15s, border-color .15s, transform .15s; }
    .doc-fila:hover { background: var(--an-rosa-claro); transform: translateX(3px); }
    .doc-fila.activo { border-color: var(--an-vino); background: var(--an-rosa-claro); font-weight: 700;
                       box-shadow: inset 4px 0 0 var(--an-vino); }
    .doc-fila.sin-cargar { cursor: default; color: #a08e8c; background: #f6f1f0; }
    .doc-fila.sin-cargar:hover { transform: none; background: #f6f1f0; }

    .visor-barra { display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 12px; flex-wrap: wrap; }
    .visor-titulo { font-weight: 700; color: var(--an-vino); font-size: 16px; }
    .visor-botones { display: flex; gap: 8px; }
    .visor-botones button, .visor-botones a { padding: 8px 16px; border: 0; border-radius: 999px; cursor: pointer;
                          text-decoration: none; font-size: 13px; font-weight: 600; color: #fff;
                          background: linear-gradient(135deg, var(--an-vino), var(--an-vino-osc));
                          transition: transform .15s, filter .15s; }
    .visor-botones button:hover:not(:disabled), .visor-botones a:hover { transform: translateY(-1px); filter: brightness(1.1); }
    .visor-botones button:disabled { background: #cdbfbd; cursor: not-allowed; }

    .visor-marco { width: 100%; border: 1px solid var(--an-borde); border-radius: 14px; background: #f7f2f1; }
    .visor-vacio { display: none; align-items: center; justify-content: center; text-align: center; padding: 20px;
                   border: 2px dashed #dfc9c6; border-radius: 14px; color: #9a8583; }

    .meta { font-size: 13px; color: #fff; margin: 8px 0 0; opacity: .95; }
    .meta code { background: rgba(255, 255, 255, .25); padding: 2px 8px; border-radius: 6px; }
    .link-drive { display: block; margin-top: 14px; padding-top: 14px; border-top: 1px solid var(--an-borde); font-size: 13px; }
    .link-drive a { color: var(--an-vino); font-weight: 600; }

    /* ---- Corrección de overflow ---- */
    .zona-container { height: auto !important; min-height: 0 !important; overflow: visible !important; }
    .contenido-principal { height: auto !important; min-height: 0 !important; max-height: none !important;
                           overflow: visible !important; display: block; }
    .rev-grid { align-items: start; }
    .panel { min-width: 0; }
    .visor-marco { height: calc(100vh - 340px); min-height: 460px; display: block; }
    .visor-vacio { height: calc(100vh - 340px); min-height: 460px; }
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
            </div>
        </div>
        <p class="meta" style="margin:-8px 0 18px;">
            Estatus: <strong>{{ $s->soan_estatus }}</strong>
            · Documentos: <strong>{{ $cargados }} de {{ count($lista) }}</strong>
            @if ($carpeta) · Carpeta: <code>{{ $carpeta }}</code> @endif
        </p>

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