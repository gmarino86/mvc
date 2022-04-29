<?php

namespace DaVinci\Controllers;

use DaVinci\Auth\Auth;
use DaVinci\Core\App;
use DaVinci\Core\View;
use DaVinci\Models\Genero;
use DaVinci\Models\User;
use Davinci\Storage\Session;
use DaVinci\Validation\Validator;

class AuthController
{
    /**
     *  Muestra la pantalla de Login
     */
    public function login(){
        View::render('auth/login');
    }

    /**
     * Function que realiza la acción del login
     */
    public function loginDo(){
        $validator = new Validator($_POST, [
            'email'     => ['required'],
            'password'  => ['required']
        ]);

        if(!$validator->passes()) {
            Session::set('error','Ocurrieron errores de validación');
            Session::set('errores',$validator->getErrores());
            Session::set('old_data',$_POST);

            App::redirect('login');
        }

        $email      = $_POST['email'];
        $password   = $_POST['password'];

        $auth = new Auth();
        if($auth->login($email, $password)) {
            Session::set('exito','Iniciaste sesión con éxito.');
            App::redirect('');
        } else {
            Session::set('error','Las credenciales no coinciden con nuestros registros.');
            Session::set('errores',$validator->getErrores());
            Session::set('old_data',$_POST);
            App::redirect('login');
        }
    }

    /**
     *  Muestra la pantalla de Login
     */
    public function register(){
        if(Session::has('old_data')){
            $old_data = Session::get('old_data');
            Session::delete('old_data');
        } else {
            $old_data = [];
        }
        if(Session::has('errores')){
            $errores = Session::get('errores');
            Session::delete('errores');
        } else {
            $errores = [];
        }
        if(Session::has('error')){
            $error = Session::get('error');
            Session::delete('error');
        } else {
            $error = [];
        }

        $generos = (new Genero())->traerTodo();
        View::render('auth/register', compact(['errores'],['generos'],['old_data'],['error']));

    }

    /**
     * Function que realiza la acción del login
     */
    public function registerDo(){
        $validator = new Validator($_POST, [
            'name'      => ['required','min:1'],
            'last_name' => ['required','min:1'],
            'email'     => ['required','min:1'],
            'password'  => ['required','min:1'],
            'gender_id' => ['required','min:1']
        ]);

        if(!$validator->passes()) {
            Session::set('error','Ocurrieron errores de validación');
            $error = Session::get('error');
            Session::set('errores', $validator->getErrores());
            $errores = Session::get('errores');
            Session::set('old_data', $_POST);
            $old_data = Session::get('old_data');

            $generos = (new Genero())->traerTodo();
            View::render('auth/register', compact(['generos'],['error'],['errores'],['old_data']));
            exit;
        }

        $name       = $_POST['name'];
        $last_name  = $_POST['last_name'];
        $email      = $_POST['email'];
        $password   = $_POST['password'];
        $gender_id  = $_POST['gender_id'];

        $user = new User();
        $exito = $user->createUser([
            'name'          =>  $name,
            'last_name'     =>  $last_name,
            'email'         =>  $email,
            'password'      =>  $password,
            'gender_id'     =>  $gender_id
        ]);
        if($exito) {
            $auth = new Auth();
            if($auth->login($email, $password)) {
                Session::set('exito','Usuario creado con éxito.');
                App::redirect('');
            } else {
                Session::set('error','Error al loguear el usuario.');
                Session::set('errores',$validator->getErrores());
                Session::set('old_data',$_POST);
                App::redirect('register');
            }
        } else {
            Session::set('error', 'Error al crear el Usuario ');
            App::redirect('register');
        }
        exit;




    }



    public function logout(){
        $auth = new Auth();
        $auth->logout();

        $_SESSION['exito'] = 'Cerraste sesión con éxito.';
        App::redirect('');
    }
}