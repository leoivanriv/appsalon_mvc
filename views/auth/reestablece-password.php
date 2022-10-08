<h1 class="nombrfe-pagina">Reetablece Password</h1>
<p class="descripcion-pagina">Coloca tu nuevo password a continuación</p>

<?php
    include_once __DIR__ . "/../templates/alertas.php";
?>

<?php
    if($error) return;
?>

<form class="formulario" method="POST"><!-- se elimina el action por que de lo contrario de eliminará el token -->
    <div class="campo">
        <label for="password">Password</label>
        <input 
            id="password"
            type="password"
            name="password"
            placeholder="Tu nuevo password"
        />
    </div>
    <input type="submit" value="Reestablece Password" class="boton">

</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Iniciar Sesión</a>
    <a href="/crear-cuenta">Crear cuenta</a>
</div>
