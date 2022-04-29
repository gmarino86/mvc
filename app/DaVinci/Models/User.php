<?php
namespace DaVinci\Models;

use DaVinci\DB\DBConnection;
use Davinci\Storage\Session;
use JsonSerializable;
use PDO;

class User extends Modelo implements \JsonSerializable
{
    /** @var string La tabla con la que el Modelo se mapea. */
    protected $table = 'user';

    /** @var string El nombre del campo que es la PK. */
    protected $primaryKey = 'id';

    /** @var array La lista de atributos/campos de la tabla que se mapean con las propiedades del Modelo. */
    protected $attributes = [
        'id',
        'name',
        'last_name',
        'email',
        'password',
        'gender_id',
        'birth_date',
        'profile_pic',
        'created_at'
    ];

    private $id;
    private $name;
    private $last_name;
    private $email;
    private $password;
    private $gender_id;
    private $birth_date;
    private $profile_pic;
    private $created_at;

    private $gender;

    private $friends = [];


    public function jsonSerialize() {
        return [
            'id'            => $this->getId(),
            'name'          => $this->getName(),
            'last_name'     => $this->getLastName(),
            'email'         => $this->getEmail(),
            'password'      => $this->getPassword(),
            'gender_id'     => $this->getGenderId(),

            'created_at'    => $this->getCreatedAt()
        ];
    }

    public function getUser($row)
    {
        $user = new self();
        $user->setId($row['id']);
        $user->setName($row['name']);
        $user->setLastName($row['last_name']);
        $user->setEmail($row['email']);
        $user->setGenderId($row['gender_id']);
        $user->setCreatedAt($row['created_at']);

        return $user;
    }


    public function getUserById($id)
    {
        $db = DBConnection::getConnection();
        $query = "SELECT * FROM user
                    WHERE id = ?";

        $stmt = $db->prepare($query);
        if (!$stmt->execute([$id])) {
            return null;
        };

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $user = new self();
        $user->setId($row['id']);
        $user->setName($row['name']);
        $user->setLastName($row['last_name']);
        $user->setEmail($row['email']);
        $user->setPassword($row['password']);
        $user->setGenderId($row['gender_id']);
        $user->setCreatedAt($row['created_at']);

        return $user;
    }


    /**
     * Traigo el objeto User si el email se encuentra en la DB
     *
     * @param $email
     * @return User|null
     */
    public function userByEmail(string $email)
    {
        $db = DBConnection::getConnection();
        $query = "SELECT * FROM user
                    WHERE email = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$email]);

        if (!$fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return null;
        };

        $user = new self();
        $user->setId($fila['id']);
        $user->setName($fila['name']);
        $user->setLastName($fila['last_name']);
        $user->setEmail($fila['email']);
        $user->setPassword($fila['password']);
        $user->setGenderId($fila['gender_id']);
        $user->setCreatedAt($fila['created_at']);

        return $user;

    }

    /**
     * Funcion para crear un nuevo User
     * @param $data
     * @return bool
     */
    public function createUser($data): bool
    {
        $hashPA = password_hash($data['password'], PASSWORD_DEFAULT);
        $db = DBConnection::getConnection();

        $columns = $this->prepareInsertColumns($data);
        $holders = $this->prepareInsertHolders($columns);
        $data = $this->prepareInsertData($data, $columns);

        $query = "INSERT INTO " . $this->table . " (".
                    implode(',',$columns) . ") VALUES (".
                    implode(',',$holders) . ")";

        $stmt = $db->prepare($query);
        if (!$stmt->execute([
            "name"          => $data['name'],
            "last_name"     => $data['last_name'],
            "email"         => $data['email'],
            "password"      => $hashPA,
            "gender_id"     => $data['gender_id']
        ])){
            return false;
        }
        Session::set('exito','Usuario creado con éxito');
        return true;
    }

    public function getAllContacts($id){
        $db = DBConnection::getConnection();
        $query = 'SELECT * FROM user u WHERE u.id in (SELECT uf.id_friend FROM user_has_friend uf where uf.id_user = :id and uf.`state` = 1) and u.id <> :id 
        UNION ALL SELECT * FROM user u WHERE u.id not in (SELECT uf.id_friend FROM user_has_friend uf where uf.id_user = :id) and u.id <> :id;';
        $stmt = $db->prepare($query);
        $stmt->execute(['id' => $id]);

        $output = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            $user = new self();
            $user->setId($row['id']);
            $user->setName($row['name']);
            $user->setLastName($row['last_name']);
            $user->setEmail($row['email']);
            $user->setGenderId($row['gender_id']);
            $user->setCreatedAt($row['created_at']);

            $output[] = $user;
        }
        return $output;
    }

    public function generatePassword($password){
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        return $passwordHash;
    }


    public function traerGenero()
    {
        $this->gender = (new Genero())->traerPorID($this->getGenderId());
    }


    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of last_name
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set the value of last_name
     *
     * @return  self
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of gender_id
     */
    public function getGenderId()
    {
        return $this->gender_id;
    }

    /**
     * Set the value of gender_id
     *
     * @return  self
     */
    public function setGenderId($gender_id)
    {
        $this->gender_id = $gender_id;

        return $this;
    }

    /**
     * Get the value of created_at
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     *
     * @return  self
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBirthDate()
    {
        return $this->birth_date;
    }

    /**
     * @param mixed $birth_date
     */
    public function setBirthDate($birth_date): void
    {
        $this->birth_date = $birth_date;
    }

    /**
     * @return mixed
     */
    public function getProfilePic()
    {
        return $this->profile_pic;
    }

    /**
     * @param mixed $profile_pic
     */
    public function setProfilePic($profile_pic): void
    {
        $this->profile_pic = $profile_pic;
    }


    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender): void
    {
        $this->gender = $gender;
    }




}
