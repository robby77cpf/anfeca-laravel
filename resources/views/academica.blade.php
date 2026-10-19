@include('partials.header')

<div class="reg-acad-container">
    <form action="{{ route('certificacion.guardarAcademica') }}" method="POST" enctype="multipart/form-data" class="reg-acad-form">
        @csrf

        <div class="reg-acad-grid">
            <!-- Columna Izquierda: Formación Académica de Origen -->
            <div class="reg-acad-col">
                <h2 class="reg-acad-title">Formación Académica de Origen</h2>
                <hr class="reg-acad-divider">

                <div class="reg-acad-form-group">
                    <label class="reg-acad-label">Nivel</label>
                    <select class="reg-acad-select fixed" disabled>
                        <option value="Licenciatura" selected>Licenciatura</option>
                    </select>
                    <!-- Campo oculto para asegurar que el dato inamovible se envíe en el request -->
                    <input type="hidden" name="origen_nivel" value="Licenciatura">
                </div>

                <div class="reg-acad-form-group">
                    <label class="reg-acad-label">Nombre del Título o Grado</label>
                    <select class="reg-acad-select" name="origen_titulo" required>
                        <option value="">Seleccione una opción</option>
                        <option value="Licenciatura en Informática">Licenciatura en Informática</option>
                        <option value="Licenciatura en Contaduría">Licenciatura en Contaduría</option>
                        <option value="Licenciatura en Administración">Licenciatura en Administración</option>
                        <option value="Ingeniería en Sistemas de Información">Ingeniería en Sistemas de Información</option>
                        <option value="Licenciatura en Negocios Internacionales">Licenciatura en Negocios Internacionales</option>
                        <option value="Licenciatura en Derecho">Licenciatura en Derecho</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

                <!-- Subida de Documentos Origen -->
                <div class="reg-acad-uploads">
                    <div class="reg-acad-upload-row">
                        <div class="reg-acad-doc-info">
                            <svg class="reg-acad-doc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <path d="M14 2v6h6"></path>
                                <line x1="8" y1="13" x2="16" y2="13"></line>
                                <line x1="8" y1="17" x2="16" y2="17"></line>
                                <line x1="8" y1="9" x2="10" y2="9"></line>
                            </svg>
                            <span>Título de Licenciatura</span>
                        </div>
                        <label class="reg-acad-btn-add">
                            <span class="reg-acad-btn-text">Añadir</span>
                            <input type="file" name="doc_origen_titulo" class="reg-acad-file-input" accept=".pdf,.jpg,.jpeg,.png">
                        </label>
                    </div>

                    <div class="reg-acad-upload-row">
                        <div class="reg-acad-doc-info">
                            <svg class="reg-acad-doc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <path d="M14 2v6h6"></path>
                                <line x1="8" y1="13" x2="16" y2="13"></line>
                                <line x1="8" y1="17" x2="16" y2="17"></line>
                                <line x1="8" y1="9" x2="10" y2="9"></line>
                            </svg>
                            <span>Cedula de Licenciatura</span>
                        </div>
                        <label class="reg-acad-btn-add">
                            <span class="reg-acad-btn-text">Añadir</span>
                            <input type="file" name="doc_origen_cedula" class="reg-acad-file-input" accept=".pdf,.jpg,.jpeg,.png">
                        </label>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Último Grado de Estudios (Sin sección de documentos) -->
            <div class="reg-acad-col">
                <h2 class="reg-acad-title">Último Grado de Estudios</h2>
                <hr class="reg-acad-divider">

                <div class="reg-acad-form-group">
                    <label class="reg-acad-label">Nivel</label>
                    <select class="reg-acad-select" id="reg_ultimo_nivel" name="ultimo_nivel" required>
                        <option value="">Seleccione una opción</option>
                        <option value="Licenciatura">Licenciatura</option>
                        <option value="Especialidad">Especialidad</option>
                        <option value="Maestría">Maestría</option>
                        <option value="Doctorado">Doctorado</option>
                    </select>
                </div>

                <div class="reg-acad-form-group">
                    <label class="reg-acad-label">Nombre del Título o Grado</label>
                    <select class="reg-acad-select" id="reg_ultimo_titulo" name="ultimo_titulo" required>
                        <option value="">Seleccione primero un nivel</option>
                        <!-- Se llena mediante JS dependiendo de la selección superior -->
                    </select>
                </div>
            </div>
        </div>

        <!-- Botones Anclados a los extremos y empujados hacia abajo -->
        <div class="reg-acad-nav-actions">
            <button type="button" class="reg-acad-btn-nav" onclick="window.history.back();">Anterior</button>
            <button type="submit" class="reg-acad-btn-nav">Siguiente</button>
        </div>
    </form>
</div>

@include('partials.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. Lógica para cambio visual de los botones de subida de archivos ---
        const fileInputs = document.querySelectorAll('.reg-acad-file-input');
        
        fileInputs.forEach(input => {
            input.addEventListener('change', function() {
                const label = this.closest('.reg-acad-btn-add');
                const textSpan = label.querySelector('.reg-acad-btn-text');
                
                if (this.files && this.files.length > 0) {
                    textSpan.textContent = 'Carga completa';
                    label.classList.add('reg-acad-btn-success');
                } else {
                    textSpan.textContent = 'Añadir';
                    label.classList.remove('reg-acad-btn-success');
                }
            });
        });

        // --- 2. Lógica para el llenado dinámico de los títulos de Último Grado ---
        const titulosDependientes = {
            'Licenciatura': [
                'Licenciatura en Informática',
                'Licenciatura en Contaduría',
                'Licenciatura en Administración',
                'Licenciatura en Derecho',
                'Ingeniería en Sistemas',
                'Otra Licenciatura'
            ],
            'Especialidad': [
                'Especialidad en Impuestos',
                'Especialidad en Alta Dirección',
                'Especialidad en Recursos Humanos',
                'Especialidad en Sistemas Financieros',
                'Otra Especialidad'
            ],
            'Maestría': [
                'Maestría en Administración',
                'Maestría en Auditoría',
                'Maestría en Finanzas',
                'Maestría en Tecnologías de la Información',
                'Otra Maestría'
            ],
            'Doctorado': [
                'Doctorado en Ciencias de la Administración',
                'Doctorado en Ciencias de la Computación',
                'Doctorado en Educación',
                'Otro Doctorado'
            ]
        };

        const nivelSelect = document.getElementById('reg_ultimo_nivel');
        const tituloSelect = document.getElementById('reg_ultimo_titulo');

        nivelSelect.addEventListener('change', function() {
            const nivel = this.value;
            // Limpiar opciones previas
            tituloSelect.innerHTML = '<option value="">Seleccione una opción</option>';

            if (titulosDependientes[nivel]) {
                titulosDependientes[nivel].forEach(titulo => {
                    const option = document.createElement('option');
                    option.value = titulo;
                    option.textContent = titulo;
                    tituloSelect.appendChild(option);
                });
            } else {
                tituloSelect.innerHTML = '<option value="">Seleccione primero un nivel</option>';
            }
        });
    });
</script>