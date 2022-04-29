<?php
namespace DaVinci\Models;

use DaVinci\DB\DBConnection;
use Davinci\Storage\Session;
use PDO;

class Post extends Modelo implements \JsonSerializable
{

    /** @var string La tabla con la que el Modelo se mapea. */
    protected $table = 'post';

    /** @var string El nombre del campo que es la PK. */
    protected $primaryKey = 'id';

    /** @var array La lista de atributos/campos de la tabla que se mapean con las propiedades del Modelo. */
    protected $attributes = [
        'id',
        'title',
        'content',
        'post_pic',
        'owner_id',
        'likes',
        'category_id',
        'created_at',
        'is_active'
    ];

    private $id;
    private $title;
    private $content;
    private $post_pic;
    private $owner_id;
    private $likes;
    private $category_id;
    private $created_at;
    private $is_active;

    private $categoria;

    private $usuarioPost;

    private $comentarios = [];


    /**
     * Esta función debe retornar cómo se representa como JSON este objeto.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id'            => $this->getId(),
            'title'         => $this->getTitle(),
            'content'       => $this->getContent(),
            'post_pic'      => $this->getPostPic(),
            'owner_id'      => $this->getOwnerId(),
            'likes'         => $this->getLikes(),
            'category_id'   => $this->getCategoryId(),
            '$created_at'   => $this->getCreatedAt(),
            'is_active'     => $this->getIsActive(),
            'categoria'     => $this->getCategoria()
        ];
    }

    public function traerTodo(): array
    {
        $id = Session::get('id');

        $query = "select p.*, c.id category_id, c.name category_name, u.name name, u.last_name last_name
                from post p LEFT JOIN category c on p.category_id = c.id
                INNER JOIN user u on p.owner_id = u.id
                WHERE p.owner_id = ?";
        $db = DBConnection::getConnection();
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);

        $salida = [];

        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $post = new self;
            $post->cargarDatosDeArray($fila);
            $categoria = new Categoria();
            $categoria->cargarDatosDeArray([
                'id' => $fila['category_id'],
                'name' => $fila['category_name']
            ]);
            $post->setCategoria($categoria);

            $userPost = new UsuarioPost();
            $userPost->cargarDatosDeArray([
                'name' => $fila['name'],
                'last_name' => $fila['last_name']
            ]);
            $post->setUsuarioPost($userPost);

            $salida[] = $post;
        }

        return $salida;
    }

    /**
     * Trae todos los post publicos para mostrar en la Home si el usuario esta logueado.
     * @return array | Post
     */
    public function traerTodosPublicos(): array
    {
        $query = "select p.id, p.title, p.post_pic, u.name, u.last_name from post p 
            inner join user u on p.owner_id = u.id
            where category_id = 1
            ORDER BY 1 DESC";
        $db = DBConnection::getConnection();
        $stmt = $db->prepare($query);
        $stmt->execute();

        $salida = [];

        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $post = new self;
            $post->cargarDatosDeArray($fila);

            $userPost = new UsuarioPost();
            $userPost->cargarDatosDeArray([
                'name' => $fila['name'],
                'last_name' => $fila['last_name']
            ]);
            $post->setUsuarioPost($userPost);

            $salida[] = $post;
        }

        return $salida;
    }



    public function traerPublicosXFriend(int $friend): array
    {
        $query = "select p.id, p.title, p.post_pic, u.name, u.last_name from post p 
            inner join user u on p.owner_id = u.id
            where p.category_id = 1
            and p.owner_id = ?;";
        $db = DBConnection::getConnection();
        $stmt = $db->prepare($query);
        $stmt->execute([$friend]);

        $salida = [];

        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $post = new self;
            $post->cargarDatosDeArray($fila);

            $userPost = new UsuarioPost();
            $userPost->cargarDatosDeArray([
                'name' => $fila['name'],
                'last_name' => $fila['last_name']
            ]);
            $post->setUsuarioPost($userPost);

            $salida[] = $post;
        }

        return $salida;
    }

    /**
     * Trae la categoria del producto
     * @return Categoria;
     */
    public function traerCategoria()
    {
        $this->categoria = (new Categoria())->traerPorID($this->getCategoryId());
    }

    /**
     * Trae los datos del usuario que creo el Post
     * @return UsuarioPost
     */
    public function traerUsuarioPost()
    {
        $this->usuarioPost = (new UsuarioPost())->traerPorIdPost($this->getId());
    }


    public function traerComentarios()
    {
        $this->comentarios = (new Comentarios())->traerPorIdPost($this->getId());
    }




    /**
     * COMIENZO DE GETTERS & GETTERS
     */

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
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getPostPic()
    {
        return $this->post_pic;
    }

    /**
     * @param mixed $post_pic
     */
    public function setPostPic($post_pic)
    {
        $this->post_pic = $post_pic;
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
    public function setOwnerId($owner_id)
    {
        $this->owner_id = $owner_id;
    }

    /**
     * @return mixed
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param mixed $likes
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param int $category_id
     */
    public function setCategoryId(int $category_id) :void
    {
        $this->category_id = $category_id;
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
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * @param mixed $is_active
     */
    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;
    }



    /**
     * @return mixed
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * @param mixed $categoria
     */
    public function setCategoria($categoria): void
    {
        $this->categoria = $categoria;
    }

    /**
     * @return mixed
     */
    public function getUsuarioPost()
    {
        return $this->usuarioPost;
    }

    /**
     * @param mixed $usuarioPost
     */
    public function setUsuarioPost($usuarioPost): void
    {
        $this->usuarioPost = $usuarioPost;
    }

    /**
     * @return mixed
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * @param mixed $comentarios
     */
    public function setComentarios($comentarios): void
    {
        $this->comentarios = $comentarios;
    }



}
