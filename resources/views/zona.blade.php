@include('partials.header')

<?php
// zona.php
?>

<link rel="stylesheet" href="{{ asset('css/zona.css') }}">

    <div class="zona-container">
    <!-- Barra Superior de Navegación y Acciones -->
    <div class="nav-bar-superior">
        <div class="menu-botones-left">
            <!-- Botón Zona con Desplegable -->
            <div class="dropdown-zona" id="dropdownZona">
                <button type="button" class="btn-top" onclick="toggleMenu('menuZona')">
                    Zona <span class="arrow">&#9660;</span>
                </button>
                <div class="menu-desplegable" id="menuZona">
                    <a href="javascript:void(0)" onclick="seleccionarOpcion('Zona 1')">Zona 1</a>
                    <a href="javascript:void(0)" onclick="seleccionarOpcion('Zona 2')">Zona 2</a>
                    <a href="javascript:void(0)" onclick="seleccionarOpcion('Zona 3')">Zona 3</a>
                    <a href="javascript:void(0)" onclick="seleccionarOpcion('Zona 4')">Zona 4</a>
                    <a href="javascript:void(0)" onclick="seleccionarOpcion('Zona 5')">Zona 5</a>
                    <a href="javascript:void(0)" onclick="seleccionarOpcion('Zona 6')">Zona 6</a>
                    <a href="javascript:void(0)" onclick="seleccionarOpcion('Zona 7')">Zona 7</a>
                </div>
            </div>

            <!-- Botón Reportes -->
            <button type="button" class="btn-top">Reportes</button>

            <!-- Botón Solicitudes con Desplegable -->
            <div class="dropdown-zona" id="dropdownSolicitudes">
                <button type="button" class="btn-top" onclick="toggleMenu('menuSolicitudes')">
                    Solicitudes <span class="arrow">&#9660;</span>
                </button>
                <div class="menu-desplegable" id="menuSolicitudes">
                    <a href="#">Solicitudes Aceptadas</a>
                    <a href="#">Solicitudes Pendientes</a>
                </div>
            </div>

            <!-- Botón Acta -->
            <button type="button" class="btn-top">Acta</button>
        </div>

        <div class="user-actions-right">
            <!-- Botón Cerrar Sesión -->
            <a href="/index" class="btn-top btn-cerrar-sesion">Cerrar Sesión</a>

            <!-- Botón de Usuario -->
            <a href="/editarDatos" class="btn-user-profile" title="Editar Datos del Usuario">
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
                <span class="user-label">Usuario: <strong>Victor Godínez Paredes</strong></span>
            </a>
        </div>
    </div>
