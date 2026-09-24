<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos - ANFECA</title>
    <link rel="stylesheet" href="styles.css"> <!-- Asegúrate que apunte a tu CSS unificado -->
</head>
<body>

    <!-- Encabezado -->
@include('partials.header')

    <!-- Contenido Principal -->
    <main class="registro-main">
        
        <!-- Insignia del Usuario -->
        <!-- <div class="user-badge-container">
            <div class="user-badge"> -->
                <!-- Icono de Edición -->
                <!-- <svg viewBox="0 0 24 24" fill="none" stroke="#173459" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg> -->
                <!-- Icono de Usuario -->
                <!-- <svg viewBox="0 0 24 24" width="22" height="22">
                    <circle cx="12" cy="12" r="11" fill="#e0e0e0" stroke="#173459" stroke-width="1"/>
                    <circle cx="12" cy="9" r="4" fill="white"/>
                    <path d="M5 20c0-4 4-7 7-7s7 3 7 7" fill="white"/>
                </svg>
                Usuario: Victor Godínez Paredes
            </div>
        </div> -->

        <!-- Formulario Principal -->
        <form class="registro-form" action="" method="POST">
@csrf
            <div class="form-columns docs-columns">
                
                <!-- Columna 1: Documentos Básicos -->
                <div class="form-column basicos-col">
                    <h3 class="col-header">Documentos Básicos</h3>
                    
                    <div class="doc-list">
                        <!-- Iteración de Documentos (Puedes automatizar esto con un array en PHP si lo deseas) -->
                        <?php 
                        $documentosBasicos = [
                            "Título de Licenciatura",
                            "Constancia de Antigüedad",
                            "Cedula de Licenciatura",
                            "Comprobante de Pago",
                            "Título de último grado",
                            "Cédula de último grado"
                        ];

                        foreach ($documentosBasicos as $doc) {
                        ?>
                            <div class="doc-item">
                                <div class="doc-icon-text">
                                    <svg viewBox="0 0 24 28" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 2h10l6 6v18a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"></path>
                                        <path d="M14 2v6h6"></path>
                                        <line x1="6" y1="14" x2="18" y2="14"></line>
                                        <line x1="6" y1="18" x2="18" y2="18"></line>
                                        <line x1="6" y1="22" x2="14" y2="22"></line>
                                    </svg>
                                    <span class="doc-text"><?php echo $doc; ?></span>
                                </div>
                                <label class="btn-añadir">
                                    <input type="file" name="documentos[]" accept="application/pdf,.pdf" hidden>
                                    <span class="btn-text">Añadir</span>
                                </label>
                            </div>
                        <?php 
                        } 
                        ?>
                    </div>
                </div>

                <!-- Columna 2: Documentos Probatorios -->
                <div class="form-column probatorios-col">
                    <h3 class="col-header">Documentos Probatorios</h3>
                    <div class="link-input-group">
                        <label for="drive-link">Link</label>
                        <input type="url" id="drive-link" name="drive-link" placeholder="linkdeejemplocarpetadrive.com">
                    </div>
                </div>
            </div>

            <!-- Botones de Navegación -->
            <div class="form-actions">
                <button type="button" class="btn-registro" onclick="window.location.href='/formacion'">Anterior</button>
                <button type="submit" class="btn-registro">Siguiente</button>
            </div>
        </form>

    </main>

@include('partials.footer')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInputs = document.querySelectorAll('input[type="file"][accept*="pdf"]');

        fileInputs.forEach(function (input) {
            input.addEventListener('change', function () {
                const label = this.closest('.btn-añadir');
                const text = label.querySelector('.btn-text');

                if (this.files && this.files.length > 0) {
                    const file = this.files[0];
                    const isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');

                    if (isPdf) {
                        label.classList.add('btn-cargado');
                        text.textContent = 'Carga Completa';
                    } else {
                        label.classList.remove('btn-cargado');
                        text.textContent = 'Añadir';
                        this.value = '';
                        alert('Solo se permiten archivos PDF.');
                    }
                } else {
                    label.classList.remove('btn-cargado');
                    text.textContent = 'Añadir';
                }
            });
        });
    });
</script>

</body>
</html>