<?php

namespace DaVinci\Models;

use DaVinci\DB\DBConnection;
use PDO;

class UsuarioPost
{

    protected $name;
    protected $last_name;

    public function cargarDatosDeArray(array $data)
    {
        $this->setName($data['name']);
        $this->setLastName($data['last_name']);
    }


    public function traerPorIdPost(int $id)
    {

        $query = 'select u.name name, u.last_name last_name
                from post p 
                INNER JOIN user u on p.owner_id = u.id
                WHERE p.id = ?';
        $db = DBConnection::getConnection();
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);

        $exito = $stmt->fetchObject(static::class);

        if(!$exito){
            return null;
        }
        return $exito;
    }





    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param mixed $last_name
     */
    public function setLastName($last_name): void
    {
        $this->last_name = $last_name;
    }






}