<!-- Área Principal con Fondo Rosa -->
    <div class="contenido-principal">
        
        <!-- Encabezado de la Zona y Botón Guardar -->
        <div class="header-seccion">
            <div class="zona-titulo-container">
                <span class="sub-zona">Zona 7</span>
                <h2 class="titulo-solicitudes">Solicitudes Aceptadas</h2>
            </div>
            
            <button type="button" class="btn-guardar">Guardar</button>

            <div class="total-box-container">
                <label>Total</label>
                <input type="text" class="input-total" readonly>
            </div>
        </div>

        <!-- Filtro Buscar -->
        <div class="bar-buscar">
            <label for="buscarInput">Buscar:</label>
            <input type="text" id="buscarInput" class="input-buscar">
        </div>

        <!-- Tabla de Registros -->
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
                        <th>Certificación a Concursar</th>
                        <th>Evaluación</th>
                        <th>Aprobado</th>
                        <th>Dictamen</th>
                        <th>Editar</th>
                        <th>Borrar</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Fila 1 -->
                    <tr>
                        <td class="bold">26Z7RC003</td>
                        <td>7</td>
                        <td>Instituto Politécnico Nacional ESCA Sto. Tomás</td>
                        <td>Alma Lucero Sosa Blancas</td>
                        <td>Refrendo</td>
                        <td>Contaduría Pública</td>
                        <td>
                            <a href="#" class="icon-doc-pdf" title="Ver Documento">
                                <svg width="22" height="26" viewBox="0 0 24 28" fill="none">
                                    <path d="M4 0C2.34 0 1 1.34 1 3V25C1 26.66 2.34 28 4 28H20C21.66 28 23 26.66 23 25V8L15 0H4Z" fill="#FFFFFF" stroke="#333333" stroke-width="1.5"/>
                                    <path d="M15 0V8H23" stroke="#333333" stroke-width="1.5"/>
                                    <text x="4" y="20" font-size="7" font-weight="bold" fill="#333333">PDF</text>
                                </svg>
                            </a>
                        </td>
                        <td>
                            <input type="text" class="input-evaluacion">
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox">
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
                            <a href="/editarDatos" class="btn-action-icon" title="Editar">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2">
                                    <path d="M12 20h9"></path>
                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                </svg>
                            </a>
                        </td>
                        <td>
                            <button type="button" class="btn-action-icon btn-trash" title="Borrar">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Fila 2 -->
                    <tr>
                        <td class="bold">26Z7PO002</td>
                        <td>3</td>
                        <td>Universidad Autónoma de Querétaro FCA-UNAM</td>
                        <td>Aaron Iván Gonzalez Neri</td>
                        <td>Primera Vez</td>
                        <td>Negocios Internacionales</td>
                        <td>
                            <a href="#" class="icon-doc-pdf" title="Ver Documento">
                                <svg width="22" height="26" viewBox="0 0 24 28" fill="none">
                                    <path d="M4 0C2.34 0 1 1.34 1 3V25C1 26.66 2.34 28 4 28H20C21.66 28 23 26.66 23 25V8L15 0H4Z" fill="#FFFFFF" stroke="#333333" stroke-width="1.5"/>
                                    <path d="M15 0V8H23" stroke="#333333" stroke-width="1.5"/>
                                    <text x="4" y="20" font-size="7" font-weight="bold" fill="#333333">PDF</text>
                                </svg>
                            </a>
                        </td>
                        <td>
                            <input type="text" class="input-evaluacion">
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox">
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
                            <a href="/editarDatos" class="btn-action-icon" title="Editar">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2">
                                    <path d="M12 20h9"></path>
                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                </svg>
                            </a>
                        </td>
                        <td>
                            <button type="button" class="btn-action-icon btn-trash" title="Borrar">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Fila 3 -->
                    <tr>
                        <td class="bold">26Z7PAD02</td>
                        <td>7</td>
                        <td>Universidad Intercontinental Campus Sur</td>
                        <td>Diana Del Carmen Pérez Ruíz</td>
                        <td>Primera Vez</td>
                        <td>Administración</td>
                        <td>
                            <a href="#" class="icon-doc-pdf" title="Ver Documento">
                                <svg width="22" height="26" viewBox="0 0 24 28" fill="none">
                                    <path d="M4 0C2.34 0 1 1.34 1 3V25C1 26.66 2.34 28 4 28H20C21.66 28 23 26.66 23 25V8L15 0H4Z" fill="#FFFFFF" stroke="#333333" stroke-width="1.5"/>
                                    <path d="M15 0V8H23" stroke="#333333" stroke-width="1.5"/>
                                    <text x="4" y="20" font-size="7" font-weight="bold" fill="#333333">PDF</text>
                                </svg>
                            </a>
                        </td>
                        <td>
                            <input type="text" class="input-evaluacion">
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox">
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
                            <a href="/editarDatos" class="btn-action-icon" title="Editar">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2">
                                    <path d="M12 20h9"></path>
                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                </svg>
                            </a>
                        </td>
                        <td>
                            <button type="button" class="btn-action-icon btn-trash" title="Borrar">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('partials.footer')   

<script>
function toggleMenu(id) {
    const targetMenu = document.getElementById(id);
    const allMenus = document.querySelectorAll('.menu-desplegable');
    
    allMenus.forEach(menu => {
        if (menu.id !== id) {
            menu.classList.remove('show');
        }
    });

    if (targetMenu) {
        targetMenu.classList.toggle('show');
    }
}

function seleccionarOpcion(zona) {
    alert("Has seleccionado " + zona);
    const menu = document.getElementById('menuZona');
    if (menu) menu.classList.remove('show');
}

// Cerrar menús al hacer clic fuera
window.addEventListener('click', function(event) {
    if (!event.target.closest('.dropdown-zona')) {
        document.querySelectorAll('.menu-desplegable').forEach(menu => {
            menu.classList.remove('show');
        });
    }
});
</script>