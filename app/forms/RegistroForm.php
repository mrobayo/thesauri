<?php
namespace Thesaurus\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;

/**
 * Formulario de Registro
 * @author mrobayo
 */
class RegistroForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        // Name
        $name = new Text('nombre');
        $name->setLabel('Nombre completo');
        $name->setFilters(array('striptags', 'string'));
        $name->addValidators(array(
            new PresenceOf(array( 'message' => 'Nombre es requerido' ))
        ));
        $this->add($name);


        // Email
        $email = new Text('email');
        $email->setLabel('E-Mail');
        $email->setFilters('email');
        $email->addValidators(array(
            new PresenceOf(array( 'message' => 'E-mail es requerido' )),
            new Email(array( 'message' => 'E-mail no es valido' ))
        ));
        $this->add($email);

        // Password
        $password = new Password('clave');
        $password->setLabel('Clave');
        $password->addValidators(array(
            new PresenceOf(array( 'message' => 'Clave es requerida' ))
        ));
        $this->add($password);

        // Confirm Password
        $repeatPassword = new Password('repeatPassword');
        $repeatPassword->setLabel('Confirmación Clave');
        $repeatPassword->addValidators(array(
            new PresenceOf(array( 'message' => 'Confirmación de clave es requerida' ))
        ));
        $this->add($repeatPassword);
    }

}