<?php

namespace DaVinci\Models;

use DaVinci\DB\DBConnection;
use Davinci\Storage\createModelException;
use Davinci\Storage\Session;
use DaVinci\Utilities\Str;
use PDO;

class Modelo
{
    /** @var string La tabla con la que el Modelo se mapea. */
    protected $table = '';

    /** @var string El nombre del campo que es la PK. */
    protected $primaryKey = '';

    /** @var array La lista de atributos/campos de la tabla que se mapean con las propiedades del Modelo. */
    protected $attributes = [];

    /**
     * Asigna todos los valores de $data que referencien a atributos definidos en self::$attributes.
     *
     * @param array $data
     */
    public function cargarDatosDeArray(array $data)
    {
        foreach($this->attributes as $attribute) {
            if(isset($data[$attribute])) {
                $setter = 'set' . ucfirst(Str::snakeToCamel($attribute));
                if(method_exists($this, $setter)) {
                    $this->{$setter}($data[$attribute]);
                } else {
                    $this->{$attribute} = $data[$attribute];
                }
            }
        }
    }

    /**
     * Retorna todos los registros de la tabla.
     *
     * @return static[]
     */
    public function traerTodo(): array
    {
        $query = "SELECT * FROM " . $this->table;
        $db = DBConnection::getConnection();
        $stmt = $db->prepare($query);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, static::class);

        return $stmt->fetchAll();

    }

    /**
     * Retorna el registro asociado a la PK.
     *
     * @param mixed $id
     * @return static|null
     */
    public function traerPorID(int $id)
    {
        $db = DBConnection::getConnection();
        $query = "SELECT * FROM " . $this->table . "
                WHERE " . $this->primaryKey . " = ?";
        $stmt = $db->prepare($query);

        if(!$stmt->execute([$id])) {
            return null;
        }

        return $stmt->fetchObject(static::class);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function crear(array $data): bool
    {
        $db = DBConnection::getConnection();
        $columns    = $this->prepareInsertColumns($data);
        $holders    = $this->prepareInsertHolders($columns);
        $data       = $this->prepareInsertData($data, $columns);

        $query = "INSERT INTO " . $this->table . " (" . implode(',', $columns) . ")
                  VALUES (" . implode(',', $holders) . ")";
        $stmt = $db->prepare($query);

        if(!$stmt->execute($data)) {
            throw new createModelException();
        }
        return true;
    }

    /**
     * Funcion para editar una clase
     * @param array $data
     * @return bool
     */
    public function editar(array $data, int $id): bool
    {
        $db = DBConnection::getConnection();
        $columnsAndHolders = $this->prepareUpdateColumns($data);

        $query = "UPDATE " . $this->table . " SET " . implode(',', $columnsAndHolders) . "
                  WHERE (id = ". $id ." )";

        $stmt = $db->prepare($query);

        if(!$stmt->execute($data)) {
            return false;
        }
        return true;
    }

    /**
     * Retorna un array con los nombres de las columnas para el INSERT.
     * Las columnas se detectan a partir de las claves de los valores en $data que coinciden con
     * atributos de $attributes.
     *
     * @param array $data
     * @return array
     */
    protected function prepareUpdateColumns(array $data)
    {
        $salida = [];
        foreach($data as $key => $value) {
            // Si existe la key de $data en los atributos, lo agregamos al Update.
            if(in_array($key, $this->attributes)) {
                $salida[] = $key."=".":".$key;
            }
        }

        return $salida;
    }


    /**
     * Retorna un array con los nombres de las columnas para el INSERT.
     * Las columnas se detectan a partir de las claves de los valores en $data que coinciden con
     * atributos de $attributes.
     *
     * @param array $data
     * @return array
     */
    protected function prepareInsertColumns(array $data)
    {
        $salida = [];
        foreach($data as $key => $value) {
            // Si existe la key de $data en los atributos, lo agregamos al INSERT.
            if(in_array($key, $this->attributes)) {
                $salida[] = $key;
            }
        }
        return $salida;
    }

    /**
     * Retorna un array con los holders.
     * Los holders van a ser los valores del array $columns con un ":" prefijado.
     *
     * @param array $columns
     * @return array
     */
    protected function prepareInsertHolders(array $columns): array
    {
        $salida = [];
        foreach($columns as $column) {
            $salida[] = ":" . $column;
        }
        return $salida;
    }

    /**
     * Retorna un array con los valores para el execute del INSERT.
     * Va a contener las claves que coinciden con las columnas y los valores de $data ascoiados.
     *
     * @param array $data
     * @param array $columns
     * @return array
     */
    protected function prepareInsertData(array $data, array $columns): array
    {
        $salida = [];
        foreach($columns as $column) {
            $salida[$column] = $data[$column];
        }
        return $salida;
    }

    /**
     * FUNCION PARA ELIMAR UN ELEMENTO
     *
     * @param $id
     *
     * @return bool
     */
    public function eliminarXId($id)
    {
        $db = DBConnection::getConnection();
        $query = 'DELETE FROM ' . $this->table . ' WHERE '. $this->primaryKey . '= ?';
        $stmt = $db->prepare($query);

        if (!$stmt->execute([$id])) {
            return false;
        }
        return true;
    }



}
