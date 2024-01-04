<div class="contenedor crear">

  <?php include_once __DIR__ . '/../templates/nombre-sitio.php' ?>

  <div class="contenedor-sm">

    <?php include_once __DIR__ . '/../templates/alertas.php' ?>

    <p class="descripcion-pagina">Crea tu cuenta en UpTask</p>

    <form class="formulario" method="POST" action="/crear">

      <div class="campo">
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" placeholder="Tu nombre" value="<?php echo $usuario->nombre ?>">
      </div>

      <div class="campo">
        <label for="email">Correo Electronico</label>
        <input type="email" name="email" id="email" placeholder="Tu correo" value="<?php echo $usuario->email ?>">
      </div>


      <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="Tu contraseña">
      </div>

      <div class="campo">
        <label for="password2">Repite Contraseña</label>
        <input type="password" name="password2" id="password2" placeholder="Repite tu contraseña">
      </div>

      <input type="submit" class="boton" value="Crear Cuenta">
    </form>

    <div class="acciones">
      <a href="/">¿Ya tienes cuenta? Iniciar Sesión</a>
      <a href="/olvide">¿Olviaste tu password?</a>
    </div>

  </div> <!-- .contenedor-sm-->
</div>