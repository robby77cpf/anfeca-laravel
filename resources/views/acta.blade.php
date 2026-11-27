@include('partials.header')

<link rel="stylesheet" href="{{ asset('css/acta.css') }}">

@php
    $solicitudes = [
        'Solicitudes que cumplieron con los requisitos de procedencia y aceptación',
        'Solicitudes de evaluación para la certificación académica en Contaduría Pública',
        'Solicitudes de evaluación para la certificación académica en Administración',
        'Solicitudes de evaluación para la certificación académica en Informática Administrativa',
        'Solicitudes de evaluación para la certificación académica en Negocios Internacionales',
        'Solicitudes de evaluación para la certificación académica en Otras Profesiones Formativas en el Área Económico Administrativa',
        'Número de solicitudes revisadas en la reunión',
        'Total de solicitudes aprobadas',
        'Total de solicitudes No aprobadas',
        'Total de solicitudes procesadas',
    ];
@endphp

<div class="acta-container">

    <!-- Barra superior -->
    <div class="nav-bar-superior">
        <div class="menu-botones-left">
            <a href="{{ route('zona') }}" class="btn-top">&larr; Volver a Zona</a>
        </div>

        <div class="user-actions-right">
            <a href="{{ route('logout') }}" class="btn-top btn-cerrar-sesion">Cerrar Sesión</a>
            <span class="user-label">Usuario: <strong>{{ session('usuario_nombre') }}</strong></span>
        </div>
    </div>

    <div class="contenido-principal">

        <div class="header-seccion">
            <div class="zona-titulo-container">
                <span class="sub-zona">{{ $numZona !== '' ? 'Zona '.$numZona : 'Todas las zonas' }}</span>
                <h2 class="titulo-solicitudes">Captura para acta</h2>
            </div>
        </div>

        <form id="formActa" class="acta-form" autocomplete="off" onsubmit="return false;">

            <!-- Coordinadores -->
            <section class="acta-seccion">
                <h3 class="acta-titulo">Coordinadores</h3>
                <div class="acta-grid">
                    <div class="campo c-12">
                        <label for="coordinador">Coordinador Regional de Certificación Académica</label>
                        <input type="text" id="coordinador" name="coordinador" value="{{ $coordinador }}" readonly>
                    </div>
                    <div class="campo c-12">
                        <label for="secretario">Secretario Regional de Certificación Académica</label>
                        <input type="text" id="secretario" name="secretario">
                    </div>
                </div>
            </section>

            <!-- Sede -->
            <section class="acta-seccion">
                <h3 class="acta-titulo">Sede</h3>
                <div class="acta-grid">
                    <div class="campo c-2">
                        <label for="prefijoInstitucion">Prefijo institución</label>
                        <select id="prefijoInstitucion" name="prefijo_institucion">
                            <option value="De la">De la</option>
                            <option value="Del">Del</option>
                            <option value="De">De</option>
                        </select>
                    </div>
                    <div class="campo c-3">
                        <label for="institucion">Institución</label>
                        <select id="institucion" name="institucion">
                            <option value="">Seleccione una opción</option>
                            @foreach ($instituciones as $i)
                                <option value="{{ $i->inst_id_institucion }}">{{ $i->inst_descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="campo c-4">
                        <label for="facultad">Facultad/Escuela/División</label>
                        <input type="text" id="facultad" name="facultad">
                    </div>
                    <div class="campo c-3">
                        <label for="campus">Campus/Unidad/Sede</label>
                        <input type="text" id="campus" name="campus">
                    </div>

                    <div class="campo c-3">
                        <label for="region">Región, Localidad o Municipio</label>
                        <input type="text" id="region" name="region">
                    </div>
                    <div class="campo c-3">
                        <label for="estado">Ciudad/Estado</label>
                        <select id="estado" name="estado">
                            <option value="">Seleccione una opción</option>
                            @foreach ($estados as $e)
                                <option value="{{ $e->esta_id_estado }}">{{ $e->esta_nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="campo c-2">
                        <label for="zona">Zona</label>
                        @if ($esSuper)
                            <select id="zona" name="zona">
                                <option value="">Seleccione</option>
                                @for ($n = 1; $n <= 7; $n++)
                                    <option value="{{ $n }}">Zona {{ $n }}</option>
                                @endfor
                            </select>
                        @else
                            <input type="text" id="zona" name="zona" value="{{ $numZona }}" readonly>
                        @endif
                    </div>
                    <div class="campo c-4">
                        <label for="numActa">No. de acta</label>
                        <input type="text" id="numActa" name="numero_acta">
                    </div>

                    <div class="campo c-2">
                        <label for="prefijoLugar">Prefijo lugar</label>
                        <select id="prefijoLugar" name="prefijo_lugar">
                            <option value="En la">En la</option>
                            <option value="En el">En el</option>
                            <option value="En">En</option>
                        </select>
                    </div>
                    <div class="campo c-2">
                        <label for="lugar">Lugar</label>
                        <select id="lugar" name="lugar">
                            <option value="">Seleccione una opción</option>
                            <option>Sala</option>
                            <option>Salón</option>
                            <option>Aula</option>
                            <option>Auditorio</option>
                            <option>Oficina</option>
                            <option>Sala de juntas</option>
                            <option>Otro</option>
                        </select>
                    </div>
                    <div class="campo c-4">
                        <label for="nombreLugar">Nombre/Descripción del lugar</label>
                        <input type="text" id="nombreLugar" name="nombre_lugar">
                    </div>
                    <div class="campo c-2">
                        <label for="fecha">Fecha</label>
                        <input type="date" id="fecha" name="fecha">
                    </div>
                    <div class="campo c-2">
                        <label for="hora">Hora</label>
                        <input type="time" id="hora" name="hora">
                    </div>
                </div>
            </section>

            <!-- Evaluadores -->
            <section class="acta-seccion">
                <h3 class="acta-titulo">Evaluadores</h3>
                <div id="listaEvaluadores">
                    <div class="evaluador-fila">
                        <div class="campo">
                            <label>Nombre</label>
                            <input type="text" name="evaluadores[0][nombre]">
                        </div>
                        <div class="campo">
                            <label>Apellido paterno</label>
                            <input type="text" name="evaluadores[0][apellido_paterno]">
                        </div>
                        <div class="campo">
                            <label>Apellido materno</label>
                            <input type="text" name="evaluadores[0][apellido_materno]">
                        </div>
                        <div class="evaluador-acciones">
                            <button type="button" class="btn-mini btn-agregar" id="btnAgregar">Agregar otro</button>
                            <button type="button" class="btn-mini btn-eliminar" id="btnEliminar">Eliminar</button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Solicitudes (vacía por ahora) -->
            <section class="acta-seccion">
                <h3 class="acta-titulo">Solicitudes</h3>
                <div class="acta-solicitudes">
                    @foreach ($solicitudes as $texto)
                        <div class="sol-fila">
                            <span>{{ $texto }}</span>
                            <input type="text" class="sol-valor" readonly>
                        </div>
                    @endforeach
                </div>
            </section>

            <div class="acta-pie">
                <button type="button" class="btn-guardar" id="btnGenerar">Generar Acta</button>
            </div>
        </form>
    </div>
</div>

<div class="toast" id="toast"></div>

@include('partials.footer')

<script>
/* ---------- Evaluadores dinámicos ---------- */
const lista = document.getElementById('listaEvaluadores');
let indice = 0;

document.getElementById('btnAgregar').addEventListener('click', () => {
    indice++;
    const fila = document.createElement('div');
    fila.className = 'evaluador-fila';
    fila.innerHTML = `
        <div class="campo"><label>Nombre</label>
            <input type="text" name="evaluadores[${indice}][nombre]"></div>
        <div class="campo"><label>Apellido paterno</label>
            <input type="text" name="evaluadores[${indice}][apellido_paterno]"></div>
        <div class="campo"><label>Apellido materno</label>
            <input type="text" name="evaluadores[${indice}][apellido_materno]"></div>
        <div class="evaluador-acciones"></div>`;
    lista.appendChild(fila);
    fila.querySelector('input').focus();
});

// Elimina el último evaluador agregado (siempre queda al menos uno)
document.getElementById('btnEliminar').addEventListener('click', () => {
    const filas = lista.querySelectorAll('.evaluador-fila');
    if (filas.length > 1) filas[filas.length - 1].remove();
});

/* ---------- Superadministrador: el coordinador cambia según la zona elegida ---------- */
const COORDINADORES = @json($coordinadores);
const campoZona = document.getElementById('zona');
if (campoZona && campoZona.tagName === 'SELECT') {
    campoZona.addEventListener('change', () => {
        document.getElementById('coordinador').value = COORDINADORES[campoZona.value] ?? '';
    });
}

/* ---------- Aviso ---------- */
const toast = document.getElementById('toast');
let toastTimer;
function aviso(texto, tipo) {
    toast.textContent = texto;
    toast.className = 'toast ' + tipo;
    toast.style.display = 'block';
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.style.display = 'none', 2600);
}

// Por ahora no se guarda ni se genera nada
document.getElementById('btnGenerar').addEventListener('click', () => {
    aviso('Por ahora la captura no se guarda.', 'ok');
});
</script>