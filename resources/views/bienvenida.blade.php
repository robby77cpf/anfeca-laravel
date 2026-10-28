@include('partials.header')

<main class="bienvenida-main">

    <!-- Etiqueta de usuario superior derecha -->
    <div class="bienvenida-badge-container">
        <div class="bienvenida-user-badge">
            <!-- Asumiendo que tienes estos íconos en tu carpeta public/img/ -->
            <img src="{{ asset('img/icon-edit.svg') }}" alt="Editar">
            <img src="{{ asset('img/icon-user.svg') }}" alt="Usuario">
            <span>Usuario: {{ $nombreCompleto }}</span>
        </div>
    </div>

    <!-- Título principal -->
    <div class="bienvenida-title-container">
        <h2 class="bienvenida-title">Bienvenido de Nuevo: {{ $nombreCompleto }}</h2>
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
                <span class="bienvenida-label">Domicilio:</span>
                {{ $persona->domi_calle }} {{ $persona->domi_numero_exterior }}@if($persona->domi_numero_interior), Int. {{ $persona->domi_numero_interior }}@endif,
                {{ $persona->colo_nombre }},<br>
                {{ $persona->domi_codigo_postal }} {{ $persona->alca_nombre }}, {{ $persona->esta_nombre }}
            </p>
            <p>
                <span class="bienvenida-label">CURP:</span> {{ $persona->pers_curp }}
            </p>
        </div>

        <!-- Columna 3: Datos de contacto -->
        <div class="bienvenida-datos-col">
            <h3 class="bienvenida-subtitle">Datos de contacto:</h3>
            <p>
                <span class="bienvenida-label">Teléfono de celular:</span> {{ $telCelular }}
            </p>
            <p>
                <span class="bienvenida-label">Teléfono particular:</span> {{ $telParticular }}
            </p>
            <p>
                <span class="bienvenida-label">Correo Electrónico:</span> {{ $persona->pers_correo_electronico }}
            </p>
        </div>

    </div>

    <!-- Botones de acción inferiores -->
    <div class="bienvenida-actions">
        <!-- Reemplaza las rutas con las rutas correspondientes en tu web.php de Laravel -->
        <button type="button" class="bienvenida-btn" onclick="window.location.href='{{ url('/index') }}'">Anterior</button>
        <button type="button" class="bienvenida-btn" onclick="window.location.href='{{ url('/formacion') }}'">Siguiente</button>
    </div>

</main>

@include('partials.footer')