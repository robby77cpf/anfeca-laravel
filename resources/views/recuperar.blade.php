<?php
// Inicializar variable para mostrar mensajes al usuario
$mensaje_estado = "";

// Comprobar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y limpiar los datos
    $curp = htmlspecialchars(trim($_POST['curp']));
    $correo = filter_var(trim($_POST['correo']), FILTER_SANITIZE_EMAIL);

    // Validar el correo electrónico
    if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        
        // --- CONFIGURACIÓN DEL CORREO ---
        $destinatario = $correo;
        $asunto = "Recuperación de Contraseña - ANFECA";
        $mensaje = "correo de prueba"; // Texto solicitado
        
        // Cabeceras básicas para el envío de correo
        $cabeceras = "From: noreply@anfeca.example.com\r\n";
        $cabeceras .= "Reply-To: noreply@anfeca.example.com\r\n";
        $cabeceras .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $cabeceras .= "X-Mailer: PHP/" . phpversion();

        // Intentar enviar el correo
        if (mail($destinatario, $asunto, $mensaje, $cabeceras)) {
            $mensaje_estado = "<div class='rec-mensaje-sistema' style='color: #2e7d32; background-color: #e8f5e9;'>El correo de recuperación se ha enviado exitosamente a: $correo</div>";
        } else {
            $mensaje_estado = "<div class='rec-mensaje-sistema' style='color: #c62828; background-color: #ffebee;'>Ocurrió un error al intentar enviar el correo. Por favor, inténtelo de nuevo más tarde.</div>";
        }
    } else {
        $mensaje_estado = "<div class='rec-mensaje-sistema' style='color: #c62828; background-color: #ffebee;'>Por favor, ingrese un correo electrónico válido.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña - ANFECA</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">    
</head>
<body>

    <!-- Encabezado -->
@include('partials.header')

    <!-- Contenido Principal -->
    <main class="rec-main">
        
        <form class="rec-form" action="" method="POST">
@csrf
            
            <!-- Mostrar mensaje de éxito o error si existe -->
            <?php if (!empty($mensaje_estado)) echo $mensaje_estado; ?>

            <!-- Título con la línea inferior -->
            <div class="rec-header-container">
                <h2 class="rec-title">Recuperación de Contraseña</h2>
            </div>
            
            <!-- Campos de entrada -->
            <div class="rec-inputs-container">
                <div class="rec-input-group">
                    <label for="curp">CURP</label>
                    <input type="text" id="curp" name="curp" required>
                </div>
                
                <div class="rec-input-group">
                    <label for="correo">Correo Electrónico</label>
                    <input type="email" id="correo" name="correo" required>
                </div>
            </div>

            <!-- Nota Informativa -->
            <p class="rec-note">
                <span class="rec-highlight">Nota:</span> Se enviará un <span class="rec-highlight">enlace de generación de nueva contraseña</span> con base a los datos ingresados.
            </p>

            <!-- Botones de Navegación -->
            <div class="rec-actions">
                <button type="button" class="rec-btn" onclick="window.location.href='{{ route('index') }}'">Anterior</button>
                <button type="submit" class="rec-btn">Siguiente</button>
            </div>
        </form>

    </main>

    <!-- Pie de página -->
@include('partials.footer')

</body>
</html>