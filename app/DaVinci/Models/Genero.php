<?php

namespace DaVinci\Models;

class Genero extends Modelo
{
    /** @var string La tabla con la que el Modelo se mapea. */
    protected $table = 'gender';

    /** @var string El nombre del campo que es la PK. */
    protected $primaryKey = 'id';

    /** @var array La lista de atributos/campos de la tabla que se mapean con las propiedades del Modelo. */
    protected $attributes = [
        'id',
        'type'
    ];

    /**
     * Atributos propios de la Clase
     *
     */
    protected $id;
    protected $type;

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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }


    /**
     *  COMIENZO DE GETTER Y SETTER
     */


}