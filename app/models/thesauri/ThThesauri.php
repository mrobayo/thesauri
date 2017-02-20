<?php
namespace Thesaurus\Thesauri;

use Thesaurus\Sistema\AdUsuario;

/**
 *
 * @author mrobayo
 */
class ThThesauri extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=32, nullable=false)
     */
    public $id_thesauri;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=false)
     */
    public $id_termino;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=false)
     */
    public $id_termino_rel;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=false)
     */
    public $id_relacion;

    /**
     *
     * @var string
     * @Column(type="string", length=2, nullable=false)
     */
    public $estado_relacion;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=false)
     */
    public $id_dominio;

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
    public $id_aprobador;

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
    public $id_ingreso;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
        $this->belongsTo('id_dominio', '\ThDominio', 'id_dominio', ['alias' => 'ThDominio']);
        $this->belongsTo('id_relacion', '\ThRelacion', 'id_relacion', ['alias' => 'ThRelacion']);
        $this->belongsTo('id_termino', '\ThTermino', 'id_termino', ['alias' => 'ThTermino']);
        $this->belongsTo('id_ingreso', '\AdUsuario', 'id_usuario', ['alias' => 'AdUsuario']);
        $this->belongsTo('id_termino_rel', '\ThTermino', 'id_termino', ['alias' => 'ThTermino']);
        $this->belongsTo('id_aprobador', '\AdUsuario', 'id_usuario', ['alias' => 'AdUsuario']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'th_thesauri';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ThThesauri[]|ThThesauri
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ThThesauri
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
