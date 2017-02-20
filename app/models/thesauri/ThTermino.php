<?php
namespace Thesaurus\Thesauri;

use Thesaurus\Sistema\AdUsuario;

/**
 *
 * @author mrobayo
 */
class ThTermino extends \Phalcon\Mvc\Model
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
     * @Column(type="string", length=2, nullable=false)
     */
    public $estado_termino;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=false)
     */
    public $id_dominio;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $notilde;

    /**
     *
     * @var string
     * @Column(type="string", length=600, nullable=false)
     */
    public $descripcion;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=true)
     */
    public $id_aprobador;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
        $this->hasMany('id_termino', 'ThThesauri', 'id_termino', ['alias' => 'ThThesauri']);
        $this->hasMany('id_termino', 'ThThesauri', 'id_termino_rel', ['alias' => 'ThThesauri']);
        $this->belongsTo('id_aprobador', '\AdUsuario', 'id_usuario', ['alias' => 'AdUsuario']);
        $this->belongsTo('id_dominio', '\ThDominio', 'id_dominio', ['alias' => 'ThDominio']);
        $this->belongsTo('id_ingreso', '\AdUsuario', 'id_usuario', ['alias' => 'AdUsuario']);
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
