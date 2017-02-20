<?php
namespace Thesaurus\Controllers;

use Thesaurus\Sistema\AdUsuario;

/**
 * SessionController
 *
 * @author mrobayo
 */
class SessionController extends ControllerBase
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
     * Register an authenticated user into session data
     *
     * @param AdUsuario $user
     */
    private function _registerSession(AdUsuario $user)
    {
        $this->session->set('auth', array(
            'id' => $user->id,
            'nombre' => $user->nombre,
        	'app_role' => $user->app_role,
        	'is_admin' => ($user->app_role == 'ADMIN')
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
                $this->_registerSession($user);
                $this->flash->success('Bienvenido ' . $user->nombre);

                return $this->dispatcher->forward(
                    [
                        "controller" => "admin",
                        "action"     => "index",
                    ]
                );
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
