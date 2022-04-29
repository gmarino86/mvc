<?php
namespace DaVinci\Auth;

use DaVinci\Models\User;
use Davinci\Storage\Session;

class Auth
{
    /**
     * Intenta autenticar al usuario.
     * Si tiene éxito, retorna true.
     * De lo contrario, retorna false.
     *
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function login(string $email, string $password): bool
    {
        // Buscamos el usuario por su email.
        $user = new User;
        $user = $user->userByEmail($email);

        // Verificamos si hay un usuario.
        if($user !== null) {
            // Comparamos los passwords.
            if(password_verify($password, $user->getPassword())) {
                $this->setAsAuthenticated($user);
                return true;
            }
        }
        return false;
    }

    /**
     * Marca el $user como autenticado.
     *
     * @param User $user
     */
    public function setAsAuthenticated(User $user): void
    {
        Session::set('id',$user->getId());
    }

    /**
     * Desautentica al usuario.
     */
    public function logout(): void
    {
        Session::delete('id');

    }

    /**
     * Retorna si el usuario está autenticado o no.
     *
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return Session::has('id');
    }

    /**
     * Retorna el usuario autenticado.
     * Si no está autenticado, retorna null.
     *
     * @return User|null
     */
    public function getUser()
    {
        if(!$this->isAuthenticated()) {
            return null;
        }

        $usuario = new User;
        return $usuario->getUserById(Session::get('id'));
    }
}
