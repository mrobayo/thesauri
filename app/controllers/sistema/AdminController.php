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
     * Edit & Save
     */
    public function thesaurusAction($id_thesaurus = NULL)
    {
    	$this->view->myheading = 'Thesaurus';

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
    		if ($this->guardarThesaurus($form)) {
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


    	$entidad->xml_iso25964 = (string) $xml;
    	$entidad->aprobar_list = '';
    	$entidad->id_propietario = 1;
    	$entidad->rdf_uri = $this->config->rdf->baseUri . \StringHelper::urlize( $entidad->nombre );

    	$entidad->iso25964_identifier = \StringHelper::urlize($entidad->nombre);

    	$entidad->iso25964_description = $form->getString('iso25964_description');
    	$entidad->iso25964_publisher = $form->getString('iso25964_publisher');
    	$entidad->iso25964_rights = $form->getString('iso25964_rights');

    	$entidad->iso25964_license = $form->getString('iso25964_license');
    	$entidad->iso25964_coverage = $form->getString('iso25964_coverage');
    	$entidad->iso25964_created = $form->getString('iso25964_created');

    	$entidad->iso25964_subject = $form->getString('iso25964_subject');
    	$entidad->iso25964_language = $form->getString('iso25964_language');
    	$entidad->iso25964_source = $form->getString('iso25964_source');

    	$entidad->iso25964_creator = $form->getString('iso25964_creator');
    	$entidad->iso25964_contributor = $form->getString('iso25964_contributor');
    	$entidad->iso25964_type = $form->getString('iso25964_type');

    	$entidad->term_aprobados = 0;
    	$entidad->term_pendientes = 0;
    	$entidad->is_activo = TRUE;
    	$entidad->is_publico = TRUE;


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
