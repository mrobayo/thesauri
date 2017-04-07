<?php

namespace Thesaurus\Thesauri;

class ThTermino extends \BaseModel
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=32, nullable=false)
     */
    public $id_termino;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $nombre;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=false)
     */
    public $estado_termino;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $rdf_uri;

    /**
     *
     * @var string
     * @Column(type="string", length=2, nullable=false)
     */
    public $iso25964_language;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=false)
     */
    public $id_thesaurus;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $notilde;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $descripcion;

    /**
     *
     * @var string
     * @Column(type="string", length=120, nullable=true)
     */
    public $dc_source;

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
        $this->hasMany('id_termino', 'Thesaurus\Thesauri\ThNota', 'id_termino', ['alias' => 'ThNota']);
        $this->hasMany('id_termino', 'Thesaurus\Thesauri\ThRelacion', 'id_termino', ['alias' => 'ThRelacion']);
        $this->hasMany('id_termino', 'Thesaurus\Thesauri\ThRelacion', 'id_termino_rel', ['alias' => 'ThRelacion']);
        $this->belongsTo('id_thesaurus', 'Thesaurus\Thesauri\\ThThesaurus', 'id_thesaurus', ['alias' => 'ThThesaurus']);
        $this->belongsTo('id_aprobador', 'Thesaurus\Sistema\\AdUsuario', 'id_usuario', ['alias' => 'AdUsuario']);
        $this->belongsTo('id_ingreso', 'Thesaurus\Sistema\\AdUsuario', 'id_usuario', ['alias' => 'AdUsuario']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'th_termino';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ThTermino[]|ThTermino
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ThTermino
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
