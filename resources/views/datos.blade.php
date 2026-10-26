<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    @include('partials.header')

    <main class="registro-main">
        <form class="registro-form" action="{{ route('datos') }}" method="POST">
            @csrf

            @if ($errors->any())
                <div style="color:red; margin-bottom:10px;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-columns">
                <div class="form-column">
                    <h2 class="col-header">Datos Personales</h2>
                    <div class="input-grid">
                        <div class="input-group">
                            <label for="curp">CURP*</label>
                            <input type="text" id="curp" name="curp" value="{{ old('curp') }}" maxlength="18" required>
                        </div>
                        <div class="input-group">
                            <label for="nombres">Nombre(s)*</label>
                            <input type="text" id="nombres" name="nombres" value="{{ old('nombres') }}" required>
                        </div>
                        <div class="input-group">
                            <label for="rfc">RFC (homoclave)*</label>
                            <input type="text" id="rfc" name="rfc" value="{{ old('rfc') }}" maxlength="13" required>
                        </div>
                        <div class="input-group">
                            <label for="apellido_p">Apellido Paterno*</label>
                            <input type="text" id="apellido_p" name="apellido_p" value="{{ old('apellido_p') }}" required>
                        </div>
                        <div class="input-group">
                            <label for="cedula">Cedula Profesional*</label>
                            <input type="text" id="cedula" name="cedula" value="{{ old('cedula') }}" maxlength="20" required>
                        </div>
                        <div class="input-group">
                            <label for="apellido_m">Apellido Materno</label>
                            <input type="text" id="apellido_m" name="apellido_m" value="{{ old('apellido_m') }}">
                        </div>
                        <div class="input-group">
                            <label for="password">Contraseña* (mín. 8 caracteres)</label>
                            <input type="password" id="password" name="password" minlength="8" required>
                        </div>
                        <div class="input-group">
                            <label for="password_conf">Confirmación contraseña*</label>
                            <input type="password" id="password_conf" name="password_conf" required>
                        </div>
                    </div>
                </div>

                <div class="form-column">
                    <h2 class="col-header">Domicilio</h2>
                    <div class="input-grid">
                        <div class="input-group">
                            <label for="calle">Calle*</label>
                            <input type="text" id="calle" name="calle" value="{{ old('calle') }}" required>
                        </div>
                        <div class="input-group">
                            <label for="estado">Entidad Federativa*</label>
                            <select id="estado" name="estado" required>
                                <option value="">Selecciona...</option>
                                @foreach($estados as $estado)
                                    <option value="{{ $estado->esta_id_estado }}" @selected(old('estado') == $estado->esta_id_estado)>{{ $estado->esta_nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group">
                            <label for="num_ext">Num. Exterior*</label>
                            <input type="text" id="num_ext" name="num_ext" value="{{ old('num_ext') }}" required>
                        </div>
                        <div class="input-group">
                            <label for="colonia">Colonia*</label>
                            <input type="text" id="colonia" name="colonia" value="{{ old('colonia') }}" required>
                        </div>
                        <div class="input-group">
                            <label for="num_int">Num. Interior</label>
                            <input type="text" id="num_int" name="num_int" value="{{ old('num_int') }}">
                        </div>
                        <div class="input-group">
                            <label for="municipio">Alcaldía/Municipio*</label>
                            <select id="municipio" name="municipio" required>
                                <option value="">Selecciona...</option>
                                @foreach($alcaldias as $alcaldia)
                                    <option value="{{ $alcaldia->alca_id_alcaldia }}" @selected(old('municipio') == $alcaldia->alca_id_alcaldia)>{{ $alcaldia->alca_nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group">
                            <label for="cp">Código Postal*</label>
                            <input type="text" id="cp" name="cp" value="{{ old('cp') }}" maxlength="5" required>
                        </div>
                        <div class="input-group"></div>
                    </div>
                </div>

                <div class="form-column">
                    <h2 class="col-header">Comunicación</h2>
                    <div class="input-grid single-col">
                        <div class="input-group">
                            <label for="correo">Correo Electrónico*</label>
                            <input type="email" id="correo" name="correo" value="{{ old('correo') }}" required>
                        </div>
                        <div class="input-group">
                            <label for="tel_particular">Teléfono Particular*</label>
                            <input type="tel" id="tel_particular" name="tel_particular" value="{{ old('tel_particular') }}" required>
                        </div>
                        <div class="input-group">
                            <label for="tel_celular">Teléfono Celular *</label>
                            <input type="tel" id="tel_celular" name="tel_celular" value="{{ old('tel_celular') }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn-registro" onclick="window.location.href='{{ route('index') }}'">Anterior</button>
                <button type="submit" class="btn-registro">Siguiente</button>
            </div>
        </form>
    </main>

    @include('partials.footer')
</body>
</html>