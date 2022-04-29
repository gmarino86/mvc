<?php

namespace DaVinci\Models;

use DaVinci\DB\DBConnection;
use PDO;

class Friendship extends Modelo
{
    /** @var string La tabla con la que el Modelo se mapea. */
    protected $table = 'user_has_friend';

    /** @var string El nombre del campo que es la PK. */
    protected $primaryKey = 'id_user';

    /** @var array La lista de atributos/campos de la tabla que se mapean con las propiedades del Modelo. */
    protected $attributes = [
        'id_user',
        'id_friend',
        'state',
        'created_at'
    ];

    private $id_user;
    private $id_friend;
    private $state;
    private $created_at;

    private $name;
    private $last_name;


    public function verAmistad(int $id, int $friend)
    {
        $query = "SELECT uhf.*, u.name, u.last_name FROM user_has_friend uhf 
                inner join user u 
                on uhf.id_friend = u.id 
                WHERE id_user = ?
                and uhf.id_friend = ? 
                and state = 1;";
        $db = DBConnection::getConnection();
        $stmt = $db->prepare($query);
        $stmt->execute([$id, $friend]);

        if (!$fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return null;
        };

        $friend = new self();
        $friend->setIdUser($fila['id_user']);
        $friend->setIdFriend($fila['id_friend']);
        $friend->setState($fila['state']);
        $friend->setName($fila['name']);
        $friend->setLastName($fila['last_name']);

        return $friend;

    }

    public function traerAmigos(int $id)
    {
        $query = "SELECT uhf.*, u.name, u.last_name FROM user_has_friend uhf 
                inner join user u 
                on uhf.id_friend = u.id 
                WHERE id_user = ?
                and state = 1;";
        $db = DBConnection::getConnection();
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);

        $salida = [];
        while ($obj = $stmt->fetchObject(static::class)){
            $salida[]=$obj;
        }
        return $salida;
    }




    /**
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->id_user;
    }

    /**
     * @param mixed $id_user
     */
    public function setIdUser($id_user): void
    {
        $this->id_user = $id_user;
    }

    /**
     * @return mixed
     */
    public function getIdFriend()
    {
        return $this->id_friend;
    }

    /**
     * @param mixed $id_friend
     */
    public function setIdFriend($id_friend): void
    {
        $this->id_friend = $id_friend;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state): void
    {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
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