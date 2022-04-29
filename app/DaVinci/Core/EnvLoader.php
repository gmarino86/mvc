<?php

namespace DaVinci\Core;

// Se encarga de leer y almacenar los valores del archivo de entorno ".env".

/**
 *  Clase para manejo de Variables de Entorno
 */
class EnvLoader
{
    /** @var string Nombre del archivo de entorno. */
    private $name = ".env";

    /** @var array Lista de los datos de configuración leídos del entorno. */
    private $data = [];

    /**
     * EnvLoader constructor.
     *
     * @param string $path La ruta a la carpeta del archivo de configuración.
     */
    public function __construct(string $path = "")
    {
        $this->loadEnv($path . "/" . $this->name);
    }

    /**
     * @param string $file
     */
    private function loadEnv(string $file)
    {
        // Vamos a leer el archivo usando la función file().
        // Los flags que pasamos como segundo parámetro nos permiten limpiar poco los valores.
        // Como resultado, vamos a tener un línea en cada item del array obtenido como resultado
        // de file().
        $lineas = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach($lineas as $linea) {
            // Primero, trimmeamos la línea, y luego verificamos que no sea un comentario.
            // Es decir, que empiece con un "#".
            $linea = trim($linea);
            if(strpos($linea, '#') !== 0) {
                // Leemos la línea.
                $this->readLine($linea);
            }
        }
    }

    /**
     * Lee el contenido de la línea y lo guarda en los datos de entorno.
     *
     * @param string $linea
     */
    private function readLine(string $linea)
    {
        // Verificamos que haya un "=", que es lo que indica que es un valor, y que no sea el primer
        // caracter.
        $separador = strpos($linea, '=');
        if($separador <= 0) {
            return;
        }

        // Separamos los valores antes y después del =, usando substr y strpos.
        $clave = substr($linea, 0, $separador);
        $valor = substr($linea, $separador + 1);

        // Guardamos el valor :)
        $this->data[$clave] = $valor;
    }

    /**
     * @param string $key
     * @return string
     */
    public function getEnv(string $key): string
    {
        return $this->data[$key];
    }
}
