<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;


class LoginController {

    public static function login(Router $router) {
        $alertas = [];        

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $auth = new Usuario($_POST);            

            $alertas = $auth->validarLogin();            

            if(empty($alertas)) {
                // echo "El usuario agrego tanto email como password";

                // Comprobar que exista el usuario
                $usuario = Usuario::where('email', $auth->email);

                if($usuario) {
                    // Verificar el password
                    if( $usuario->comprobarPasswordAndVerificado($auth->password) ) {
                        // Autenticar al Usuario (con variables de sesión)
                        iniciaSesion(); // se valida que ya la sesión no este abierta para evitar un error                            

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionamiento
                        if($usuario->admin === "1") {

                            $_SESSION['admin'] = $usuario->admin ?? null;

                            header('Location: /admin');

                        } else {
                            header('Location: /cita');
                        }
                    }
                } else {
                    Usuario::setAlerta('error' , 'Usuario no encontrado');
                }
            } 
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
           'alertas' => $alertas
        ]);
    }

    public static function logout() {
        // validar que la sesión no este abierta y evitar un error
        iniciaSesion();

        $_SESSION = [];

        header('Location: /');
    }

    public static function olvide(Router $router) {

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);

                if($usuario && $usuario->confirmado === "1") {

                    // Generar un Token
                    $usuario->crearToken();
                    $usuario->guardar();

                    // Enviar el Email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarInstrucciones();

                    // Alerta de exito
                    Usuario::setAlerta('exito', 'Revisa tu email');
                } else {
                    Usuario::setAlerta('error', 'El usuario no Existe o no confirmado'); // SET para Agregar alertas                    
                }
            }
        }

        $alertas = Usuario::getAlertas(); // GET para Obtener alertas

        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router) {
        // arreglo vaciío se va llenando de acuerdo a ciertas acciones
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);

        // Buscar usuario por su token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token No Válido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Leer el nuevo password y guardarlo

            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)) {

                $usuario->password = null;

                // se toma de la instancia de password el password y se le asigna al usuario
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = '';

                $resultado = $usuario->guardar();
                if($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router) {

        $usuario = new Usuario($_POST);

        // Alertas vacías
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta(); // llena el arreglo vacío con la validación

            // Revisar que alerta este vacío
            if(empty($alertas)) {
                // Verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hashear el Password
                    $usuario->hashPassword();

                    // Generar un Token único
                    $usuario->crearToken();

                    // Enviar el Email
                    $email = new Email($usuario->nombre , $usuario->email , $usuario->token);                    
                    $email->enviarConfirmacion();

                    // Crear el usuario
                    $resultado = $usuario->guardar();

                    // debuguear($usuario);

                    if($resultado) {
                        header('Location: /mensaje');
                    } 
                    
                }
            }
        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas // se deben pasar las alertas a la vista para que se muestren
        ]);
    }

    public static function mensaje(Router $router) {

        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router) {
        $alertas = [];

        $token = s($_GET['token']);

        $usuario = Usuario::where('token' , $token);

        
        if(empty($usuario)) {
            // Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no válido');
        } else {
            // Modificar a usuario confirmado            
            $usuario->confirmado = '1';
            $usuario->token='';
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');
        }

        // Obtener Alertas: las alertas que se están guardadndo en memoria se puedan leer antes de renderizar la vista
        $alertas = Usuario::getAlertas();

        // Renderizar la vista: si se confirmó correctamente o si el token no es válido
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}