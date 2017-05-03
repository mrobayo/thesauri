<?php
//namespace Thesaurus\Sistema;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class AdUsuario extends \BaseModel
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=32, nullable=false)
     */
    public $id_usuario;

    /**
     *
     * @var string
     * @Column(type="string", length=120, nullable=false)
     */
    public $email;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $is_activo;

    /**
     *
     * @var string
     * @Column(type="string", length=120, nullable=false)
     */
    public $nombre;

    /**
     *
     * @var string
     * @Column(type="string", length=40, nullable=false)
     */
    public $app_role;

    /**
     *
     * @var string
     * @Column(type="string", length=40, nullable=false)
     */
    public $clave;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $cambiar_clave;

    /**
     *
     * @var string
     * @Column(type="string", length=120, nullable=true)
     */
    public $nuevaclave_info;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=false)
     */
    public $recibir_avisos;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $ultima_clave;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $login_history;

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
    public $fecha_inactivo;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
        $this->useDynamicUpdate(true);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'ad_usuario';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return AdUsuario[]|AdUsuario
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AdUsuario
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
