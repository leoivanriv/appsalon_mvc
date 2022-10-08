<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router) {

        
        $alertas = [];

        $auth = new Usuario;

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin(); 

            if(empty($alertas)){
                //Comprobar que exista el usuario
                $usuario = Usuario::where('email', $auth->email);

            if($usuario){
                //Verificar el password
                if($usuario->comprobarPasswordAndVerificado($auth->password)) {
                    //Autenticar el usuario
                    session_start();
                    $_SESSION['id'] = $usuario->id;
                    $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                    $_SESSION['email'] = $usuario->email;
                    $_SESSION['login'] = true;

                    //Redireccionamiento

                    if($usuario->admin === "1"){
                        $_SESSION['admin'] =  $usuario->admin ?? null;

                        header('Location: /admin');
                    }else{
                        header('Location: /cita');
                    }

                }
            }else{
                Usuario::setAlerta('error', 'Usuario no encontrado');
            }
        }
    }

        $alertas = Usuario::getAlertas();
        
        $router->render('auth/login', [
            'alertas' => $alertas,
            'auth'=> $auth
        ]);
    }

    public static function logout() {
        //session_start();
        //debuguear($_SESSION);

        $_SESSION = [];

        //debuguear($_SESSION);

        header('Location: /');

        
    }

    public static function olvide(Router $router) {
        $alertas =  [];

        if($_SERVER['REQUEST_METHOD']==='POST'){
            //Mensaje de validacion
            $auth = new Usuario($_POST);
            $alertas = $auth -> validarEmail();

            //
            if(empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);

                if($usuario && $usuario->confirmado === "1") {
                    //Generar Tocken de un solo uso
                    $usuario->crearToken();
                    //Guardarlo en la BD
                    $usuario->guardar();

                    //Enviar el email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarInstrucciones();

                    //Mensaje de alerta de envio exitoso
                    Usuario::setAlerta('exito', 'Instrucciones enviadas por E-mail');

                }else{
                    //Mensaje de alerta cuando no existe usuario o no está confirmado
                    Usuario::setAlerta('error', 'El Usuario no existe o no está confirmado');

                }
            }
        }

        $alertas =  Usuario::getAlertas();
        $router->render('auth/olvide-password',[
            'alertas'=>$alertas
            
        ]);
    }

    public static function reestablecer(Router $router) {
        
        $alertas =  [];
        $error = false;

        $token = ($_GET['token']);

        //Buscar usuario por via del token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token no valido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD']==='POST') {
            //Leer el nuevo password y guardarlo
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)) {
                $usuario-> password = null;

                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->guardar();

                if($resultado){
                    header('Location: /');
                }

            }

        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/reestablece-password',[
            'alertas' => $alertas,
            'error' => $error 
        ]);

    }

    public static function crear(Router $router) {

        $usuario = new Usuario;
        //Alertas vacias
        $alertas =  [];
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            //Revisar que alertas esté vacio

            if(empty($alertas)){
                // Verificar que el usuario no esté registrado
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                }else{
                    //Hashear el Password
                    $usuario->hashPassword();

                    //Generar Token Unico

                    $usuario-> crearToken();

                    //Enviar el Email

                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();

                    //Crear el usuario

                    $resultado = $usuario->guardar();
                    if($resultado) {
                        header('Location: /mensaje');
                    }
                } 
            }
        }

        $router->render('auth/crear-cuenta', [
            'usuario'=>$usuario,
            'alertas'=>$alertas

        ]);
    }

    public static function mensaje(Router $router){

        $router->render('auth/mensaje');

    }

    public static function confirmar(Router $router) {
        $alertas=[];

        $token = s($_GET['token']);

        $usuario = Usuario::Where('token', $token);

        if(empty($usuario)){
            //mostrar mensaje de error
            Usuario::setAlerta('error', 'Token No Valido'); 
        }else {
            //Modificar el usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = '';
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Comprobada Exitosamente');
        }
        
        //Obtener alertas
        $alertas = Usuario::getAlertas();

        //Rederizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);

    }

}