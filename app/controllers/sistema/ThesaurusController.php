<?php
namespace Thesaurus\Controllers\Sistema;

use Phalcon\Flash;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Tag;
use Thesaurus\Thesauri\ThThesaurus;
use Thesaurus\Forms\ThesaurusForm;


class ThesaurusController extends ControllerBase
{

	/*
	 * {@inheritDoc}
	 * @see \Helpdesk\Controllers\Sistema\ControllerBase::initialize()
	 */
	public function initialize()
	{
		$this->tag->setTitle('Thesaurus');
		parent::initialize();

		// Transalation messages/es.php
		$this->view->t = $this->getTranslation();
	}


    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for th_thesaurus
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'ThThesaurus', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id_thesaurus";

        $th_thesaurus = ThThesaurus::find($parameters);
        if (count($th_thesaurus) == 0) {
            $this->flash->notice("The search did not find any th_thesaurus");

            $this->dispatcher->forward([
                "controller" => "th_thesaurus",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $th_thesaurus,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a th_thesauru
     *
     * @param string $id_thesaurus
     */
    public function viewAction($id_thesaurus)
    {
    	$form = new ThesaurusForm(new ThThesaurus(), ['edit' => false]);
    	$this->view->myheading = 'Thesaurus';

        if (!$this->request->isPost()) {

            $th_thesauru = ThThesaurus::findFirstByid_thesaurus($id_thesaurus);
            if (!$th_thesauru) {
                $this->flash->error("th_thesauru was not found");

                $this->dispatcher->forward([
                    'controller' => "thesaurus",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id_thesaurus = $th_thesauru->id_thesaurus;
            $this->view->c = $th_thesauru;

            $this->tag->setDefault("id_thesaurus", $th_thesauru->id_thesaurus);
            $this->tag->setDefault("nombre", $th_thesauru->nombre);
            $this->tag->setDefault("is_activo", $th_thesauru->is_activo);
            $this->tag->setDefault("is_publico", $th_thesauru->is_publico);

            $this->tag->setDefault("description", 'description');

            $this->view->form = $form;
        }
    }

    /**
     * Creates a new th_thesauru
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "th_thesaurus",
                'action' => 'index'
            ]);

            return;
        }

        $th_thesauru = new ThThesaurus();
        $th_thesauru->nombre = $this->request->getPost("nombre");
        $th_thesauru->is_activo = $this->request->getPost("is_activo");
        $th_thesauru->is_publico = $this->request->getPost("is_publico");
        $th_thesauru->lang_list = $this->request->getPost("lang_list");
        $th_thesauru->xml_iso25964 = $this->request->getPost("xml_iso25964");
        $th_thesauru->aprobar_list = $this->request->getPost("aprobar_list");
        $th_thesauru->descripcion = $this->request->getPost("descripcion");
        $th_thesauru->id_creador = $this->request->getPost("id_creador");
        $th_thesauru->fecha_creado = $this->request->getPost("fecha_creado");
        $th_thesauru->fecha_modifica = $this->request->getPost("fecha_modifica");
        $th_thesauru->fecha_inactivo = $this->request->getPost("fecha_inactivo");


        if (!$th_thesauru->save()) {
            foreach ($th_thesauru->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "th_thesaurus",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("th_thesauru was created successfully");

        $this->dispatcher->forward([
            'controller' => "th_thesaurus",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a th_thesauru edited
     *
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "th_thesaurus",
                'action' => 'index'
            ]);
            return;
        }

        $id_thesaurus = $this->request->getPost("id_thesaurus");
        $th_thesauru = ThThesaurus::findFirstByid_thesaurus($id_thesaurus);

        if (!$th_thesauru) {
            $this->flash->error("th_thesauru does not exist " . $id_thesaurus);

            $this->dispatcher->forward([
                'controller' => "th_thesaurus",
                'action' => 'index'
            ]);

            return;
        }

        $th_thesauru->nombre = $this->request->getPost("nombre");
        $th_thesauru->is_activo = $this->request->getPost("is_activo");
        $th_thesauru->is_publico = $this->request->getPost("is_publico");
        $th_thesauru->lang_list = $this->request->getPost("lang_list");
        $th_thesauru->xml_iso25964 = $this->request->getPost("xml_iso25964");
        $th_thesauru->aprobar_list = $this->request->getPost("aprobar_list");
        $th_thesauru->descripcion = $this->request->getPost("descripcion");
        $th_thesauru->id_creador = $this->request->getPost("id_creador");
        $th_thesauru->fecha_creado = $this->request->getPost("fecha_creado");
        $th_thesauru->fecha_modifica = $this->request->getPost("fecha_modifica");
        $th_thesauru->fecha_inactivo = $this->request->getPost("fecha_inactivo");


        if (!$th_thesauru->save()) {

            foreach ($th_thesauru->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "th_thesaurus",
                'action' => 'edit',
                'params' => [$th_thesauru->id_thesaurus]
            ]);

            return;
        }

        $this->flash->success("th_thesauru was updated successfully");

        $this->dispatcher->forward([
            'controller' => "th_thesaurus",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a th_thesauru
     *
     * @param string $id_thesaurus
     */
    public function deleteAction($id_thesaurus)
    {
        $th_thesauru = ThThesaurus::findFirstByid_thesaurus($id_thesaurus);
        if (!$th_thesauru) {
            $this->flash->error("th_thesauru was not found");

            $this->dispatcher->forward([
                'controller' => "th_thesaurus",
                'action' => 'index'
            ]);

            return;
        }

        if (!$th_thesauru->delete()) {

            foreach ($th_thesauru->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "th_thesaurus",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("th_thesauru was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "th_thesaurus",
            'action' => "index"
        ]);
    }

}
