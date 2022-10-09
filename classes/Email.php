<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{

    public $nombre;
    public $email;
    public $token;

    public function __construct($nombre, $email, $token)
    {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->token = $token;

    }

    public function enviarConfirmacion() {

        //Crear el objeto de Email

        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '796a54487855d9';
        $mail->Password = 'a3fb194cf5433d';
        
        //Set HTML
        $mail -> isHTML(TRUE);
        $mail -> CharSet = 'UTF-8';

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress($this->email);
        $mail->Subject = 'Confirmar Cuenta';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola "  . $this->nombre . "</strong> Has creado tu cuenta en App Salon, solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .= "<p> Presiona aquí: <a href='http://warm-fjord-43843.herokuapp.com/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tu no solicitastes esta cuenta, favor de hacer caso omiso.</p>";
        $contenido .= "</html>";

        $mail -> Body = $contenido;

        //Enviar el mail

        $mail-> send();
    }

    public function enviarInstrucciones() {

        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '796a54487855d9';
        $mail->Password = 'a3fb194cf5433d';
        
        //Set HTML
        $mail -> isHTML(TRUE);
        $mail -> CharSet = 'UTF-8';

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Reestablece tu password';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola "  . $this->nombre . "</strong> Has solicitado reetablecer tu password, sigue el siguiente enlace para continuar.</p>";
        $contenido .= "<p> Presiona aquí: <a href='http://warm-fjord-43843.herokuapp.com/reestablecer?token=" . $this->token . "'>Reestablece contraseña</a></p>";
        $contenido .= "<p>Si tu no solicitastes esta cuenta, favor de hacer caso omiso.</p>";
        $contenido .= "</html>";

        $mail -> Body = $contenido;

        //Enviar el mail

        $mail-> send();
    }
}

