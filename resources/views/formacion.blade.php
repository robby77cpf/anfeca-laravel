<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formación y Certificación - ANFECA</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    @include('partials.header')

    <main class="registro-main">
        <!-- Badge superior de usuario -->
        <!-- <div class="user-badge-container">
            <div class="user-badge">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="#e2e8f0" stroke="#64748b" stroke-width="1.5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <circle cx="12" cy="9" r="4" fill="#94a3b8" stroke="none"></circle>
                    <path d="M6 18c0-3 3-4.5 6-4.5s6 1.5 6 4.5" stroke="#94a3b8" stroke-width="2" fill="none"></path>
                </svg>
                <span>Usuario: Victor Godínez Paredes</span>
            </div>
        </div> -->

        <form class="registro-form" action="" method="POST">
@csrf
            <!-- Estructura principal en 3 columnas alineadas con datos.php -->
            <div class="form-columns">
                
                <!-- Columna 1: Certificación -->
                <div class="form-column">
                    <h2 class="col-header">Certificación</h2>
                    <p class="section-instruction">Seleccione la Certificación Académica a la que se aspira*</p>
                    
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="certificacion" value="ANFECA" required>
                            <span>ANFECA</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="certificacion" value="CACSLA">
                            <span>CACSLA</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="certificacion" value="CACECA">
                            <span>CACECA</span>
                        </label>
                    </div>
                </div>

                <!-- Columna 2: Personal Académico -->
                <div class="form-column">
                    <h2 class="col-header">Personal Académico</h2>
                    
                    <div class="input-grid single-col">
                        <div class="input-group">
                            <label for="tipo_personal">Tipo de Personal Académico*</label>
                            <select id="tipo_personal" name="tipo_personal" required>
                                <option value="" disabled selected>Seleccione una opción</option>
                                <option value="Tiempo Completo">Tiempo Completo</option>
                                <option value="Medio Tiempo">Medio Tiempo</option>
                                <option value="PAES">PAES</option>
                            </select>
                        </div>

                        <div class="input-group">
                            <label for="area_certificacion">Área de Certificación Personal*</label>
                            <select id="area_certificacion" name="area_certificacion" required>
                                <option value="" disabled selected>Seleccione una opción</option>
                                <option value="Contaduría">Contaduría</option>
                                <option value="Administración">Administración</option>
                                <option value="Informática">Informática</option>
                                <option value="Negocios Internacionales">Negocios Internacionales</option>
                                <option value="Disciplinas vinculadas">Disciplinas vinculadas al ámbito...</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Columna 3: Institución de Procedencia -->
                <div class="form-column">
                    <h2 class="col-header">Institución de Procedencia</h2>
                    
                    <div class="input-grid single-col">
                        <div class="input-group">
                            <label for="num_afiliacion">Número de Afiliación*</label>
                            <input type="text" id="num_afiliacion" name="num_afiliacion" required>
                        </div>
                    </div>

                    <div class="procedencia-info">
                        <p><strong>Institución:</strong> <span id="info_institucion">---</span></p>
                        <p><strong>Sede:</strong> <span id="info_sede">---</span></p>
                        <p><strong>Zona ANFECA:</strong> <span id="info_zona">---</span></p>
                    </div>
                </div>

            </div>

            <!-- Botones de Acción -->
            <div class="form-actions">
                <button type="button" class="btn-registro" onclick="window.location.href='/datos'">Anterior</button>
                <button type="submit" class="btn-registro" formaction="/documentos">Siguiente</button>
            </div>
        </form>
    </main>

    @include('partials.footer')
</body>
</html>