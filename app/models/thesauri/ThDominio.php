<?php
namespace Thesaurus\Thesauri;

/**
 *
 * @author mrobayo
 */
class ThDominio extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=32, nullable=false)
     */
    public $id_dominio;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $nombre;

    /**
     *
     * @var string
     * @Column(type="string", length=600, nullable=false)
     */
    public $descripcion;

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
     * @Column(type="string", nullable=false)
     */
    public $fecha_inactivo;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
        $this->hasMany('id_dominio', 'ThRelacion', 'id_dominio', ['alias' => 'ThRelacion']);
        $this->hasMany('id_dominio', 'ThTermino', 'id_dominio', ['alias' => 'ThTermino']);
        $this->hasMany('id_dominio', 'ThThesauri', 'id_dominio', ['alias' => 'ThThesauri']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'th_dominio';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ThDominio[]|ThDominio
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ThDominio
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
