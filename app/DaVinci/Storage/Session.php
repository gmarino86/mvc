<?php

namespace Davinci\Storage;

/**
 * Class Session
 * Clase wrapper para el manejo de sesiones.
 * Motivación: Si bien el uso de sesiones es algo bastante simple en php, no siempre vamos a querer
 * utilizar para esta funcionalidad la implementación nativa de php (sin mencionar que en el futuro
 * php podría cambiar esa implementación).
 *
 * @package Davinci\Storage
 */
class Session
{
    /**
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public static function get($key)
    {
        return self::has($key) ? $_SESSION[$key] : null;
    }

    /**
     * @param $key
     * @return bool
     */
    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * @param $key
     */
    public static function delete($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * @param $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public static function getAndForget($key, $default = null)
    {
        $value = self::get($key) ?? $default;
        self::delete($key);
        return $value;
    }
}
