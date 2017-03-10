<?php
namespace Thesaurus\Controllers\Sistema;

use Phalcon\Flash;
use Phalcon\Session;
use Phalcon\Tag;
use Thesaurus\Forms\ThesaurusForm;
use Thesaurus\Forms\AdUsuarioForm;
use Thesaurus\Thesauri\ThThesaurus;
use Thesaurus\Sistema\AdUsuario;

/**
 * Administracion
 * @author mrobayo
 */
class AdminController extends ControllerBase
{
	/*
	 * {@inheritDoc}
	 * @see \Helpdesk\Controllers\Sistema\ControllerBase::initialize()
	 */
    public function initialize()
    {
        $this->tag->setTitle('Administración');
        parent::initialize();

        // Transalation messages/es.php
        $this->view->t = $this->getTranslation();
    }

    public function indexAction()
    {
    	$this->view->myheading = 'General';
    	$this->view->config_seccion = 'ajustes_general';
    }

    /**
     * Edit & Save
     */
    public function thesaurusAction($id_thesaurus = NULL)
    {
    	$this->view->myheading = 'Thesaurus';

    	if ($this->request->isPost()) {
    		$id_thesaurus = $this->request->getPost("id_thesaurus");
    	}

    	if (is_numeric($id_thesaurus)) {
    		$entidad = ThThesaurus::findFirstByid_thesaurus($id_thesaurus);

    		if (!$entidad) {
    			$this->flash->error("Thesaurus [$id_thesaurus] no encontrado");

    			$this->dispatcher->forward([ 'controller' => "admin", 'action' => 'index' ]);
    			return;
    		}
    	}
    	else {
    		$entidad = new ThThesaurus();
    	}

    	$form = new ThesaurusForm($entidad);

    	if ($this->request->isPost()) {
    		if ($form->guardar($entidad)) {
    			$this->logger->error('guardado exitosmente :D');
    			return $this->dispatcher->forward( ["controller" => "admin", "action" => "index", ] );
    		}
    	}

    	$items_list = [];

    	foreach (ThThesaurus::find() as $c) {
    		// $c->xml_iso25964 = \StringHelper::xmltoArray($c->xml_iso25964);
    		$items_list[ $c->id_thesaurus ] = $c;
    	}

    	$this->view->items_list = $items_list;
    	$this->view->form = $form;
    	$this->view->entidad = $entidad;
    }


    /**
     * Edit & Save
     */
    public function usuariosAction($id = NULL)
    {
    	$this->view->myheading = 'Usuarios';

    	if ($this->request->isPost()) {
    		$id = $this->request->getPost("id_usuario");
    	}

    	if (is_numeric($id)) {
    		$entidad = AdUsuario::findFirstByid_usuario($id);

    		if (!$entidad) {
    			$this->flash->error("Usuario [$id] no encontrado");
    			$this->dispatcher->forward([ 'controller' => "admin", 'action' => 'index' ]);
    			return;
    		}
    	}
    	else {
    		$entidad = new AdUsuario();
    	}

    	$form = new AdUsuarioForm($entidad);

    	if ($this->request->isPost()) {
    		if ($form->guardar($entidad)) {
    			$this->logger->error('guardado exitosmente :D');
    			return $this->dispatcher->forward( ["controller" => "admin", "action" => "index", ] );
    		}
    	}

    	$items_list = [];

    	foreach (AdUsuario::find() as $c) {
    		$items_list[ $c->id_usuario ] = $c;
    	}

    	$this->view->items_list = $items_list;
    	$this->view->form = $form;
    	$this->view->entidad = $entidad;
    }

    /**
     * Edit the active user profile
     *
     */
    public function profileAction()
    {
        //Get session info
        $auth = $this->session->get('auth');

        //Query the active user
        $user = Users::findFirst($auth['id']);
        if ($user == false) {
            return $this->dispatcher->forward(
                [
                    "controller" => "index",
                    "action"     => "index",
                ]
            );
        }

        if (!$this->request->isPost()) {
            $this->tag->setDefault('name', $user->name);
            $this->tag->setDefault('email', $user->email);
        }
        else {
            $name = $this->request->getPost('name', array('string', 'striptags'));
            $email = $this->request->getPost('email', 'email');

            $user->name = $name;
            $user->email = $email;
            if ($user->save() == false) {
                foreach ($user->getMessages() as $message) {
                    $this->flash->error((string) $message);
                }
            } else {
                $this->flash->success('Your profile information was updated successfully');
            }
        }

    }

}
