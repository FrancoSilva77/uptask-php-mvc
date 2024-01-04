<div class="contenedor olvide">

  <?php include_once __DIR__ . '/../templates/nombre-sitio.php' ?>
  
  <div class="contenedor-sm">

    <?php include_once __DIR__ . '/../templates/alertas.php' ?>
    
    <p class="descripcion-pagina">Recupera tu acceso a UpTask </p>

    <form class="formulario" method="POST" action="/olvide">
      <div class="campo">
        <label for="email">Correo Electronico</label>
        <input type="email" name="email" id="email" placeholder="Tu correo">
      </div>

      <input type="submit" class="boton" value="Enviar Instrucciones">
    </form>

    <div class="acciones">
      <a href="/">¿Ya tienes una cuenta? Iniciar Sesión</a>
      <a href="/crear">¿Aún no tienes una cuenta? Obtener una</a>
    </div>

  </div> <!-- .contenedor-sm-->
</div>