<?php

namespace Thesaurus\Controllers;

use Thesaurus\Thesauri\ThTermino;
use Thesaurus\Forms\TerminoForm;
use Thesaurus\Sistema\AdConfig;

/**
 * Index
 * @author mrobayo@gmail.com
 */
class IndexController extends ControllerBase
{

	/**
	 * Inicializa opciones
	 * @var array
	 */
	var $th_options;

	/**
	 * {@inheritDoc}
	 * @see \Thesaurus\Controllers\ControllerBase::initialize()
	 */
	public function initialize()
	{
		$this->tag->setTitle('Inicio');
		parent::initialize();

		$this->th_options['thesaurus_list'] = [1 => 'COIP'];
		$this->th_options['language_list'] = ['es' => 'Español'];
	}

	/**
	 * index
	 */
    public function indexAction()
    {
    	$this->view->myheading = $this->config->application->appTitle;
    	$this->view->modo_mantenimiento = $this->get_config_value('modo_mantenimiento', FALSE);

    	$this->logger->error('prueba: [' . $this->view->modo_mantenimiento . ']');

    	$this->view->t = $this->getTranslation();
    }

    /**
     * enviar termino
     */
    public function enviarAction()
    {
    	$this->view->myheading = 'Nuevo Término';
   		$id_termino = $this->request->isPost() ? $this->request->getPost("id_termino") : FALSE;

    	if (is_numeric($id_termino)) {
    		$entidad = ThThesaurus::findFirstByid_termino($id_termino);

    		if (!$entidad) {
    			$this->flash->error("Termino [$id_termino] no encontrado");
    			$this->dispatcher->forward([ 'controller' => "admin", 'action' => 'index' ]);
    			return;
    		}
    	}
    	else {
    		$entidad = new ThTermino();
    	}

    	$form = new TerminoForm($entidad, $this->th_options);

    	if ($this->request->isPost()) {
    		if ($form->guardar($entidad)) {
    			$this->logger->error('guardado exitosmente :D');
    			return $this->dispatcher->forward( ["controller" => "admin", "action" => "index", ] );
    		}
    	}

    	$items_list = [];

    	$this->view->form = $form;
    	$this->view->entidad = $entidad;
    }

}

