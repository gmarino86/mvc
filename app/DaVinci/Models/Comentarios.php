<?php

namespace DaVinci\Models;

use DaVinci\DB\DBConnection;
use Davinci\Storage\Session;

class Comentarios extends Modelo
{
    /** @var string La tabla con la que el Modelo se mapea. */
    protected $table = 'comment';

    /** @var string El nombre del campo que es la PK. */
    protected $primaryKey = 'id';

    /** @var array La lista de atributos/campos de la tabla que se mapean con las propiedades del Modelo. */
    protected $attributes = [
        'id',
        'post_id',
        'owner_id',
        'content',
        'created_at'
    ];

    protected $id;
    protected $post_id;
    protected $owner_id;
    protected $content;
    protected $created_at;

    protected $name;
    protected $last_name;

    /**
     * Traigo los comentarios por ID del Post
     * @param int $id
     * @return array| Comentarios
     */
    public function traerPorIdPost(int $id)
    {
        $query = 'select c.*, u.name, u.last_name
            from gonetwork.post p 
            inner join comment c on p.id = c.post_id
            left join user u on c.owner_id = u.id
            WHERE p.id = ?';
        $db = DBConnection::getConnection();
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);

        $salida = [];
        while ($obj = $stmt->fetchObject(static::class)){
            $salida[]=$obj;
        }
        return $salida;

    }


    public function eliminarComentarios(int $id)
    {
        $query = "DELETE FROM comment WHERE post_id = ?";
        $db = DBConnection::getConnection();
        $stmt = $db->prepare($query);
        $exito = $stmt->execute([$id]);

        if(!$exito) {
            Session::set('error','No se pudieron eliminar los comentarios');
            return false;
        }
        return true;
    }







    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * @param mixed $post_id
     */
    public function setPostId($post_id): void
    {
        $this->post_id = $post_id;
    }

    /**
     * @return mixed
     */
    public function getOwnerId()
    {
        return $this->owner_id;
    }

    /**
     * @param mixed $owner_id
     */
    public function setOwnerId($owner_id): void
    {
        $this->owner_id = $owner_id;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
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