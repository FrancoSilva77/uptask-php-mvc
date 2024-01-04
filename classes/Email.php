<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
  protected $nombre;
  protected $email;
  protected $token;

  public function __construct($nombre, $email, $token)
  {
    $this->nombre = $nombre;
    $this->email = $email;
    $this->token = $token;
  }

  public function enviarConfirmacion()
  {
    // Crear el objeto de email
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host       = $_ENV['EMAIL_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Port       = $_ENV['EMAIL_PORT'];
    $mail->Username   = $_ENV['EMAIL_USER'];
    $mail->Password   = $_ENV['EMAIL_PASS'];

    $mail->setFrom('uptask@uptask.com');
    $mail->addAddress($this->email, $this->nombre);
    $mail->Subject = 'Confirma tu cuenta';

    // set HTML
    $mail->isHTML(TRUE);
    $mail->CharSet = 'UTF-8';

    $contenido = "<html>";
    $contenido .= "<p><strong>Hola " . $this->nombre . " </strong> Has creado tu cuenta en UpTask, solo debes confirmarla presionando el siguiente enlace</p>";
    $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['HOST'] . "/confirmar?token=" . $this->token . "'>Confirmar Cuenta</a>";
    $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
    $contenido .= "</html>";

    $mail->Body = $contenido;

    // Enviar el mail
    $mail->send();
  }

  public function enviarInstrucciones()
  {
    // Crear el objeto de email
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host       = $_ENV['EMAIL_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Port       = $_ENV['EMAIL_PORT'];
    $mail->Username   = $_ENV['EMAIL_USER'];
    $mail->Password   = $_ENV['EMAIL_PASS'];

    $mail->setFrom('uptask@uptask.com');
    $mail->addAddress($this->email, $this->nombre);
    $mail->Subject = 'Reestablece tu contraseña';

    // Set HTML
    $mail->isHTML(TRUE);
    $mail->CharSet = 'UTF-8';


    $contenido = "<html>";
    $contenido .= "<p><strong>Hola " . $this->nombre . " </strong> Has solicitado reestablecer tu contraseña, sigue el siguiente enlace para hacerlo.</p>";
    $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['HOST'] . "/reestablecer?token=" . $this->token . "'>Reestablecer Contaseña</a>";
    $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
    $contenido .= "</html>";

    $mail->Body = $contenido;

    // Enviar el mail
    $mail->send();
  }
}
