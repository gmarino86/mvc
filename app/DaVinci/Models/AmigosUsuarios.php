<?php

namespace DaVinci\Models;

use DaVinci\DB\DBConnection;
use Davinci\Storage\Session;
use PDO;

class AmigosUsuarios
{

    protected $id_friend;
    protected $name;
    protected $last_name;

    public function traerAmigos()
    {
        $id_user = Session::get('id');
        $query = "SELECT uhf.id_friend, u.name, u.last_name FROM gonetwork.user_has_friend uhf 
                inner join user u on uhf.id_friend = u.id
                WHERE uhf.id_user = ?
                ORDER BY 2";
        $db = DBConnection::getConnection();
        $stmt = $db->prepare($query);
        $stmt->execute([$id_user]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        return $stmt->fetchAll();

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