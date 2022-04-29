<?php
/**
 * Retorna una ruta absoluta para la $url proporcionada.
 *
 * @param string|null $url
 * @return string
 */
function url(string $url = null) {
    return \DaVinci\Core\App::urlTo($url);
}

/**
 * Retorna el valor asociado a la ruta.
 *
 * @param string $clave
 * @return mixed
 */
function urlParam($clave) {
    return \DaVinci\Core\Route::getUrlParameters()[$clave];
}
