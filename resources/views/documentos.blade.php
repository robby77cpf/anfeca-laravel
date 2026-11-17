<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos - ANFECA</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

@include('partials.header')

    <main class="registro-main">

        <form class="registro-form" action="{{ route('documentos.guardar') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @if (session('status'))
                <p style="color:#1a7f37; font-weight:bold;">{{ session('status') }}</p>
            @endif

            @if ($errors->any())
                <ul style="color:#b00020;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <div class="form-columns docs-columns">

                <!-- Columna 1: Documentos Básicos -->
                <div class="form-column basicos-col">
                    <h3 class="col-header">Documentos Básicos</h3>

                    <div class="doc-list">
                        @foreach ($archivos as $campo => $info)
                            @php
                                $guardado = $docs && ! empty($docs->{$info[0]});
                                $textoInicial = $guardado ? 'Guardado · Reemplazar' : 'Añadir';
                            @endphp
                            <div class="doc-item">
                                <div class="doc-icon-text">
                                    <svg viewBox="0 0 24 28" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 2h10l6 6v18a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"></path>
                                        <path d="M14 2v6h6"></path>
                                        <line x1="6" y1="14" x2="18" y2="14"></line>
                                        <line x1="6" y1="18" x2="18" y2="18"></line>
                                        <line x1="6" y1="22" x2="14" y2="22"></line>
                                    </svg>
                                    <span class="doc-text">{{ $info[1] }}</span>
                                </div>

                                @if ($guardado)
                                    <a href="{{ route('documentos.ver', $campo) }}" target="_blank" rel="noopener">Ver</a>
                                @endif

                                <label class="btn-añadir {{ $guardado ? 'btn-cargado' : '' }}"
                                       data-guardado="{{ $guardado ? '1' : '0' }}">
                                    <input type="file" name="{{ $campo }}" accept="application/pdf,.pdf" hidden>
                                    <span class="btn-text">{{ $textoInicial }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Columna 2: Documentos Probatorios -->
                <div class="form-column probatorios-col">
                    <h3 class="col-header">Documentos Probatorios</h3>
                    <div class="link-input-group">
                        <label for="drive-link">Link</label>
                        <input type="url" id="drive-link" name="drive_link"
                               value="{{ old('drive_link', $docs->doan_url_documentos_probatorios ?? '') }}"
                               placeholder="https://drive.google.com/...">
                    </div>
                </div>
            </div>

            <!-- Botones de Navegación -->
            <div class="form-actions">
                <button type="button" class="btn-registro" onclick="window.location.href='{{ route('formacion') }}'">Anterior</button>
                <button type="submit" class="btn-registro">Guardar documentos</button>
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
                const yaGuardado = label.dataset.guardado === '1';

                const restaurar = function () {
                    if (yaGuardado) {
                        label.classList.add('btn-cargado');
                        text.textContent = 'Guardado · Reemplazar';
                    } else {
                        label.classList.remove('btn-cargado');
                        text.textContent = 'Añadir';
                    }
                };

                if (this.files && this.files.length > 0) {
                    const file = this.files[0];
                    const isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');

                    if (isPdf) {
                        label.classList.add('btn-cargado');
                        text.textContent = 'Carga Completa';
                    } else {
                        this.value = '';
                        restaurar();
                        alert('Solo se permiten archivos PDF.');
                    }
                } else {
                    restaurar();
                }
            });
        });
    });
</script>

</body>
</html>