<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisión de documentos - ANFECA</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/zona.css') }}">
    <style>
        .badge { display:inline-block; padding:2px 10px; border-radius:12px; font-size:12px; font-weight:bold; }
        .badge-ok { background:#d9f2e0; color:#1a7f37; }
        .badge-falta { background:#fde8c8; color:#9a5b00; }
        .barra { background:#ddd; border-radius:6px; height:8px; width:90px; margin-top:4px; overflow:hidden; }
        .barra span { display:block; height:100%; background:#173459; }
        .btn-revisar { display:inline-block; padding:6px 14px; background:#173459; color:#fff; border-radius:4px; text-decoration:none; font-size:13px; }
        .btn-revisar:hover { background:#0f2540; }
        .vacio { text-align:center; padding:24px; color:#555; }
        .buscar-form { display:flex; gap:8px; align-items:center; }
    </style>
</head>
<body>
@include('partials.header')

<div class="zona-container">

    <!-- Barra superior -->
    <div class="nav-bar-superior">
        <div class="menu-botones-left">
            <a href="{{ route('zona') }}" class="btn-top">&larr; Zona</a>
            <a href="{{ route('revision.index') }}" class="btn-top">Documentos</a>
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
                <span class="sub-zona">Revisión</span>
                <h2 class="titulo-solicitudes">Documentos por solicitante</h2>
            </div>

            <div class="total-box-container">
                <label>Total</label>
                <input type="text" class="input-total" value="{{ count($solicitudes) }}" readonly>
            </div>
        </div>

        <!-- Buscador -->
        <form class="bar-buscar buscar-form" method="GET" action="{{ route('revision.index') }}">
            <label for="buscarInput">Buscar:</label>
            <input type="text" id="buscarInput" name="buscar" class="input-buscar"
                   value="{{ $buscar }}" placeholder="Nombre del solicitante">
            <button type="submit" class="btn-top">Buscar</button>
            @if ($buscar !== '')
                <a href="{{ route('revision.index') }}" class="btn-top">Limpiar</a>
            @endif
        </form>

        <!-- Tabla -->
        <div class="tabla-container">
            <table class="tabla-solicitudes">
                <thead>
                    <tr>
                        <th>Solicitante</th>
                        <th>Estatus de la solicitud</th>
                        <th>Documentos cargados</th>
                        <th>Última actualización</th>
                        <th>Revisar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($solicitudes as $s)
                        @php
                            $completo = $s->cargados >= $total;
                            $porcentaje = $total ? round(($s->cargados / $total) * 100) : 0;
                        @endphp
                        <tr>
                            <td class="bold">
                                {{ trim($s->pers_nombre.' '.$s->pers_apellido_paterno.' '.($s->pers_apellido_materno ?? '')) }}
                            </td>
                            <td>{{ $s->soan_estatus }}</td>
                            <td>
                                <span class="badge {{ $completo ? 'badge-ok' : 'badge-falta' }}">
                                    {{ $s->cargados }} de {{ $total }}
                                </span>
                                <div class="barra"><span style="width: {{ $porcentaje }}%"></span></div>
                            </td>
                            <td>{{ $s->soan_fecha_actualizacion }}</td>
                            <td>
                                <a class="btn-revisar" href="{{ route('revision.detalle', $s->soan_id_solicitud_anfeca) }}">
                                    Ver documentos
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="vacio">
                                {{ $buscar !== '' ? 'No se encontraron solicitantes con ese nombre.' : 'Todavía no hay solicitantes con documentos cargados.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('partials.footer')
</body>
</html>