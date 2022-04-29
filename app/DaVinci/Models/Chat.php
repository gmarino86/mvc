<?php

namespace DaVinci\Models;

use DaVinci\DB\DBConnection;
use PDO;

class Chat extends Modelo
{
    /** @var string La tabla con la que el Modelo se mapea. */
    protected $table = 'chat';

    /** @var string El nombre del campo que es la PK. */
    protected $primaryKey = 'id_chat';

    /** @var array La lista de atributos/campos de la tabla que se mapean con las propiedades del Modelo. */
    protected $attributes = [
        'id_chat',
        'id_user',
        'id_friend',
        'message',
        'date'
    ];

    protected $id_chat;
    protected $id_user;
    protected $id_friend;
    protected $message;
    protected $date;
    protected $name;
    protected $last_name;
    protected $id_friendship;


    /**
     * Trae todos los nombres y apellidos de los usuarios con los que hay iniciado un chat.
     * @param int $id
     * @return array
     */
    public function traerTodosLosChats(int $id) :array
    {
        $query = "select id id_user, name, last_name from user
            where id in (
            SELECT id_user FROM user_has_chat
            WHERE id_friendship in 
                  (SELECT id_friendship FROM user_has_chat WHERE id_user = :id or id_friend = :id GROUP BY id_friendship)
            GROUP BY id_user
            UNION 
            SELECT id_friend FROM user_has_chat
            WHERE id_friendship in 
                  (SELECT id_friendship FROM user_has_chat WHERE id_user = :id or id_friend = :id GROUP BY id_friendship)
            GROUP BY id_friend);";
        $db = DBConnection::getConnection();
        $stmt = $db->prepare($query);
        $stmt->execute([
            'id'    =>  $id
        ]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, static::class);

        return $stmt->fetchAll();
    }



    public function traerChatsConAmigo(int $id_user, int $id_friend) :array
    {
        $query = "SELECT uhc.id_user, uhc.id_friend, uhc.id_chat, uhc.id_friendship, c.message, c.date, u.name, u.last_name FROM gonetwork.user_has_chat uhc
            left join chat c on uhc.id_chat = c.id_chat
            inner join user u on uhc.id_user = u.id
            where uhc.id_friendship = (SELECT id_friendship FROM gonetwork.user_has_chat
            where (id_user = :id_user and id_friend = :id_friend
            or id_friend = :id_user and id_user = :id_friend)
            GROUP BY id_friendship)
            order by uhc.id_chat";
        $db = DBConnection::getConnection();
        $stmt = $db->prepare($query);
        $stmt->execute([
            'id_user'   =>  $id_user,
            'id_friend' =>  $id_friend
        ]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, static::class);
        return $stmt->fetchAll();
    }

    public function enviarMsg($mensaje, $id_user, $id_friend, $id_friendship)
    {
        $db = DBConnection::getConnection();
        $query = "INSERT INTO chat (message) VALUES (:message);";
        $stmt = $db->prepare($query);
        $exito = $stmt->execute([
            'message'   =>  strval($mensaje)
        ]);
        if ($exito) {
            $id_chat = $db->lastInsertId();
            $query = "INSERT INTO user_has_chat (id_user, id_friend, id_chat, id_friendship) 
                    VALUES (:id_user, :id_friend, :id_chat, :id_friendship)";
            $stmt = $db->prepare($query);
            $exito = $stmt->execute([
                'id_user'       =>  $id_user,
                'id_friend'     =>  $id_friend,
                'id_chat'       =>  $id_chat,
                'id_friendship' =>  $id_friendship
            ]);

            if($exito){
                return true;
            } else {
                return false;
            }

            return true;
        }
        return false;

    }



    /**
     * @return mixed
     */
    public function getIdChat()
    {
        return $this->id_chat;
    }

    /**
     * @param mixed $id_chat
     */
    public function setIdChat($id_chat): void
    {
        $this->id_chat = $id_chat;
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
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
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

    /**
     * @return mixed
     */
    public function getIdFriendship()
    {
        return $this->id_friendship;
    }

    /**
     * @param mixed $id_friendship
     */
    public function setIdFriendship($id_friendship): void
    {
        $this->id_friendship = $id_friendship;
    }




}