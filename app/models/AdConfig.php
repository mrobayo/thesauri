<?php
//namespace Thesaurus\Sistema;

class AdConfig extends \BaseModel
{

    /**
     *
     * @var string
     * @Primary
     * @Column(type="string", length=60, nullable=false)
     */
    public $id_config;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=false)
     */
    public $tipo_config;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $is_activo;

    /**
     *
     * @var string
     * @Column(type="string", length=600, nullable=true)
     */
    public $descripcion;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $nombre;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=true)
     */
    public $orden;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=false)
     */
    public $tipo_prop;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $is_requerido;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=false)
     */
    public $tipo_valor;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'ad_config';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return AdConfig[]|AdConfig
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AdConfig
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
