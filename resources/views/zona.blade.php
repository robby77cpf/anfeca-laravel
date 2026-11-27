@include('partials.header')

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

.menu-desplegable { background: #fff; border-radius: 14px; overflow: hidden;
                    box-shadow: var(--an-sombra); border: 1px solid var(--an-borde); }
.menu-desplegable a { display: block; padding: 11px 18px; color: var(--an-vino);
                      text-decoration: none; font-weight: 500; transition: background .15s; }
.menu-desplegable a:hover { background: var(--an-rosa-claro); }

.btn-user-profile { display: inline-flex; align-items: center; gap: 10px; text-decoration: none;
                    padding: 6px 16px 6px 8px; border-radius: 999px; background: #fff;
                    border: 1px solid #e6c9cb; color: var(--an-texto);
                    box-shadow: 0 3px 10px rgba(90, 20, 30, .08); transition: box-shadow .15s, transform .15s; }
.btn-user-profile:hover { box-shadow: 0 8px 18px rgba(90, 20, 30, .16); transform: translateY(-1px); }

.contenido-principal { background: linear-gradient(180deg, #dc938a 0%, var(--an-rosa) 45%, var(--an-rosa-osc) 100%);
                       border-radius: 22px; padding: 28px; box-shadow: var(--an-sombra);
                       animation: an-aparece .35s ease both; }

.header-seccion { display: flex; justify-content: space-between; align-items: center;
                  flex-wrap: wrap; gap: 16px; margin-bottom: 18px; }
.zona-titulo-container { display: flex; align-items: center; flex-wrap: wrap; gap: 14px; }
.sub-zona { background: rgba(255, 255, 255, .22); color: #fff; font-weight: 700; font-size: 13px;
            padding: 5px 14px; border-radius: 999px; letter-spacing: .4px; }
.titulo-solicitudes { margin: 0; color: #fff; font-size: 20px; font-weight: 700;
                      letter-spacing: .2px; text-shadow: 0 2px 6px rgba(90, 20, 30, .25); }

.btn-guardar { border: 0; cursor: pointer; color: #fff; font-weight: 700; font-size: 16px;
               padding: 13px 44px; border-radius: 999px;
               background: linear-gradient(135deg, var(--an-verde), var(--an-verde-osc));
               box-shadow: 0 6px 16px rgba(23, 138, 50, .35);
               transition: transform .15s, box-shadow .15s, filter .15s; }
.btn-guardar:hover { transform: translateY(-2px); box-shadow: 0 10px 22px rgba(23, 138, 50, .42); filter: brightness(1.06); }

.total-box-container { display: flex; flex-direction: column; align-items: center; gap: 4px; color: #fff; font-weight: 600; font-size: 13px; }
.input-total { width: 84px; text-align: center; font-weight: 700; font-size: 16px; color: var(--an-vino);
               border: 0; border-radius: 12px; padding: 8px; background: #fff;
               box-shadow: inset 0 0 0 1px var(--an-borde); }

.bar-buscar { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; color: #fff; font-weight: 600; }
.input-buscar { min-width: 260px; border: 0; border-radius: 999px; padding: 10px 18px; font-size: 14px;
                background: #fff; color: var(--an-texto); outline: none;
                box-shadow: 0 3px 10px rgba(90, 20, 30, .12); transition: box-shadow .15s; }
.input-buscar:focus { box-shadow: 0 0 0 3px rgba(255, 255, 255, .55), 0 3px 10px rgba(90, 20, 30, .12); }

/* ---- Filtros ---- */
.filtros-bar { display: flex; align-items: flex-end; flex-wrap: wrap; gap: 14px; margin-bottom: 18px;
               background: rgba(255, 255, 255, .18); border-radius: 16px; padding: 12px 16px; }
.filtro { display: flex; flex-direction: column; gap: 5px; color: #fff; font-weight: 600; font-size: 12px;
          text-transform: uppercase; letter-spacing: .5px; }
.filtro select { min-width: 170px; border: 0; border-radius: 999px; padding: 9px 16px; font-size: 14px;
                 color: var(--an-texto); background: #fff; outline: none; cursor: pointer; text-transform: none;
                 letter-spacing: 0; box-shadow: 0 3px 10px rgba(90, 20, 30, .12); transition: box-shadow .15s; }
.filtro select:focus { box-shadow: 0 0 0 3px rgba(255, 255, 255, .55), 0 3px 10px rgba(90, 20, 30, .12); }
.btn-limpiar { border: 0; cursor: pointer; padding: 10px 20px; border-radius: 999px; font-weight: 600; font-size: 13px;
               color: var(--an-vino); background: #fff; box-shadow: 0 3px 10px rgba(90, 20, 30, .12);
               transition: transform .15s, background .15s; }
.btn-limpiar:hover { background: var(--an-rosa-claro); transform: translateY(-1px); }

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

.input-evaluacion { width: 92px; text-align: center; padding: 8px 10px; font-size: 14px;
                    border: 1px solid var(--an-borde); border-radius: 10px; background: #fff;
                    outline: none; transition: border-color .15s, box-shadow .15s; }
.input-evaluacion:focus { border-color: var(--an-vino); box-shadow: 0 0 0 3px rgba(140, 29, 44, .15); }

.switch input:checked + .slider { background: var(--an-verde); }

.icon-doc-pdf, .btn-action-icon { display: inline-flex; align-items: center; justify-content: center;
                                  width: 38px; height: 38px; border-radius: 50%; border: 0;
                                  background: transparent; cursor: pointer; text-decoration: none;
                                  transition: background .15s, transform .15s; }
.icon-doc-pdf:hover, .btn-action-icon:hover { background: var(--an-rosa-claro); transform: scale(1.1); }

.badge { display: inline-block; padding: 3px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; white-space: nowrap; }
.badge-ok { background: #dcf5e3; color: #17763a; }
.badge-falta { background: #fdebd0; color: #965600; }

/* ---- Específico de Zona ---- */
.input-folio { width: 120px; }
.link-docs { color: var(--an-vino); font-weight: 700; text-decoration: none;
             padding: 6px 12px; border-radius: 999px; background: var(--an-rosa-claro);
             transition: background .15s, color .15s; }
.link-docs:hover { background: var(--an-vino); color: #fff; }

.toast { position: fixed; bottom: 24px; right: 24px; padding: 12px 20px; border-radius: 12px; color: #fff;
         font-size: 14px; font-weight: 600; display: none; z-index: 10001; box-shadow: var(--an-sombra); }
.toast.ok { background: var(--an-verde-osc); }
.toast.error { background: #b00020; }

.modal-fondo { position: fixed; inset: 0; background: rgba(40, 10, 15, .55); backdrop-filter: blur(3px);
               display: none; align-items: center; justify-content: center; z-index: 10000; }
.modal-fondo.abierto { display: flex; }
.modal-caja { background: #fff; border-radius: 20px; width: min(660px, 94vw); max-height: 86vh;
              overflow: auto; padding: 26px; box-shadow: 0 20px 50px rgba(0, 0, 0, .35);
              animation: an-aparece .25s ease both; }
.modal-caja h3 { margin: 0 0 16px; color: var(--an-vino); font-size: 20px; }
.modal-tabla { width: 100%; border-collapse: separate; border-spacing: 0; border: 1px solid var(--an-borde);
               border-radius: 12px; overflow: hidden; }
.modal-tabla th { text-align: left; width: 40%; background: var(--an-rosa-claro); color: var(--an-vino); font-weight: 600; }
.modal-tabla th, .modal-tabla td { padding: 9px 14px; font-size: 14px; border-bottom: 1px solid #f1e4e2; }
.modal-tabla tr:last-child th, .modal-tabla tr:last-child td { border-bottom: 0; }
.modal-cerrar { margin-top: 18px; padding: 10px 26px; border: 0; border-radius: 999px; cursor: pointer;
                color: #fff; font-weight: 600; background: linear-gradient(135deg, var(--an-vino), var(--an-vino-osc));
                transition: filter .15s, transform .15s; }
.modal-cerrar:hover { filter: brightness(1.1); transform: translateY(-1px); }

/* ---- Corrección de columnas apretadas ---- */
.link-docs { display: inline-block; white-space: nowrap; font-size: 12.5px; padding: 6px 14px; }

.tabla-solicitudes { min-width: 1250px; table-layout: auto; }
.tabla-solicitudes thead th { white-space: nowrap; padding: 16px 10px; }
.tabla-solicitudes tbody td { padding: 14px 10px; }

.tabla-solicitudes th:nth-child(4), .tabla-solicitudes td:nth-child(4) { min-width: 190px; }
.tabla-solicitudes td:nth-child(2) { padding-left: 16px; }

.input-folio { width: 100px; }
.input-evaluacion { width: 80px; }

/* ---- Corrección de capas al hacer scroll ---- */
.tabla-container { isolation: isolate; }

.tabla-solicitudes thead { position: relative; z-index: 30; }
.tabla-solicitudes thead th { position: sticky; top: 0; z-index: 30; background: #fff; }

.tabla-solicitudes tbody { position: relative; z-index: 1; }
.tabla-solicitudes tbody td { position: relative; z-index: 1; }
.tabla-solicitudes tbody .switch,
.tabla-solicitudes tbody .switch .slider { z-index: 0; }

.tabla-solicitudes tbody .icon-doc-pdf:hover,
.tabla-solicitudes tbody .btn-action-icon:hover { position: relative; z-index: 0; }

.menu-dictamen-flotante {
    position: fixed;
    display: none;
    min-width: 310px;
    background: #fff;
    border-radius: 14px;
    border: 1px solid var(--an-borde);
    box-shadow: 0 12px 30px rgba(90, 20, 30, .22);
    z-index: 10002;
    overflow: hidden;
    text-align: left;
}
.menu-dictamen-flotante.show { display: block; }
.menu-dictamen-flotante a,
.menu-dictamen-flotante span {
    display: block;
    padding: 10px 16px;
    font-size: 13px;
    border-bottom: 1px solid #f4e9e7;
    text-decoration: none;
}
.menu-dictamen-flotante a {
    color: var(--an-vino);
    font-weight: 600;
    transition: background .15s;
}
.menu-dictamen-flotante a:hover { background: var(--an-rosa-claro); }
.menu-dictamen-flotante span.pendiente {
    color: #9e8c8a;
    cursor: not-allowed;
    background: #fafafa;
}
.menu-dictamen-flotante > *:last-child { border-bottom: 0; }
</style>

<div class="zona-container">
    <!-- Barra Superior de Navegación y Acciones -->
    <div class="nav-bar-superior">
        <div class="menu-botones-left">
            <div class="dropdown-zona" id="dropdownZona" @if($zonaUsuario ?? null) style="display:none" @endif>
                <button type="button" class="btn-top" onclick="toggleMenu('menuZona')">
                    Zona <span class="arrow">&#9660;</span>
                </button>
                <div class="menu-desplegable" id="menuZona">
                    <a href="javascript:void(0)" onclick="seleccionarZona('')">Todas las zonas</a>
                    @for ($n = 1; $n <= 7; $n++)
                        <a href="javascript:void(0)" onclick="seleccionarZona('{{ $n }}')">Zona {{ $n }}</a>
                    @endfor
                </div>
            </div>

            <a href="{{ route('acta') }}" class="btn-top">Reportes</a>

            <a href="{{ route('revision.index') }}" class="btn-top">Documentos</a>


            <a href="{{ route('acta') }}" class="btn-top">Acta</a>
        </div>

        <div class="user-actions-right">
            <a href="{{ route('logout') }}" class="btn-top btn-cerrar-sesion">Cerrar Sesión</a>

            <a href="{{ route('editarDatos') }}" class="btn-user-profile" title="Editar Datos del Usuario">
                <div class="circle-icon">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9"></path>
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                    </svg>
                </div>
                <div class="user-icon-avatar">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="#666">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <span class="user-label">Usuario: <strong>{{ session('usuario_nombre') }}</strong></span>
            </a>
        </div>
    </div>

    <!-- Área Principal -->
    <div class="contenido-principal">

        <div class="header-seccion">
            <div class="zona-titulo-container">
                <span class="sub-zona" id="subZona">{{ ($zonaUsuario ?? null) ? "Zona ".$zonaUsuario : "Todas las zonas" }}</span>
                <h2 class="titulo-solicitudes" id="tituloSolicitudes">Solicitudes Completadas</h2>
            </div>

            <button type="button" class="btn-guardar" id="btnGuardarTodo">Guardar</button>

            <div class="total-box-container">
                <label>Total</label>
                <input type="text" class="input-total" id="inputTotal" value="{{ count($solicitudes) }}" readonly>
            </div>
        </div>

        <div class="bar-buscar">
            <label for="buscarInput">Buscar:</label>
            <input type="text" id="buscarInput" class="input-buscar">
        </div>

        <!-- Filtros -->
        <div class="filtros-bar">
            <div class="filtro">
                <label for="filtroProceso">Proceso</label>
                <select id="filtroProceso">
                    <option value="completo" selected>Completo</option>
                    <option value="incompleto">Incompleto</option>
                    <option value="">Todos</option>
                </select>
            </div>

            <div class="filtro" @if($zonaUsuario ?? null) style="display:none" @endif>
                <label for="filtroZona">Zona</label>
                <select id="filtroZona">
                    <option value="">Todas las zonas</option>
                    @for ($n = 1; $n <= 7; $n++)
                        <option value="{{ $n }}">Zona {{ $n }}</option>
                    @endfor
                </select>
            </div>

            <div class="filtro">
                <label for="filtroTipo">Tipo de certificación</label>
                <select id="filtroTipo">
                    <option value="">Todas</option>
                    <option value="primera">Primera Vez</option>
                    <option value="refrendo">Refrendo</option>
                    <option value="refrendo14">Refrendo por Art. 14</option>
                    <option value="refrendo13">Refrendo por Art. 13</option>
                </select>
            </div>

            <button type="button" class="btn-limpiar" id="btnLimpiar">Limpiar filtros</button>
        </div>

        <div class="tabla-container">
            <table class="tabla-solicitudes">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Zona</th>
                        <th>Institución</th>
                        <th>Nombre del Concursante</th>
                        <th>Estatus</th>
                        <th>Proceso</th>
                        <th>Certificación</th>
                        <th>Documentos</th>
                        <th>Evaluación</th>
                        <th>Aprobado</th>
                        <th>Dictamen</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody id="tbodySolicitudes">
                    @foreach ($solicitudes as $s)
                        @php
                            $nombre = trim($s->pers_nombre.' '.$s->pers_apellido_paterno.' '.($s->pers_apellido_materno ?? ''));

                            // Número de zona (1 a 7): se toma de las siglas; si no traen número, del id de la zona
                            $numZona = preg_match('/\d+/', (string) $s->zona_siglas, $m) ? (int) $m[0] : ($s->zona_id_zona ?? '');

                            // Texto de la tabla de artículos (columnas arce_*) para detectar Art. 13 / 14
                            $textoArt = '';
                            foreach ((array) $s as $k => $v) {
                                if (str_starts_with($k, 'arce_') && is_string($v)) {
                                    $textoArt .= ' '.$v;
                                }
                            }
                            $articulo = null;
                            if ($s->soan_id_articulo) {
                                if (preg_match('/\b14\b/', $textoArt)) { $articulo = 14; }
                                elseif (preg_match('/\b13\b/', $textoArt)) { $articulo = 13; }
                            }

                            $esRefrendo = (bool) $s->soan_id_refrendo_certificacion || $articulo !== null;
                            $tipo = $articulo ? 'Refrendo por Art. '.$articulo : ($esRefrendo ? 'Refrendo' : 'Primera Vez');

                            // Etiquetas para el filtro (una solicitud puede tener varias)
                            $etiquetasTipo = $esRefrendo ? 'refrendo' : 'primera';
                            if ($articulo) { $etiquetasTipo .= ' refrendo'.$articulo; }

                            $completo = $s->soan_estatus !== 'Borrador';
                            $certificacion = $s->area_descripcion ?? $s->soan_nombre_certificacion ?? '—';
                        @endphp
                        <tr data-id="{{ $s->soan_id_solicitud_anfeca }}"
                            data-proceso="{{ $completo ? 'completo' : 'incompleto' }}"
                            data-zona="{{ $numZona }}"
                            data-tipo="{{ $etiquetasTipo }}">
                            <td>
                                <input type="text" class="input-evaluacion input-folio campo-folio"
                                       maxlength="20" value="{{ $s->soan_folio }}" placeholder="Folio">
                            </td>
                            <td>{{ $s->zona_siglas ?? '—' }}</td>
                            <td>{{ $s->inst_descripcion ?? '—' }}</td>
                            <td>{{ $nombre }}</td>
                            <td>{{ $tipo }}</td>
                            <td>
                                <span class="badge {{ $completo ? 'badge-ok' : 'badge-falta' }}">
                                    {{ $completo ? 'Completo' : 'Incompleto' }}
                                </span>
                            </td>
                            <td>{{ $certificacion }}</td>
                            <td>
                                <a class="link-docs" href="{{ route('revision.detalle', $s->soan_id_solicitud_anfeca) }}">
                                    Documentos
                                </a>
                            </td>
                            <td>
                                <input type="number" min="0" max="99999" class="input-evaluacion campo-evaluacion"
                                       value="{{ $s->soan_evaluacion }}">
                            </td>
                            <td>
                                <label class="switch">
                                    <input type="checkbox" class="campo-aprobado" {{ $s->soan_aprobado ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <button type="button"
                                        class="icon-doc-pdf btn-menu-dictamen"
                                        data-id="{{ $s->soan_id_solicitud_anfeca }}"
                                        title="Seleccionar Dictamen">
                                    <svg width="22" height="26" viewBox="0 0 24 28" fill="none">
                                        <path d="M4 0C2.34 0 1 1.34 1 3V25C1 26.66 2.34 28 4 28H20C21.66 28 23 26.66 23 25V8L15 0H4Z" fill="#FFFFFF" stroke="#333333" stroke-width="1.5"/>
                                        <path d="M15 0V8H23" stroke="#333333" stroke-width="1.5"/>
                                        <text x="4" y="20" font-size="7" font-weight="bold" fill="#333333">PDF</text>
                                    </svg>
                                </button>
                            </td>
                            <td>
                                <button type="button" class="btn-action-icon btn-ver-datos" title="Ver datos del registro">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2">
                                        <path d="M12 20h9"></path>
                                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach

                    <tr id="filaSinResultados" style="display:none;">
                        <td colspan="12" style="text-align:center; padding:24px; color:#7a6462;">
                            No hay solicitudes que coincidan con los filtros seleccionados.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Ventana con los datos del registro -->
<div class="modal-fondo" id="modalDatos">
    <div class="modal-caja">
        <h3 id="modalTitulo">Datos del registro</h3>
        <table class="modal-tabla"><tbody id="modalCuerpo"></tbody></table>
        <button type="button" class="modal-cerrar" id="modalCerrar">Cerrar</button>
    </div>
</div>

<div class="toast" id="toast"></div>

<!-- Menú desplegable flotante de Dictámenes -->
<div class="menu-dictamen-flotante" id="menuDictamenFlotante">
    <a href="#" id="linkDictamenImprocedencia" target="_blank">
        Dictamen de improcedencia
    </a>
    <a href="#" id="linkDictamenFavorablePrimera" target="_blank">
        Dictamen de certificación académica favorable (primera vez)
    </a>
    <a href="#" id="linkDictamenRefrendo14" target="_blank">
        Dictamen de certificación académica por refrendo Artículo 14
    </a>
    <a href="#" id="linkDictamenRefrendo14NoFav" target="_blank">
        Dictamen de resultado no favorable para refrendo por Artículo 14
    </a>
    <a href="#" id="linkDictamenNoFavorable" target="_blank">
        Dictamen de certificación académica no favorable
    </a>
</div>

@include('partials.footer')

<script>
const CSRF = '{{ csrf_token() }}';
const URL_GUARDAR = '{{ url('/zona') }}';

/* ---------- Menús ---------- */
function toggleMenu(id) {
    const target = document.getElementById(id);
    document.querySelectorAll('.menu-desplegable').forEach(m => { if (m.id !== id) m.classList.remove('show'); });
    if (target) target.classList.toggle('show');
}
window.addEventListener('click', e => {
    if (!e.target.closest('#menuDictamenFlotante')) {
        menuDictamen.classList.remove('show');
    }
});
window.addEventListener('scroll', () => menuDictamen.classList.remove('show'), true);

/* ---------- Avisos ---------- */
const toast = document.getElementById('toast');
let toastTimer;
function aviso(texto, tipo) {
    toast.textContent = texto;
    toast.className = 'toast ' + tipo;
    toast.style.display = 'block';
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.style.display = 'none', 2500);
}

/* ---------- Guardar folio / evaluación / aprobado ---------- */
async function guardarFila(fila, silencioso = false) {
    const folio = fila.querySelector('.campo-folio');
    const evaluacion = fila.querySelector('.campo-evaluacion');
    const aprobado = fila.querySelector('.campo-aprobado');

    // Regla: 2000 o más aprueba automáticamente
    if (evaluacion.value !== '' && Number(evaluacion.value) >= 2000) {
        aprobado.checked = true;
    }

    const cuerpo = new FormData();
    cuerpo.append('folio', folio.value.trim());
    cuerpo.append('evaluacion', evaluacion.value);
    cuerpo.append('aprobado', aprobado.checked ? 1 : 0);

    try {
        const r = await fetch(`${URL_GUARDAR}/${fila.dataset.id}/guardar`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: cuerpo,
        });
        const datos = await r.json();

        if (!r.ok) {
            const msg = datos.errors ? Object.values(datos.errors).flat().join(' ') : 'No se pudo guardar.';
            aviso(msg, 'error');
            return false;
        }

        aprobado.checked = datos.aprobado;
        if (!silencioso) aviso('Guardado', 'ok');
        return true;
    } catch (e) {
        aviso('Error de conexión al guardar.', 'error');
        return false;
    }
}

const filasDatos = document.querySelectorAll('#tbodySolicitudes tr[data-id]');

filasDatos.forEach(fila => {
    fila.querySelectorAll('.campo-folio, .campo-evaluacion').forEach(input => {
        input.addEventListener('keydown', e => {
            if (e.key === 'Enter') { e.preventDefault(); guardarFila(fila); }
        });
        input.addEventListener('change', () => guardarFila(fila));
    });
    fila.querySelector('.campo-aprobado').addEventListener('change', () => guardarFila(fila));
});

// "Guardar" solo envía las filas que se están viendo con los filtros actuales
document.getElementById('btnGuardarTodo').addEventListener('click', async () => {
    const visibles = [...filasDatos].filter(f => f.style.display !== 'none');
    let todoOk = true;
    for (const fila of visibles) {
        if (!(await guardarFila(fila, true))) todoOk = false;
    }
    if (todoOk && visibles.length) aviso('Cambios guardados', 'ok');
});

/* ---------- Búsqueda y filtros ---------- */
const buscar = document.getElementById('buscarInput');
const total = document.getElementById('inputTotal');
const fProceso = document.getElementById('filtroProceso');
const fZona = document.getElementById('filtroZona');
const fTipo = document.getElementById('filtroTipo');
const sinResultados = document.getElementById('filaSinResultados');
const ZONA_FIJA = @json($zonaUsuario ?? null);
const subZona = document.getElementById('subZona');
const titulo = document.getElementById('tituloSolicitudes');

function aplicarFiltros() {
    const q = buscar.value.trim().toLowerCase();
    const proceso = fProceso.value;
    const zona = fZona.value;
    const tipo = fTipo.value;
    let visibles = 0;

    filasDatos.forEach(fila => {
        const texto = (fila.textContent + ' ' + fila.querySelector('.campo-folio').value).toLowerCase();

        const ok = texto.includes(q)
            && (proceso === '' || fila.dataset.proceso === proceso)
            && (zona === '' || fila.dataset.zona === zona)
            && (tipo === '' || fila.dataset.tipo.split(' ').includes(tipo));

        fila.style.display = ok ? '' : 'none';
        if (ok) visibles++;
    });

    total.value = visibles;
    sinResultados.style.display = visibles === 0 ? '' : 'none';

    // Encabezado dinámico
    subZona.textContent = ZONA_FIJA ? "Zona " + ZONA_FIJA : zona === '' ? 'Todas las zonas' : fZona.options[fZona.selectedIndex].text;
    titulo.textContent = proceso === 'completo' ? 'Solicitudes Completadas'
                       : proceso === 'incompleto' ? 'Solicitudes Incompletas'
                       : 'Todas las solicitudes';
}

[buscar].forEach(el => el.addEventListener('input', aplicarFiltros));
[fProceso, fZona, fTipo].forEach(el => el.addEventListener('change', aplicarFiltros));

document.getElementById('btnLimpiar').addEventListener('click', () => {
    buscar.value = '';
    fProceso.value = '';
    fZona.value = '';
    fTipo.value = '';
    aplicarFiltros();
});

// Los menús de la barra superior controlan los mismos filtros
function seleccionarZona(id) {
    fZona.value = id;
    aplicarFiltros();
    document.getElementById('menuZona')?.classList.remove('show');
}
function seleccionarProceso(valor) {
    fProceso.value = valor;
    aplicarFiltros();
    document.getElementById('menuSolicitudes')?.classList.remove('show');
}

aplicarFiltros();

/* ---------- Ventana de datos del registro ---------- */
const modal = document.getElementById('modalDatos');
const cuerpoModal = document.getElementById('modalCuerpo');

document.querySelectorAll('.btn-ver-datos').forEach(btn => {
    btn.addEventListener('click', async () => {
        const fila = btn.closest('tr');
        const nombre = fila.children[3].textContent.trim();
        document.getElementById('modalTitulo').textContent = 'Datos del registro: ' + nombre;
        cuerpoModal.innerHTML = '<tr><td>Cargando...</td></tr>';
        document.querySelectorAll('.menu-desplegable').forEach(m => m.classList.remove('show'));
        modal.classList.add('abierto');

        try {
            const r = await fetch(`${URL_GUARDAR}/${fila.dataset.id}/persona`, { headers: { 'Accept': 'application/json' } });
            if (!r.ok) throw new Error();
            const datos = await r.json();

            cuerpoModal.innerHTML = '';
            Object.entries(datos).forEach(([etiqueta, valor]) => {
                const tr = document.createElement('tr');
                const th = document.createElement('th');
                const td = document.createElement('td');
                th.textContent = etiqueta;
                td.textContent = (valor === null || valor === '') ? '—' : valor;
                tr.append(th, td);
                cuerpoModal.appendChild(tr);
            });
        } catch (e) {
            cuerpoModal.innerHTML = '<tr><td>No se pudieron cargar los datos.</td></tr>';
        }
    });
});

document.getElementById('modalCerrar').addEventListener('click', () => modal.classList.remove('abierto'));
modal.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('abierto'); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') modal.classList.remove('abierto'); });

/* ---------- Menú desplegable de Dictámenes ---------- */
const menuDictamen = document.getElementById('menuDictamenFlotante');
const linkImprocedencia = document.getElementById('linkDictamenImprocedencia');
const linkFavorablePrimera = document.getElementById('linkDictamenFavorablePrimera');
const linkRefrendo14 = document.getElementById('linkDictamenRefrendo14');
const linkRefrendo14NoFav = document.getElementById('linkDictamenRefrendo14NoFav');
const linkNoFavorable = document.getElementById('linkDictamenNoFavorable');

document.querySelectorAll('.btn-menu-dictamen').forEach(btn => {
    btn.addEventListener('click', e => {
        e.stopPropagation();
        const id = btn.dataset.id;
        linkImprocedencia.href = `${URL_GUARDAR}/${id}/dictamen/improcedencia`;
        linkFavorablePrimera.href = `${URL_GUARDAR}/${id}/dictamen/favorable-primera`;
        linkRefrendo14.href = `${URL_GUARDAR}/${id}/dictamen/refrendo-art14`;
        linkRefrendo14NoFav.href = `${URL_GUARDAR}/${id}/dictamen/refrendo-art14-no-fav`;
        linkNoFavorable.href = `${URL_GUARDAR}/${id}/dictamen/no-favorable`;

        const rect = btn.getBoundingClientRect();
        menuDictamen.classList.add('show');

        const anchoMenu = menuDictamen.offsetWidth;
        const left = Math.max(12, rect.right - anchoMenu);
        const top = Math.min(window.innerHeight - menuDictamen.offsetHeight - 12, rect.bottom + 6);

        menuDictamen.style.left = `${left}px`;
        menuDictamen.style.top = `${top}px`;
    });
});
</script>