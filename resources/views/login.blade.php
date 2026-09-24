<?php
?>
@include('partials.header')
<main class="login-container">
    <div class="image-section">
        <img src="{{ asset('img/ANFECA.jpeg') }}" alt="ANFECA Asociación Nacional de Facultades y Escuelas de Contaduría y Administración">
    </div>
    
    <div class="form-section">
        <form class="login-form">
            <div class="form-group">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" placeholder="">
            </div>
            
            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" placeholder="" required>
            </div>
            
            <div class="form-links">
                <a href="/datos">Registrarse</a>
                <a href="/recuperar">Recuperar contraseña</a>
            </div>
            
            <div class="button-group">
                <button type="button" class="btn" onclick="window.location.href='/datos'">Guía Paso a Paso</button>
                <button type="button" class="btn" onclick="window.location.href='/datos'">Iniciar Sesión</button>
            </div>
            
            <p class="login-notice">
                <span class="highlight-rojo">Aviso:</span> Si aún no completas tu registro inicial o vas a realizar un refrendo, inicia sesión con tu <span class="highlight-rojo">CORREO</span> y <span class="highlight-rojo">CONTRASEÑA</span> para continuar.
            </p>
        </form>
    </div>
</main>
@include('partials.footer')