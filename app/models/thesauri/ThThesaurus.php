<?php

namespace Thesaurus\Thesauri;

class ThThesaurus extends \BaseModel
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=32, nullable=false)
     */
    public $id_thesaurus;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $nombre;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $notilde;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $is_activo;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $is_publico;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $rdf_uri;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=false)
     */
    public $term_aprobados;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=false)
     */
    public $term_pendientes;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $ultima_actividad;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $xml_iso25964;

    /**
     *
     * @var string
     * @Column(type="string", length=600, nullable=true)
     */
    public $aprobar_list;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=true)
     */
    public $id_propietario;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $iso25964_identifier;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $iso25964_coverage;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $iso25964_creator;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $iso25964_created;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $iso25964_description;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $iso25964_publisher;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $iso25964_rights;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $iso25964_source;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $iso25964_license;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $iso25964_type;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $fecha_ingreso;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $fecha_modifica;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $fecha_inactivo;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
        $this->belongsTo('id_propietario', 'Thesaurus\Thesauri\\AdUsuario', 'id_usuario', ['alias' => 'AdUsuario']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'th_thesaurus';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ThThesaurus[]|ThThesaurus
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ThThesaurus
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
