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
        <!-- <div class="user-badge-container">
            <div class="user-badge">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                <svg width="26" height="26" viewBox="0 0 24 24" fill="#e2e8f0" stroke="#64748b" stroke-width="1.5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <circle cx="12" cy="9" r="4" fill="#94a3b8" stroke="none"></circle>
                    <path d="M6 18c0-3 3-4.5 6-4.5s6 1.5 6 4.5" stroke="#94a3b8" stroke-width="2" fill="none"></path>
                </svg>
                <span>Usuario: Victor Godínez Paredes</span>
            </div>
        </div> -->

        <form class="registro-form" action="" method="POST">
@csrf
            <div class="form-columns">
                <div class="form-column">
                    <h2 class="col-header">Datos Personales</h2>
                    <div class="input-grid">
                        <div class="input-group">
                            <label for="curp">CURP*</label>
                            <input type="text" id="curp" name="curp" required>
                        </div>
                        <div class="input-group">
                            <label for="nombres">Nombre(s)*</label>
                            <input type="text" id="nombres" name="nombres" required>
                        </div>
                        <div class="input-group">
                            <label for="rfc">RFC (homoclave)*</label>
                            <input type="text" id="rfc" name="rfc" required>
                        </div>
                        <div class="input-group">
                            <label for="apellido_p">Apellido Paterno*</label>
                            <input type="text" id="apellido_p" name="apellido_p" required>
                        </div>
                        <div class="input-group">
                            <label for="cedula">Cedula Profesional*</label>
                            <input type="text" id="cedula" name="cedula" required>
                        </div>
                        <div class="input-group">
                            <label for="apellido_m">Apellido Materno</label>
                            <input type="text" id="apellido_m" name="apellido_m">
                        </div>
                        <div class="input-group">
                            <label for="password">Contraseña*</label>
                            <input type="password" id="password" name="password" required>
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
                            <input type="text" id="calle" name="calle" required>
                        </div>
                        <div class="input-group">
                            <label for="estado">Entidad Federativa*</label>
                            <input type="text" id="estado" name="estado" required>
                        </div>
                        <div class="input-group">
                            <label for="num_ext">Num. Exterior*</label>
                            <input type="text" id="num_ext" name="num_ext" required>
                        </div>
                        <div class="input-group">
                            <label for="colonia">Colonia*</label>
                            <input type="text" id="colonia" name="colonia" required>
                        </div>
                        <div class="input-group">
                            <label for="num_int">Num. Interior</label>
                            <input type="text" id="num_int" name="num_int">
                        </div>
                        <div class="input-group">
                            <label for="municipio">Alcaldía/Municipio*</label>
                            <input type="text" id="municipio" name="municipio" required>
                        </div>
                        <div class="input-group">
                            <label for="cp">Código Postal*</label>
                            <input type="text" id="cp" name="cp" required>
                        </div>
                        <div class="input-group"></div>
                    </div>
                </div>

                <div class="form-column">
                    <h2 class="col-header">Comunicación</h2>
                    <div class="input-grid single-col">
                        <div class="input-group">
                            <label for="correo">Correo Electrónico*</label>
                            <input type="email" id="correo" name="correo" required>
                        </div>
                        <div class="input-group">
                            <label for="tel_particular">Teléfono Particular*</label>
                            <input type="tel" id="tel_particular" name="tel_particular" required>
                        </div>
                        <div class="input-group">
                            <label for="tel_celular">Teléfono Celular *</label>
                            <input type="tel" id="tel_celular" name="tel_celular" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn-registro" onclick="window.location.href='/index'">Anterior</button>
                <button type="submit" class="btn-registro" formaction="/formacion">Siguiente</button>
            </div>
        </form>
    </main>

    @include('partials.footer')
</body>
</html>