<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

use Dotenv\Dotenv as Dotenv;
$dotenv = Dotenv::createImmutable('../includes/.env');
$dotenv->safeLoad();


class Email {

    public $nombre;
    public $email;    
    public $token;
    
    public function __construct($nombre , $email , $token) {
        $this->nombre = $nombre;
        $this->email = $email;        
        $this->token = $token;
    }

    public function enviarConfirmacion() {

        // Crear el objeto de Email                
        $mail = new PHPMailer();
        /*
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '25cafcb6481ae0';
        $mail->Password = '6d471064c22bf8';
        */
        // Configurar SMTP
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USER'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = $_ENV['MAIL_PORT'];

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com' , 'AppSalon.com');
        $mail->Subject = "Confirma tu Cuenta";

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola " . $this->email . "</strong> Has creado tu cuenta con AppSalon,
        solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .=  "<p>Presiona aquí: <a href='" . $_ENV['SERVER_HOST'] . "confirmar-cuenta?token="
        . $this->token . "'>Confirmar Cuenta</a> </p>";
        $contenido .=  "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>"; 
        $contenido .= '</html>';
        $mail->Body = $contenido;

        $mail->send();
    }
    
    // no toma parametros porque cuando se esta instanciando los parametros se pasan en el constructor
    public function enviarInstrucciones() {

        // Crear el objeto de Email        
        $mail = new PHPMailer();
        /*
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '25cafcb6481ae0';
        $mail->Password = '6d471064c22bf8';
        */
        // Configurar SMTP
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USER'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = $_ENV['MAIL_PORT'];

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com' , 'AppSalon.com');
        $mail->Subject = "Reestablece tu password";

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has solicitado 
        reestablecer tu password, sigue el siguiente enlace para hacerlo</p>";
        $contenido .=  "<p>Presiona aquí: <a href='" . $_ENV['SERVER_HOST'] . "recuperar?token="
        . $this->token . "'>Reestablecer Password</a> </p>";
        $contenido .=  "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>"; 
        $contenido .= '</html>';
        $mail->Body = $contenido;

        $mail->send();        

    }

}