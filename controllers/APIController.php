<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController {

    public static function index() {

        $servicios = Servicio::all(); // Es un metódo estático en ActiveRecord no requiere instancearse con NEW

        echo json_encode($servicios);
    }

    public static function guardar() {
        
        // Almacena la cita y devuelve el ID
        $cita = new Cita($_POST);
        $resultado = $cita->guardar(); // insertar en la base de datos

        $id = $resultado['id'];        

        // Almacena los Servicios con el ID de la Cita
        $idServicios = explode(",", $_POST['servicios']);

        foreach($idServicios as $idServicio) {
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];            
            $citaServicio = new CitaServicio($args);            
            $citaServicio->guardar();
        }        
        echo json_encode(['resultado' => $resultado]); // ver el resultado de lo insertado en la BD
    }

    public static function eliminar(){
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $id = $_POST['id']; // se lee el id
            $cita = Cita::find($id); // se encuentra el id
            $cita->eliminar(); // se elimina el id
            header( 'Location:' . $_SERVER['HTTP_REFERER'] ); // redirecciona a la pagina de donde se venia
        }
    }
}