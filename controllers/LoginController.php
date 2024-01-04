<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{
  public static function login(Router $router)
  {

    $alertas = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $usuario = new Usuario($_POST);

      $alertas = $usuario->validarLogin();

      if (empty($alertas)) {
        // Verificar que el usuario exista
        $usuario = Usuario::where('email', $usuario->email);

        if (!$usuario || !$usuario->confirmado) {
          Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
        } else {
          // El usuario Existe
          if (password_verify($_POST['password'], $usuario->password)) {
            // Iniciar la sesión del usuario

            session_start();
            $_SESSION['id'] = $usuario->id;
            $_SESSION['nombre'] = $usuario->nombre;
            $_SESSION['email'] = $usuario->email;
            $_SESSION['login'] = true;

            // Redireccionar
            header('Location: /dashboard');


            debuguear($_SESSION);
          } else {
            Usuario::setAlerta('error', 'Contraseña Incorrecta');
          }
        }

        // debuguear($usuario);
      }
    }

    $alertas = Usuario::getAlertas();

    // Render a la vista
    $router->render('auth/login', [
      'titulo' => 'Iniciar Sesión',
      'alertas' => $alertas
    ]);
  }

  public static function logout()
  {
    session_start();
    $_SESSION = [];
    header('Location: /');
  }

  public static function crear(Router $router)
  {
    $alertas = [];
    $usuario = new Usuario;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $usuario->sincronizar($_POST);
      $alertas = $usuario->validarCuentaNueva();

      $existeUsuario = Usuario::where('email', $usuario->email);

      if (empty($alertas)) {
        if ($existeUsuario) {
          Usuario::setAlerta('error', 'El usuario ya esta registrado');
          $alertas = Usuario::getAlertas();
        } else {
          // Hashear el password
          $usuario->hashPassword();

          // Eliminar password2
          unset($usuario->password2);

          // Generar el token
          $usuario->crearToken();

          // Enviar el email
          $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
          $email->enviarConfirmacion();

          // Crear un nuevo usuario
          $resultado = $usuario->guardar();

          if ($resultado) {
            header('Location: /mensaje');
          }
        }
      }
    }

    // Render a la vista
    $router->render('auth/crear', [
      'titulo' => 'Crear cuenta',
      'usuario' => $usuario,
      'alertas' => $alertas
    ]);
  }

  public static function olvide(Router $router)
  {

    $alertas = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $usuario = new Usuario($_POST);
      $alertas = $usuario->validarEmail();

      if (empty($alertas)) {
        // Buscar el usuario
        $usuario = Usuario::where('email', $usuario->email);

        if ($usuario && $usuario->confirmado) {
          // Generar un nuevo token
          $usuario->crearToken();
          unset($usuario->password2);

          // Actualizar el usuario
          $usuario->guardar();

          // Enviar el email
          $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
          $email->enviarInstrucciones();

          // Imprimir la alerta
          Usuario::setAlerta('exito', 'Se han enviado las instrucciones a tu email');
        } else {
          Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
        }
      }
    }
    $alertas = Usuario::getAlertas();

    // Muestra la vista
    $router->render('auth/olvide', [
      'titulo' => 'Olvide mi contraseña',
      'alertas' => $alertas
    ]);
  }

  public static function reestablecer(Router $router)
  {
    $token = s($_GET['token']);
    $mostrar = true;


    if (!$token) header('Location: /');

    // Identificar el usuario con este token
    $usuario = Usuario::where('token', $token);

    if (empty($usuario)) {
      Usuario::setAlerta('error', 'Token No Válido');
      $mostrar = false;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      // Añadir el nuevo password
      $usuario->sincronizar($_POST);

      // Validar Contraseña
      $alertas = $usuario->validarPassword();

      if (empty($alertas)) {
        // Hashear en nuevo Password
        $usuario->hashPassword();

        // Eliminar el token
        $usuario->token = null;

        // Guardar el usuario
        $resultado = $usuario->guardar();

        // Redireccionar

        if ($resultado) {
          header('Location: /');
        }
      }
    }

    $alertas = Usuario::getAlertas();

    // Muestra la vista
    $router->render('auth/reestablecer', [
      'titulo' => 'Reestablecer Contraseña',
      'alertas' => $alertas,
      'mostrar' => $mostrar
    ]);
  }

  public static function mensaje(Router $router)
  {
    $router->render('auth/mensaje', [
      'titulo' => 'Cuenta creada Exitosamente'
    ]);
  }

  public static function confirmar(Router $router)
  {

    $token = s($_GET['token']);

    if (!$token) header('Location: /');

    // Encontrar al usuario con este token
    $usuario = Usuario::where('token', $token);

    if (empty($usuario)) {
      // No hay un usuario con ese token
      Usuario::setAlerta('error', 'Token No Válido');
    } else {
      // Confirmar la cuenta
      $usuario->confirmado = 1;
      $usuario->token = null;
      unset($usuario->password2);

      // Guardar en la base de datos
      $usuario->guardar();
      Usuario::setAlerta('exito', 'Cuenta Confirmada Correctamente');
    }

    $alertas = Usuario::getAlertas();

    $router->render('auth/confirmar', [
      'titulo' => 'Confirma tu cuenta en Uptask',
      'alertas' => $alertas
    ]);
  }
}
