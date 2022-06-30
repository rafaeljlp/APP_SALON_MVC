<?php

namespace Controllers;

use MVC\Router;
use Model\Servicio;

class ServicioController {

    public static function index(Router $router) {
        
        iniciaSesion();

        isAdmin(); // Proteger las rutas revisa la sesión iniciada sino es administrador se envía a loguearse de nuevo

        $servicios = Servicio::all(); // retorna todos registros de la BD

        $router->render('servicios/index', [
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios
        ]);
    }

    public static function crear(Router $router) {

        iniciaSesion();

        isAdmin(); // Proteger las rutas revisa la sesión iniciada sino es administrador se envía a loguearse de nuevo

        $servicio = new Servicio; // instancia vacía para pasarla a la vista para que no marque un error de undefined

        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $servicio->sincronizar($_POST); // el objeto que se tiene en memoria los sincroniza con los datos del POST

            $alertas = $servicio->validar();

            if( empty($alertas) ) { // Si el arreglo de alertas esta vacío:
                $servicio->guardar();
                header('Location: /servicios');
            }

        }

        $router->render('servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);

    }

    public static function actualizar(Router $router) {

        iniciaSesion();

        isAdmin(); // Proteger las rutas revisa la sesión iniciada sino es administrador se envía a loguearse de nuevo

        // is_numeric cuando es true retorna 1 y cuando es false retorma cero (0)
        if(!is_numeric($_GET['id'])) return; 

        $servicio = Servicio::find($_GET['id']); // busca el id servicio que se quiere actualizar y lo muestra en pantalla

        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if(empty($alertas)) {
                $servicio->guardar();
                header('Location: /servicios');
            }
        }

        $router->render('servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function eliminar() { // no requiere router porque solo va a leer el id q se va a eliminar

        iniciaSesion();

        isAdmin(); // Proteger las rutas revisa la sesión iniciada sino es administrador se envía a loguearse de nuevo

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $servicio = Servicio::find($id);
            $servicio->eliminar();
            header('Location: /servicios');
        }        
    }
}

