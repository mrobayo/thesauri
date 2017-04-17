<?php

namespace Thesaurus\Thesauri;

class ThNota extends \BaseModel
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=32, nullable=false)
     */
    public $id_nota;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=false)
     */
    public $tipo_nota;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $contenido;

    /**
     *
     * @var string
     * @Column(type="string", length=120, nullable=false)
     */
    public $dc_source;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=false)
     */
    public $id_termino;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=true)
     */
    public $estado_nota;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=false)
     */
    public $id_thesaurus;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=true)
     */
    public $id_aprobador;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $fecha_aprobado;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=true)
     */
    public $id_ingreso;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $fecha_ingreso;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=true)
     */
    public $id_modifica;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $fecha_modifica;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
        $this->belongsTo('id_thesaurus', '\ThThesaurus', 'id_thesaurus', ['alias' => 'ThThesaurus']);
        $this->belongsTo('id_termino', '\ThTermino', 'id_termino', ['alias' => 'ThTermino']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'th_nota';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ThNota[]|ThNota
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ThNota
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
