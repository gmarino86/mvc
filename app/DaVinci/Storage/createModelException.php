<?php

namespace Davinci\Storage;

use Throwable;

/**
 *
 * Las clases de exceptions tienen como requisito heredar de la clase Exception, o al menos, implementar
 * la interfaz Throwable de php.
 *
 * No requieren tener su propio contenido, puede ser suficiente con lo que heredamos de Exception.
 *
 * @package Davinci\Storage
 */
class createModelException extends \Exception
{
    /**
     *
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 8000, Throwable $previous = null)
    {
        if($message === "") {
            $message = 'No se pudo realizar el crear del modelo';
        }
        parent::__construct($message, $code, $previous);
    }


}
