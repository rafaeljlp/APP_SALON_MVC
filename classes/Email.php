<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

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
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '0acaf3d38550b4';
        $mail->Password = '0b59277437f7b4';

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com' , 'AppSalon.com');
        $mail->Subject = "Confirma tu Cuenta";

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola " . $this->email . "</strong> Has creado tu cuenta con AppSalon,
        solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .=  "<p>Presiona aquí: <a href='http://localhost:3000/confirmar-cuenta?token="
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
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '0acaf3d38550b4';
        $mail->Password = '0b59277437f7b4';

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com' , 'AppSalon.com');
        $mail->Subject = "Reestablece tu password";

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has solicitado 
        reestablecer tu password, sigue el siguiente enlace para hacerlo</p>";
        $contenido .=  "<p>Presiona aquí: <a href='http://localhost:3000/recuperar?token="
        . $this->token . "'>Reestablecer Password</a> </p>";
        $contenido .=  "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>"; 
        $contenido .= '</html>';
        $mail->Body = $contenido;

        $mail->send();        

    }

}