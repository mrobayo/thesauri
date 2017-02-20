<?php
namespace Thesaurus\Thesauri;

/**
 *
 * @author mrobayo
 */
class ThRelacion extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     * @Primary
     * @Column(type="string", length=20, nullable=false)
     */
    public $id_relacion;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $descripcion;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=false)
     */
    public $tipo_item;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=false)
     */
    public $id_dominio;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
        $this->hasMany('id_relacion', 'ThThesauri', 'id_relacion', ['alias' => 'ThThesauri']);
        $this->belongsTo('id_dominio', '\ThDominio', 'id_dominio', ['alias' => 'ThDominio']);
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
