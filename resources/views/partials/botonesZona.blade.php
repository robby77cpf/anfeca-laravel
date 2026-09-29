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
            <a href="{{ route('index') }}" class="btn-top btn-cerrar-sesion">Cerrar Sesión</a>

            <!-- Botón de Usuario -->
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
                <span class="user-label">Usuario: <strong>Victor Godínez Paredes</strong></span>
            </a>
        </div>
    </div>