<?php
namespace Thesaurus\Forms;

use Phalcon\Validation\Validator\PresenceOf;
use Thesaurus\Sistema\AdUsuario;

/**
 * Formulario de Termino
 *
 * @author mrobayo
 */
class RecuperarForm extends BaseForm
{

	/**
	 *
	 */
    public function initialize($entity = null, $options = [])
    {
    	$this->addText('email', ['tooltip'=>'Correo Electrónico', 'label'=>'Email', 'filters'=>array('striptags', 'string'), 'validators'=>[new PresenceOf(['message' => 'es requerido'])] ]);
    	$this->addText('captcha', ['label'=>'Captcha', 'filters'=>array('striptags', 'string'), 'validators'=>[new PresenceOf(['message' => 'es requerido'])] ]);

        if ($this->isEditable($options)) {

        }
        else {
        	foreach($this->getElements() as &$e) {
        		$e->setAttribute("readonly", "readonly");
        	}
        }
    }

    /**
     * Recuperar clave
     */
    public function recuperarClave($email) {
    	$usuario = AdUsuario::findFirst(['email=?1', 'bind'=>[1=> $email] ]);;

    	if ($usuario === false) {
    		return $this->flash->warning('No se encontro el email ingresado. Por favor, verifique que su email sea el correcto.');
    	}

    	$this->flash->notice('Su email ha sido encontrado, las instrucciones han sido enviadas a su correo.');
    	$emailHash = sha1($usuario->email);

    	$this->mail->send(
    			[$usuario->email => $usuario->nombre],
    			'Recuperacion de clave / '. $this->config->application->appTitle,
    			'confirmation', [
    					'confirmUrl' => '/confirmar-password/' . $emailHash . '/' . date('Ymd') . '.' . $usuario->id_usuario,
    					'appPartner' => $this->config->application->appPartner
    	]);
    }





}