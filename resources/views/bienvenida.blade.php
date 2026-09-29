@include('partials.header')

<main class="bienvenida-main">
    
    <!-- Etiqueta de usuario superior derecha -->
    <div class="bienvenida-badge-container">
        <div class="bienvenida-user-badge">
            <!-- Asumiendo que tienes estos íconos en tu carpeta public/img/ -->
            <img src="{{ asset('img/icon-edit.svg') }}" alt="Editar">
            <img src="{{ asset('img/icon-user.svg') }}" alt="Usuario">
            <span>Usuario: Victor Godínez Paredes</span>
        </div>
    </div>

    <!-- Título principal -->
    <div class="bienvenida-title-container">
        <h2 class="bienvenida-title">Bienvenido de Nuevo: Víctor Godínez Paredes</h2>
    </div>

    <!-- Contenido de datos en 3 columnas flex -->
    <div class="bienvenida-content">
        
        <!-- Columna 1: Ícono de usuario grande -->
        <div class="bienvenida-large-icon">
            <img src="{{ asset('img/user-check-large.png') }}" alt="Ícono de Perfil Verificado">
        </div>

        <!-- Columna 2: Datos personales -->
        <div class="bienvenida-datos-col">
            <h3 class="bienvenida-subtitle">Datos personales:</h3>
            <p>
                <span class="bienvenida-label">Domicilio:</span> Circuito Exterior, C.U., Coyoacán,<br> 
                04510 Ciudad de México, CDMX
            </p>
            <p>
                <span class="bienvenida-label">CURP:</span> TEST990101HDFR00
            </p>
        </div>

        <!-- Columna 3: Datos de contacto -->
        <div class="bienvenida-datos-col">
            <h3 class="bienvenida-subtitle">Datos de contacto:</h3>
            <p>
                <span class="bienvenida-label">Teléfono de celular:</span> 55 1234 5678
            </p>
            <p>
                <span class="bienvenida-label">Teléfono particular:</span> 55 5622 8288
            </p>
            <p>
                <span class="bienvenida-label">Correo Electrónico:</span> victortest@fca.unam.mx
            </p>
        </div>
        
    </div>

    <!-- Botones de acción inferiores -->
    <div class="bienvenida-actions">
        <!-- Reemplaza las rutas con las rutas correspondientes en tu web.php de Laravel -->
        <button type="button" class="bienvenida-btn" onclick="window.location.href='{{ url('/anterior') }}'">Anterior</button>
        <button type="button" class="bienvenida-btn" onclick="window.location.href='{{ url('/siguiente') }}'">Siguiente</button>
    </div>

</main>

@include('partials.footer')