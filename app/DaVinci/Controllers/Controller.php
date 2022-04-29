<?php

namespace DaVinci\Controllers;

use DaVinci\Auth\Auth;
use DaVinci\Core\App;
use Davinci\Storage\Session;

class Controller
{
    /** @var Auth */
    protected $auth;

    public function __construct()
    {
        $this->auth = new Auth;
    }

    /**
     * Verifica que el usuario esté autenticado.
     * De no estarlo, lo redirecciona al form de login.
     */
    protected function requiresAuth()
    {
        if(!$this->auth->isAuthenticated()) {
            Session::set('error','Tenés que iniciar sesión para poder realizar esta acción.');
            App::redirect('login');
        }
    }
}
