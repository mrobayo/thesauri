<?php
namespace Thesaurus\Thesauri;

/**
 *
 * @author mrobayo
 */
class ThThesaurus extends \Phalcon\Mvc\Model
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
     * @Column(type="string", nullable=true)
     */
    public $xml_metadata;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $aprobar_list;

    /**
     *
     * @var string
     * @Column(type="string", length=600, nullable=true)
     */
    public $descripcion;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=true)
     */
    public $id_propietario;

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
        $this->belongsTo('id_propietario', '\AdUsuario', 'id_usuario', ['alias' => 'AdUsuario']);
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
