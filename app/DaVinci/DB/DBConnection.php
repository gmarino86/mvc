<?php
namespace DaVinci\DB;

use DaVinci\Core\App;
use Exception;
use PDO;

class DBConnection
{
    /** @var PDO|null Variable estática para guardar la conexión de PDO. */
    private static $db;

    // Constantes de conexión.
    //const DB_HOST = "localhost";
    //const DB_USER = "root";
    //const DB_PASS = "";
    //const DB_BASE = "goNetwork";

    /**
     * DBConnection constructor.
     * Este constructor lo tenemos solo para marcarlo como privado, y evitar que se pueda instanciar
     * libremente la clase.
     */
    private function __construct()
    {}

    /**
     * Abre la conexión a la base instanciando PDO.
     */
    protected static function openConnection()
    {
        // Leemos los datos de la conexión del entorno.
        $host = App::getEnv('DATABASE_HOST');
        $user = App::getEnv('DATABASE_USER');
        $pass = App::getEnv('DATABASE_PASS');
        $base = App::getEnv('DATABASE_NAME');
        $dsn = "mysql:host=" . $host . ";dbname=" . $base . ";charset=utf8mb4";

        try {
            self::$db = new PDO($dsn, $user, $pass);
        } catch(Exception $e) {
            echo "Error al conectar con la base de datos ";
        }
    }

    /**
     * Retorna la conexión PDO a la base de datos.
     * Si no está aún abierta, primero hace la conexión, y luego la retorna.
     *
     * @return PDO
     */
    public static function getConnection()
    {
        if(self::$db === null) {
            self::openConnection();
        }
        return self::$db;
    }
}
