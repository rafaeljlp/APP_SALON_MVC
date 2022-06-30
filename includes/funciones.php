<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function esUltimo( string $actual, string $proximo ): bool {

    if($actual !== $proximo ) {
        return true;
    }
    return false;
}

// Función que revisa que el usuario este autenticado

// función helper para comprobar si el usuario esta autenticado sino lo manda a la página de login
function isAuth() : void { // void: no retorna nada
    if(!isset($_SESSION['login'])) { // sino esta definido como true:
        header('Location: /'); // manda al usuario a redireccionarse a la página ppal.
    }
}

function isAdmin() : void { // void: no retorna nada
    if( !isset( $_SESSION['admin'] ) ) {
        header('Location: /');
    }
}

function iniciaSesion() : void {

    if(!isset($_SESSION)) {       
        session_start();
    }

 }