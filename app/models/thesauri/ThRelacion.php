<?php

namespace Thesaurus\Thesauri;

class ThRelacion extends \BaseModel
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=32, nullable=false)
     */
    public $id_relacion;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=false)
     */
    public $tipo_relacion;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=false)
     */
    public $id_thesaurus;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=false)
     */
    public $id_termino;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=true)
     */
    public $id_termino_rel;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=false)
     */
    public $orden_relacion;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
        $this->belongsTo('id_termino', 'Thesaurus\Thesauri\\ThTermino', 'id_termino', ['alias' => 'ThTermino']);
        $this->belongsTo('id_termino_rel', 'Thesaurus\Thesauri\\ThTermino', 'id_termino', ['alias' => 'ThTermino']);
        $this->belongsTo('id_thesaurus', 'Thesaurus\Thesauri\\ThThesaurus', 'id_thesaurus', ['alias' => 'ThThesaurus']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'th_relacion';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ThRelacion[]|ThRelacion
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ThRelacion
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
