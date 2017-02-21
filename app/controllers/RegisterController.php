<?php
namespace Thesaurus\Controllers;

use Phalcon\Db\RawValue;
use Thesaurus\Forms\RegistroForm;
use Thesaurus\Sistema\AdUsuario;

/**
 * Registro
 *
 * Permite a los usuarios registrarse
 */
class RegisterController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Ingresar/ Registrarse');
        parent::initialize();
    }

    /**
     * Action to register a new user
     */
    public function indexAction()
    {
        $form = new RegistroForm;

        if ($this->request->isPost()) {

        	// Create a transaction manager
        	$this->db->begin();

            $nombre = $this->request->getPost('nombre', array('string', 'striptags'));
            $email = $this->request->getPost('email', 'email');
            $password = $this->request->getPost('clave');
            $repeatPassword = $this->request->getPost('repeatPassword');

            if ($password != $repeatPassword) {
                $this->flash->error('Clave y confirmación son diferentes');
                return false;
            }

            $user = new AdUsuario();
            $user->clave = sha1($password);
            $user->nombre = $nombre;
            $user->email = $email;
            $user->app_role = 'USER';
            $user->fecha_in = new RawValue('now()');
            $user->is_activo = TRUE;

            if ($user->save() == false) {
            	$this->db->rollback();

                foreach ($user->getMessages() as $message) {
                    $this->flash->error((string) $message);
                }

            } else {

            	$this->db->commit();

            	$this->tag->setDefault('email', '');
            	$this->tag->setDefault('password', '');

            	if ($this->mail) { $this->mail
            		->send([$user->email => $user->nombre], "Reset your password", 'reset',
            			['resetUrl' => '/reset-password/xxxx/' . $user->email]);
            	}


            	$this->flash->success('Registro exitoso, por favor verifica tu email');

            	return $this->dispatcher->forward(
            				[
            						"controller" => "session",
            						"action"     => "index",
            				]
            	);
            }
        }

        $this->view->form = $form;
    }
}
