@include('partials.header')

<main class="certification-container">
    <!-- Indicador de Usuario (Esquina superior derecha) -->
    <div class="user-badge-container">
        <div class="user-badge">
            <img src="{{ asset('img/edit-icon.png') }}" alt="Editar" class="icon-sm">
            <img src="{{ asset('img/user-avatar.png') }}" alt="Usuario" class="icon-md">
            <span>Usuario: Victor Godínez Paredes</span>
        </div>
    </div>

    <!-- Sección del Formulario -->
    <div class="form-section">
        <form action="{{ route('certificacion.guardar') }}" method="POST" class="certification-form">
            @csrf
            
            <h2 class="form-title">Certificación</h2>
            <hr class="title-line">

            @if (session('status'))
                <p role="status">{{ session('status') }}</p>
            @endif

            <!-- Campo 1: Cambia de etiqueta dependiendo de la selección -->
            <div class="form-group center-group">
                <label id="label_refrendo" for="no_refrendo">No. de Refrendo Asignado</label>
                <input type="text" id="no_refrendo" name="no_refrendo" value="5000" readonly class="input-readonly">
            </div>

            <!-- Campo 2 -->
            <div class="form-group center-group">
                <label for="refrendo_actual">Refrendo actual</label>
                <input type="text" id="refrendo_actual" name="refrendo_actual" value="Refrendo número 2" readonly class="input-readonly">
            </div>

            <!-- Pregunta Art. 14 -->
            <div class="form-group center-group">
                <label>¿Corresponde a Refrendo por Art. 14?</label>
                <div class="radio-options">
                    <label class="radio-label">
                        Sí <input type="radio" name="aplica_art_14" value="si" id="art_14_si" required @checked(old('aplica_art_14', session('certificacion.aplica_art_14')) === 'si') onclick="toggleInciso()">
                    </label>
                    <label class="radio-label">
                        No <input type="radio" name="aplica_art_14" value="no" id="art_14_no" @checked(old('aplica_art_14', session('certificacion.aplica_art_14')) === 'no') onclick="toggleInciso()">
                    </label>
                </div>
                @error('aplica_art_14')
                    <p role="alert">{{ $message }}</p>
                @enderror
            </div>

            <!-- Select Condicional (Oculto por defecto) -->
            <div class="form-group center-group hidden" id="contenedor_inciso">
                <label for="inciso_art_14">Inciso Art. 14</label>
                <select id="inciso_art_14" name="inciso_art_14" class="input-select">
                    <option value="" selected disabled></option>
                    <option value="14.1" @selected(old('inciso_art_14', session('certificacion.inciso_art_14')) === '14.1')>14.1 -</option>
                    <option value="14.2" @selected(old('inciso_art_14', session('certificacion.inciso_art_14')) === '14.2')>14.2 -</option>
                    <option value="14.3" @selected(old('inciso_art_14', session('certificacion.inciso_art_14')) === '14.3')>14.3 -</option>
                    <option value="14.4" @selected(old('inciso_art_14', session('certificacion.inciso_art_14')) === '14.4')>14.4 -</option>
                </select>
                @error('inciso_art_14')
                    <p role="alert">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botones de Acción -->
            <div class="button-group space-between">
                <!-- Se asume que "Anterior" regresa a la vista previa -->
                <button type="button" class="btn btn-red" onclick="window.history.back()">Anterior</button>
                <button type="submit" class="btn btn-red">Siguiente</button>
            </div>
        </form>
    </div>
</main>

<!-- Script para manejar el dinamismo de las capturas -->
<script>
    function toggleInciso() {
        const radioSi = document.getElementById('art_14_si');
        const contenedorInciso = document.getElementById('contenedor_inciso');
        const incisoSelect = document.getElementById('inciso_art_14');
        const labelRefrendo = document.getElementById('label_refrendo');

        if (radioSi.checked) {
            // Muestra el select y cambia la etiqueta a Folio (Captura 4)
            contenedorInciso.classList.remove('hidden');
            incisoSelect.required = true;
            labelRefrendo.innerText = "No. de Folio de Certificación";
        } else {
            // Oculta el select y regresa la etiqueta a Refrendo (Capturas 1, 2 y 3)
            contenedorInciso.classList.add('hidden');
            incisoSelect.required = false;
            labelRefrendo.innerText = "No. de Folio de Certificación";
        }
    }

    toggleInciso();
</script>

@include('partials.footer')