<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisión de documentos - ANFECA</title>
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
    .btn-top .arrow { font-size: 10px; opacity: .85; }

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

    .total-box-container { display: flex; flex-direction: column; align-items: center; gap: 4px; color: #fff; font-weight: 600; font-size: 13px; }
    .input-total { width: 84px; text-align: center; font-weight: 700; font-size: 16px; color: var(--an-vino);
                   border: 0; border-radius: 12px; padding: 8px; background: #fff;
                   box-shadow: inset 0 0 0 1px var(--an-borde); }

    .bar-buscar { display: flex; align-items: center; gap: 10px; margin-bottom: 18px; color: #fff; font-weight: 600; }
    .input-buscar { min-width: 260px; border: 0; border-radius: 999px; padding: 10px 18px; font-size: 14px;
                    background: #fff; color: var(--an-texto); outline: none;
                    box-shadow: 0 3px 10px rgba(90, 20, 30, .12); transition: box-shadow .15s; }
    .input-buscar:focus { box-shadow: 0 0 0 3px rgba(255, 255, 255, .55), 0 3px 10px rgba(90, 20, 30, .12); }

    .tabla-container { background: #fff; border-radius: 18px; overflow-x: auto;
                       box-shadow: 0 10px 28px rgba(90, 20, 30, .18); }
    .tabla-solicitudes { width: 100%; border-collapse: separate; border-spacing: 0; }
    .tabla-solicitudes thead th { position: sticky; top: 0; background: #fff; color: var(--an-vino);
                                  font-size: 11.5px; font-weight: 700; text-transform: uppercase;
                                  letter-spacing: .6px; padding: 16px 12px; text-align: center;
                                  border-bottom: 3px solid var(--an-rosa-claro); white-space: nowrap; }
    .tabla-solicitudes tbody td { padding: 14px 12px; text-align: center; font-size: 13.5px;
                                  color: var(--an-texto); border-bottom: 1px solid #f4e9e7;
                                  transition: background .15s; }
    .tabla-solicitudes tbody tr:nth-child(even) td { background: #fffaf9; }
    .tabla-solicitudes tbody tr:hover td { background: var(--an-rosa-claro); }
    .tabla-solicitudes tbody tr:last-child td { border-bottom: 0; }
    .tabla-solicitudes .bold { font-weight: 700; }

    .badge { display: inline-block; padding: 3px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; white-space: nowrap; }
    .badge-ok { background: #dcf5e3; color: #17763a; }
    .badge-falta { background: #fdebd0; color: #965600; }

    /* ---- Específico de Revisión ---- */
    .barra { background: #f0e1df; border-radius: 999px; height: 8px; width: 110px; margin: 6px auto 0; overflow: hidden; }
    .barra span { display: block; height: 100%; border-radius: 999px;
                  background: linear-gradient(90deg, var(--an-vino), #c4485a); }

    .btn-revisar { display: inline-block; padding: 8px 18px; color: #fff; text-decoration: none; font-size: 13px; font-weight: 600;
                   border-radius: 999px; background: linear-gradient(135deg, var(--an-vino), var(--an-vino-osc));
                   box-shadow: 0 3px 8px rgba(140, 29, 44, .25); transition: transform .15s, box-shadow .15s; }
    .btn-revisar:hover { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(140, 29, 44, .35); }

    .vacio { text-align: center; padding: 36px; color: #7a6462; }
    .buscar-form { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
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