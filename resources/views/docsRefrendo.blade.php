@include('partials.header')

<main class="docs-main-container">
    <!-- Etiqueta de Usuario (Esquina superior derecha) -->
    <div class="user-badge-container">
        <div class="user-badge">
            <img src="{{ asset('img/edit-icon.png') }}" alt="Editar" class="icon-sm">
            <img src="{{ asset('img/user-avatar.png') }}" alt="Usuario" class="icon-md">
            <span>Usuario: Victor Godínez Paredes</span>
        </div>
    </div>

    <!-- Formulario de Documentos -->
    <!-- Nota: Se añade enctype="multipart/form-data" para permitir la subida de archivos -->
    <form action="{{ route('docsRefrendo') }}" method="POST" enctype="multipart/form-data" class="docs-form">
        @csrf
        
        <div class="docs-grid">
            
            <!-- Columna Izquierda: Documentos Básicos -->
            <div class="docs-column">
                <h3 class="docs-col-title">Documentos Básicos</h3>
                
                <div class="doc-item">
                    <div class="doc-info">
                        <img src="{{ asset('img/doc-icon.png') }}" alt="Documento" class="doc-icon">
                        <span class="doc-name">Constancia de Antigüedad</span>
                    </div>
                    <label class="doc-btn-label">
                        <input type="file" name="doc_antiguedad" class="doc-hidden-input" onchange="actualizarEstadoArchivo(this)" accept=".pdf,.jpg,.jpeg,.png">
                        <span class="doc-btn-text">Añadir</span>
                    </label>
                </div>

                <div class="doc-item">
                    <div class="doc-info">
                        <img src="{{ asset('img/doc-icon.png') }}" alt="Documento" class="doc-icon">
                        <span class="doc-name">Comprobante de Pago</span>
                    </div>
                    <label class="doc-btn-label">
                        <input type="file" name="doc_pago" class="doc-hidden-input" onchange="actualizarEstadoArchivo(this)" accept=".pdf,.jpg,.jpeg,.png">
                        <span class="doc-btn-text">Añadir</span>
                    </label>
                </div>

                <div class="doc-item">
                    <div class="doc-info">
                        <img src="{{ asset('img/doc-icon.png') }}" alt="Documento" class="doc-icon">
                        <span class="doc-name">Cédula de último grado</span>
                    </div>
                    <label class="doc-btn-label">
                        <input type="file" name="doc_cedula" class="doc-hidden-input" onchange="actualizarEstadoArchivo(this)" accept=".pdf,.jpg,.jpeg,.png">
                        <span class="doc-btn-text">Añadir</span>
                    </label>
                </div>

                <div class="doc-item">
                    <div class="doc-info">
                        <img src="{{ asset('img/doc-icon.png') }}" alt="Documento" class="doc-icon">
                        <span class="doc-name">Título de último grado</span>
                    </div>
                    <label class="doc-btn-label">
                        <input type="file" name="doc_titulo" class="doc-hidden-input" onchange="actualizarEstadoArchivo(this)" accept=".pdf,.jpg,.jpeg,.png">
                        <span class="doc-btn-text">Añadir</span>
                    </label>
                </div>
            </div>

            <!-- Columna Derecha: Documentos Refrendo -->
            <div class="docs-column">
                <h3 class="docs-col-title">Documentos Refrendo</h3>
                
                <div class="doc-item">
                    <div class="doc-info">
                        <img src="{{ asset('img/doc-icon.png') }}" alt="Documento" class="doc-icon">
                        <span class="doc-name">Carta Solicitud de<br>Refrendo por Art. 14</span>
                    </div>
                    <label class="doc-btn-label">
                        <input type="file" name="doc_solicitud_refrendo" class="doc-hidden-input" onchange="actualizarEstadoArchivo(this)" accept=".pdf,.jpg,.jpeg,.png">
                        <span class="doc-btn-text">Añadir</span>
                    </label>
                </div>

                <div class="doc-item">
                    <div class="doc-info">
                        <img src="{{ asset('img/doc-icon.png') }}" alt="Documento" class="doc-icon">
                        <span class="doc-name">Constancia de Refrendo<br>por Art. 14</span>
                    </div>
                    <label class="doc-btn-label">
                        <input type="file" name="doc_constancia_refrendo" class="doc-hidden-input" onchange="actualizarEstadoArchivo(this)" accept=".pdf,.jpg,.jpeg,.png">
                        <span class="doc-btn-text">Añadir</span>
                    </label>
                </div>
            </div>

        </div>

        <!-- Botones de Acción -->
        <div class="docs-actions">
            <button type="button" class="btn btn-red" onclick="window.history.back()">Anterior</button>
            <button type="submit" class="btn btn-red">Siguiente</button>
        </div>
    </form>
</main>

<!-- Script para manejar el cambio visual al subir un documento -->
<script>
    function actualizarEstadoArchivo(input) {
        // Encontrar el contenedor del botón correspondiente
        const label = input.closest('.doc-btn-label');
        const textSpan = label.querySelector('.doc-btn-text');

        // Verificar si se seleccionó al menos un archivo
        if (input.files && input.files.length > 0) {
            textSpan.innerHTML = 'Subido ✓';
            label.classList.add('doc-uploaded'); // Agrega clase para cambiar el color
        } else {
            textSpan.innerHTML = 'Añadir';
            label.classList.remove('doc-uploaded');
        }
    }
</script>

@include('partials.footer')