<?php
namespace Thesaurus\Controllers\Sistema;

use Phalcon\Db\RawValue;
use Phalcon\Flash;
use Phalcon\Session;
use Phalcon\Tag;
use Thesaurus\Forms\ThesaurusForm;
use Thesaurus\Thesauri\ThThesaurus;

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
     * Guardar thesaurus
     * iconv('ISO-8859-1','ASCII//TRANSLIT',$val);
     * iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$val);
     *
     * @param \Thesaurus\Forms\ThesaurusForm $form
     * @return int
     */
    private function guardarThesaurus($form) {
    	$this->db->begin();

    	$entidad = new ThThesaurus();

    	$xml = $form->postToXml($this->request);

    	$entidad->id_thesaurus = $this->request->getPost('id_thesaurus');
    	$entidad->nombre = $this->request->getPost('nombre', array('string', 'striptags'));

    	$entidad->notilde = \StringHelper::notilde( $entidad->nombre );

    	$entidad->is_activo = TRUE;
    	$entidad->is_publico = TRUE;
    	$entidad->xml_metadata = (string) $xml;
    	$entidad->aprobar_list = '';
    	$entidad->id_propietario = 1;
    	$entidad->rdf_uri = \StringHelper::urlize( $entidad->nombre );
    	$entidad->num_terminos = 0;
    	$entidad->num_pendientes = 0;


    	if (empty($entidad->id_thesaurus)) {
    		$entidad->fecha_ingreso = new RawValue('now()');
    	}
    	else {
    		$entidad->fecha_modifica = new RawValue('now()');
    	}

    	if ($entidad->save() == false) {
    		$this->db->rollback();

    		foreach ($entidad->getMessages() as $message) {
    			$this->flash->error((string) $message);
    		}
    	}
    	else {
    		$this->db->commit();

    		//     			$this->tag->setDefault('email', '');
    		//     			$this->tag->setDefault('password', '');
    		$this->flash->success('Guardado exitosamente');

    		$this->logger->error( 'Thesaurus guardado: ' . $entidad->id_thesaurus);
    		return $entidad->id_thesaurus;
    	}
    }

    /**
     * Edit & Save
     */
    public function thesaurusAction()
    {
    	$form = new ThesaurusForm;
    	$this->view->myheading = 'Thesaurus';

    	if ($this->request->isPost()) {

    		if ($this->guardarThesaurus($form)) {
    			return $this->dispatcher->forward( ["controller" => "admin", "action" => "index", ] );
    		}
    	}

    	$items_list = [];

    	foreach (ThThesaurus::find() as $c) {
			$c->xml_metadata = $form->textToXml($c->xml_metadata);
    		$items_list[ $c->id_thesaurus ] = $c;
    	}
    	$this->view->items_list = $items_list;
    	$this->view->form = $form;
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
