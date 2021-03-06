<?php
namespace Thesaurus\Controllers;

use MathCaptcha\MathCaptcha;
use Thesaurus\Forms\AdUsuarioForm;
use Thesaurus\Forms\RecuperarForm;
use Phalcon\Forms\Element\Email;
use \AdUsuario;

/**
 * SessionController
 *
 * @author mrobayo
 */
class SessionController extends \ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Ingresar/Registrarse');
        parent::initialize();
    }

    /**
     * Index
     */
    public function indexAction()
    {
        if (!$this->request->isPost()) {
            $this->tag->setDefault('email', '');
            $this->tag->setDefault('clave', '');
        }
    }

    /**
     * Recuperar clave
     */
    public function recuperarAction()
    {
    	$form = new RecuperarForm();

    	if ($this->request->isPost()) {

    		$email = $this->request->getPost('email', ['string', 'striptags']);
    		$captcha_answer = $this->request->getPost('captcha', ['string', 'striptags']);

    		$mathCaptcha = new MathCaptcha();

    		if ( $mathCaptcha->check($captcha_answer) !== true ) {
    			// Incorrect answer
    			$this->tag->setDefault('email', $email);
    			$this->flash->error('Respuesta del captcha es incorrecto');
    		}
    		else {
    			$form->recuperarClave($email);
    			$this->response->redirect('session/index');
    		}
    	}

    	$this->view->form = $form;
    }

    /**
     * Captcha
     */
    public function captchaAction()
    {
    	$this->view->disable();
    	$mathCaptcha = new MathCaptcha();

    	$mathCaptcha->generate();
    	$mathCaptcha->output();
    }


    /**
     * Register an authenticated user into session data
     *
     * @param AdUsuario $user
     */
    private function _registerSession(AdUsuario $user)
    {
        $this->session->set('auth', array(
            'id' => $user->id_usuario,
            'nombre' => $user->nombre,
        	'app_role' => $user->app_role,
        	'is_user' => ($user->app_role == AdUsuarioForm::ROLE_USER),
        	'is_admin' => ($user->app_role == AdUsuarioForm::ROLE_ADMIN)
        ));
    }

    /**
     * This action authenticate and logs an user into the application
     */
    public function startAction()
    {
        if ($this->request->isPost()) {

            $email = $this->request->getPost('email');
            $password = $this->request->getPost('clave');

            $user = AdUsuario::findFirst(array(
                "(email = :email:) AND clave = :password: AND is_activo = true",
                'bind' => array('email' => $email, 'password' => sha1($password))
            ));
            if ($user != false) {

            	$user->login_history = array_slice(explode(',', $user->login_history, 11), 0, 10);
            	$user->login_history = date('Y-m-d H:i') .','. implode(',', $user->login_history);

            	$user->update();

                $this->_registerSession($user);
                $this->flash->success('Bienvenido ' . $user->nombre);

                return $this->response->redirect('/');
            }

            $this->flash->error('Email o Clave incorrecto');
        }

        return $this->dispatcher->forward(
            [
                "controller" => "session",
                "action"     => "index",
            ]
        );
    }

    /**
     * Finishes the active session redirecting to the index
     *
     * @return unknown
     */
    public function endAction()
    {
        $this->session->remove('auth');
        $this->flash->success('Goodbye!');

        return $this->dispatcher->forward(
            [
                "controller" => "index",
                "action"     => "index",
            ]
        );
    }

}
