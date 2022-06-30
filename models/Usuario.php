<?php

namespace Model;

class Usuario extends ActiveRecord {
    // Base de Datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email',
    'password', 'telefono', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = [])  {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? null;
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? null;
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }
    // Mensajes de validación para la creación de una cuenta
    public function validarNuevaCuenta() {
        
        // ['error'] --> tipo de error    [] --> mensaje del tipo de error
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El nombre es Obligatorio';            
        }

        if(!$this->apellido) {
            self::$alertas['error'][] = 'El apellido es Obligatorio';            
        }

        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';            
        }

        if(!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';            
        }

        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe contener al menos 6 caracteres';            
        }       

        return self::$alertas;
    }

    public function validarLogin() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }

        if(!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }

        // retorna las alertas hacia el Controlador
        return self::$alertas;
    }

    public function validarEmail() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        return self::$alertas;
    }

    public function validarPassword() {
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password es obligatorio';
        }

        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe contener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    // Revisa si el usuario ya existe
    public function existeUsuario() {
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

        $resultado = self::$db->query($query);

        if($resultado->num_rows) {
            self::$alertas['error'][] = 'El usuario ya esta registrado';
        }

        return $resultado;
    }

    public function hashPassword() {
        $this->password = password_hash($this->password , PASSWORD_BCRYPT);
    }

    public function crearToken() {
        $this->token = uniqid(); // genera el token mediante una cadena de numéros y letras 
    }

    public function comprobarPasswordAndVerificado($password) {        

        // password_verify toma el password que ha introducido el usuario y el password de la BD
        $resultado = password_verify($password, $this->password);

        // debuguear($this);        
        // debuguear($resultado);
        
        if(!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = 'Password Incorrecto o tu cuenta no ha sido confirmada';
        } else {
            return true;
        }
    }

}