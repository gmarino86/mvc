<?php

namespace DaVinci\Models;

class Categoria extends Modelo
{
    /** @var string La tabla con la que el Modelo se mapea. */
    protected $table = 'category';

    /** @var string El nombre del campo que es la PK. */
    protected $primaryKey = 'id';

    /** @var array La lista de atributos/campos de la tabla que se mapean con las propiedades del Modelo. */
    protected $attributes = [
        'id',
        'name'
    ];

    /**
     * Atributos propios de la Clase
     *
     */
    protected $id;
    protected $name;


    /**
     *  COMIENZO DE GETTER Y SETTER
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
    public function setId($id): void
    {
        $this->id = $id;
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

}