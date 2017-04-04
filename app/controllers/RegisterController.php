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
class RegisterController extends \ControllerBase
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
        $this->view->form = $form;

        if ($this->request->isPost()) {

        	// Create a transaction manager
        	$this->db->begin();

            $nombre = $this->request->getPost('nombre', array('string', 'striptags'));
            $email = $this->request->getPost('email', 'email');
            $password = $this->request->getPost('clave');
            $repeatPassword = $this->request->getPost('repeatPassword');

            if ($password != $repeatPassword) {
                $this->flash->error('Clave y confirmación son diferentes');
                return;
            }

            $user_existe = AdUsuario::findFirst( ["(email = :email:)", 'bind' => ['email' => $email]] );

            if ($user_existe) {

            	if ($user_existe->is_activo) {
            		$this->flash->error("El email {$user_existe->email} ya esta registrado. Por favor, reinicia tu clave.");
					return;
            	}
            	else {
            		$user = $user_existe;
            	}
            }
            else {
            	$user = new AdUsuario();
            }

            $user->clave = sha1($password);
            $user->nombre = $nombre;
            $user->email = $email;
            $user->app_role = 'USER';
            $user->fecha_ingreso = new RawValue('now()');
            $user->is_activo = new RawValue('FALSE');

            if ($user->save() == false) {
            	$this->db->rollback();

                foreach ($user->getMessages() as $message) {
                    $this->flash->error((string) $message);
                }

                $this->flash->error("Fallo registro de usuario.");
                // return $this->dispatcher->forward( ["controller" => "session", "action" => "index",] );

            }
            else {

            	$this->db->commit();

            	$this->tag->setDefault('email', '');
            	$this->tag->setDefault('password', '');

            	$this->flash->success('Registro exitoso, por favor verifica tu email.');

            	try
            	{
            		return $this->response->redirect('session/index');
            	}
            	finally
            	{
            		if ($this->mail) {
            			$emailHash = sha1($user->email);

            			// $this->mail->send([$user->email => $user->nombre], "Reset your password", 'reset', ['resetUrl' => '/reset-password/xxxx/' . $user->email]);

            			$this->mail->send(
            					[$user->email => $user->nombre],
            					'Confirmar registro '. $this->config->application->appTitle,
            					'confirmation', [
            							'confirmUrl' => '/confirmar-password/' . $emailHash . '/' . date('Ymd') . '.' . $user->id_usuario,
            							'appPartner' => $this->config->application->appPartner
            					]);
            		}
            	}

            	// Registro exitoso!
            }
        }
    }

    /**
     * Reset
     *
     * @param string $email_hash
     * @param string $id_usuario
     */
    public function resetAction($email_hash, $id_usuario) {

    }

    /**
     * Esto confirma el email
     */
    public function confirmarAction($email_hash, $fecha_id) {

		list($fecha, $id_usuario) = explode('.', $fecha_id);
		$usuario = AdUsuario::findFirst(['id_usuario=?1', 'bind'=>[1=> $id_usuario] ]);

		$email_valid = false;
		if ($usuario) {
			$email_valid = $email_hash == sha1($usuario->email);

			if (! $email_valid) {
				$this->flash->error("Codigo URL no es validación!");
			}
		}

		if ($usuario && $email_valid) {
			$usuario->is_activo = new RawValue('TRUE');

			if ($usuario->save()) {
				$this->flash->success('Bienvenido '.$usuario->nombre.', tu registro ha sido confirmado exitosamente. Ya puedes ingresar con tu email y clave!');
			}
			else {
				$this->flash->error("Usuario encontrado, pero fallo confirmación de registro. Por favor, registrate nuevamente!");
			}
		}
		else {
			$this->flash->error("Registro no encontrado, por favor vuelve a registrarte!");
		}

		return $this->dispatcher->forward( ["controller" => "session", "action" => "index",] );
    }
}
