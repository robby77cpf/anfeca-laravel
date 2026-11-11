@include('partials.header')

<link rel="stylesheet" href="{{ asset('css/zona.css') }}">

<style>
    .input-folio { width: 110px; text-align: center; }
    .link-docs { color:#173459; font-weight:bold; text-decoration:underline; }
    .toast { position:fixed; bottom:20px; right:20px; padding:10px 16px; border-radius:6px; color:#fff;
             font-size:14px; display:none; z-index:60; }
    .toast.ok { background:#1a7f37; }
    .toast.error { background:#b00020; }
    .modal-fondo { position:fixed; inset:0; background:rgba(0,0,0,.5); display:none;
                   align-items:center; justify-content:center; z-index:50; }
    .modal-fondo.abierto { display:flex; }
    .modal-caja { background:#fff; border-radius:8px; width:min(640px, 94vw); max-height:86vh;
                  overflow:auto; padding:20px; }
    .modal-caja h3 { margin:0 0 12px; color:#173459; }
    .modal-tabla { width:100%; border-collapse:collapse; }
    .modal-tabla th { text-align:left; width:40%; background:#f3f3f3; }
    .modal-tabla th, .modal-tabla td { border:1px solid #ddd; padding:7px 10px; font-size:14px; }
    .modal-cerrar { margin-top:14px; padding:8px 18px; background:#173459; color:#fff; border:0;
                    border-radius:4px; cursor:pointer; }
</style>

<div class="zona-container">
    <!-- Barra Superior de Navegación y Acciones -->
    <div class="nav-bar-superior">
        <div class="menu-botones-left">
            <div class="dropdown-zona" id="dropdownZona">
                <button type="button" class="btn-top" onclick="toggleMenu('menuZona')">
                    Zona <span class="arrow">&#9660;</span>
                </button>
                <div class="menu-desplegable" id="menuZona">
                    @for ($z = 1; $z <= 7; $z++)
                        <a href="javascript:void(0)" onclick="seleccionarOpcion('Zona {{ $z }}')">Zona {{ $z }}</a>
                    @endfor
                </div>
            </div>

            <button type="button" class="btn-top">Reportes</button>

            <a href="{{ route('revision.index') }}" class="btn-top">Documentos</a>

            <div class="dropdown-zona" id="dropdownSolicitudes">
                <button type="button" class="btn-top" onclick="toggleMenu('menuSolicitudes')">
                    Solicitudes <span class="arrow">&#9660;</span>
                </button>
                <div class="menu-desplegable" id="menuSolicitudes">
                    <a href="#">Solicitudes Aceptadas</a>
                    <a href="#">Solicitudes Pendientes</a>
                </div>
            </div>

            <button type="button" class="btn-top">Acta</button>
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
                <span class="sub-zona">Zona 7</span>
                <h2 class="titulo-solicitudes">Solicitudes Completadas</h2>
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

        <div class="tabla-container">
            <table class="tabla-solicitudes">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Zona</th>
                        <th>Institución</th>
                        <th>Nombre del Concursante</th>
                        <th>Estatus</th>
                        <th>Certificación</th>
                        <th>Documentos</th>
                        <th>Evaluación</th>
                        <th>Aprobado</th>
                        <th>Dictamen</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody id="tbodySolicitudes">
                    @forelse ($solicitudes as $s)
                        @php
                            $nombre = trim($s->pers_nombre.' '.$s->pers_apellido_paterno.' '.($s->pers_apellido_materno ?? ''));
                            $tipo = $s->soan_id_refrendo_certificacion ? 'Refrendo' : 'Primera Vez';
                            $certificacion = $s->area_descripcion ?? $s->soan_nombre_certificacion ?? '—';
                        @endphp
                        <tr data-id="{{ $s->soan_id_solicitud_anfeca }}">
                            <td>
                                <input type="text" class="input-evaluacion input-folio campo-folio"
                                       maxlength="20" value="{{ $s->soan_folio }}" placeholder="Folio">
                            </td>
                            <td>{{ $s->zona_siglas ?? '—' }}</td>
                            <td>{{ $s->inst_descripcion ?? '—' }}</td>
                            <td>{{ $nombre }}</td>
                            <td>{{ $tipo }}</td>
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
                                <a href="#" class="icon-doc-pdf" title="Ver Dictamen">
                                    <svg width="22" height="26" viewBox="0 0 24 28" fill="none">
                                        <path d="M4 0C2.34 0 1 1.34 1 3V25C1 26.66 2.34 28 4 28H20C21.66 28 23 26.66 23 25V8L15 0H4Z" fill="#FFFFFF" stroke="#333333" stroke-width="1.5"/>
                                        <path d="M15 0V8H23" stroke="#333333" stroke-width="1.5"/>
                                        <text x="4" y="20" font-size="7" font-weight="bold" fill="#333333">PDF</text>
                                    </svg>
                                </a>
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
                    @empty
                        <tr id="filaVacia">
                            <td colspan="11" style="text-align:center; padding:20px;">
                                Todavía no hay solicitudes completadas.
                            </td>
                        </tr>
                    @endforelse
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
function seleccionarOpcion(zona) {
    alert('Has seleccionado ' + zona);
    document.getElementById('menuZona')?.classList.remove('show');
}
window.addEventListener('click', e => {
    if (!e.target.closest('.dropdown-zona')) {
        document.querySelectorAll('.menu-desplegable').forEach(m => m.classList.remove('show'));
    }
});

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

document.querySelectorAll('#tbodySolicitudes tr[data-id]').forEach(fila => {
    fila.querySelectorAll('.campo-folio, .campo-evaluacion').forEach(input => {
        input.addEventListener('keydown', e => {
            if (e.key === 'Enter') { e.preventDefault(); guardarFila(fila); }
        });
        input.addEventListener('change', () => guardarFila(fila));
    });
    fila.querySelector('.campo-aprobado').addEventListener('change', () => guardarFila(fila));
});

document.getElementById('btnGuardarTodo').addEventListener('click', async () => {
    const filas = document.querySelectorAll('#tbodySolicitudes tr[data-id]');
    let todoOk = true;
    for (const fila of filas) {
        if (!(await guardarFila(fila, true))) todoOk = false;
    }
    if (todoOk && filas.length) aviso('Cambios guardados', 'ok');
});

/* ---------- Buscar ---------- */
const buscar = document.getElementById('buscarInput');
const total = document.getElementById('inputTotal');
buscar.addEventListener('input', () => {
    const q = buscar.value.trim().toLowerCase();
    let visibles = 0;
    document.querySelectorAll('#tbodySolicitudes tr[data-id]').forEach(fila => {
        const texto = (fila.textContent + ' ' + fila.querySelector('.campo-folio').value).toLowerCase();
        const ok = texto.includes(q);
        fila.style.display = ok ? '' : 'none';
        if (ok) visibles++;
    });
    total.value = visibles;
});

/* ---------- Ventana de datos del registro ---------- */
const modal = document.getElementById('modalDatos');
const cuerpoModal = document.getElementById('modalCuerpo');

document.querySelectorAll('.btn-ver-datos').forEach(btn => {
    btn.addEventListener('click', async () => {
        const fila = btn.closest('tr');
        const nombre = fila.children[3].textContent.trim();
        document.getElementById('modalTitulo').textContent = 'Datos del registro: ' + nombre;
        cuerpoModal.innerHTML = '<tr><td>Cargando...</td></tr>';
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
</script>