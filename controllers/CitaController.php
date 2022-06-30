<?php

namespace Controllers;

use MVC\Router;


class CitaController {
    public static function index( Router $router) {

        // validar que la sesión no este abierta y evitar un error
        iniciaSesion();

        isAuth(); // función helper para comprobar si el usuario esta autenticado sino lo manda a la página de login

        $router->render('cita/index', [
            'nombre' => $_SESSION['nombre'],
            'id' => $_SESSION['id']
        ]);
    }
}