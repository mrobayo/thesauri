<?php

namespace Thesaurus\Controllers;

use Thesaurus\Thesauri\ThTermino;
use Thesaurus\Forms\TerminoForm;
use Thesaurus\Thesauri\ThThesaurus;
use Phalcon\Version;

/**
 * Index
 * @author mrobayo@gmail.com
 */
class IndexController extends \ControllerBase
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

    	$thesaurus_list = [];
    	foreach (ThThesaurus::find(['is_activo = TRUE', 'order' => 'nombre']) as $row)
    	{
    		$thesaurus_list[ $row->id_thesaurus ] = $row->nombre;
    	}
    	$this->th_options['thesaurus_list'] = $thesaurus_list;
    	$this->th_options['language_list'] = ['es' => 'Español'];
	}

	/**
	 * index
	 */
    public function indexAction()
    {
    	$this->view->myheading = $this->config->application->appTitle;
    	$this->view->modo_mantenimiento = $this->get_config_value('modo_mantenimiento', FALSE);
    	$this->view->pagina_principal = $this->get_config_value('pagina_principal', 0);
    	$this->view->t = $this->getTranslation();

    	if ($this->view->modo_mantenimiento == '1')
    	{
    		return; // continue
    	}

     	if ($this->view->pagina_principal == '1')
     	{
     		// Mostrar thesauri x defecto (primario)
 			$entidad = ThThesaurus::findFirst(['is_activo = TRUE AND is_publico = TRUE AND is_primario = TRUE']);
 			if ($entidad)
			{
				return $this->response->redirect( $entidad->rdf_uri );
			}
     	}
 		// Mostrar listado
 		return $this->dispatcher->forward([ 'controller' => "database", 'action' => 'index' ]);
    }

    public function sha1Action() {
    	$this->view->disable();

    	$this->logger->error(PHP_EOL.PHP_EOL.' *********** EJEMPLO DE LOG ************* '.PHP_EOL);
    	echo Version::getId();
    }

    /**
     * enviar termino
     */
    public function enviarAction()
    {
    	$this->view->myheading = 'Nuevo Término';
   		$id_termino = $this->request->isPost() ? $this->request->getPost("id_termino") : FALSE;

    	if (is_numeric($id_termino)) {
    		$entidad = $this->get_termino($id_termino);

    		if (!$entidad) {
    			$this->flash->error("Termino [$id_termino] no encontrado");
    			return $this->dispatcher->forward([ 'controller' => "index", 'action' => 'index' ]);
    		}
    	}
    	else {
    		$entidad = new ThTermino();

    		$thesaurus = ThThesaurus::findFirst(['is_primario = TRUE']);
    		if ($thesaurus) {
    			$entidad->id_thesaurus = $thesaurus->id_thesaurus;
    		}
    	}

    	$this->th_options['language_list'] = $this->get_isocodes();
    	$form = new TerminoForm($entidad, $this->th_options);

    	if ($this->request->isPost() && $form->guardar($entidad)) {
    		$thesaurus = $this->get_thesaurus($entidad->id_thesaurus);
    		return $this->response->redirect($this->config->rdf->baseUri . $thesaurus->iso25964_identifier);
    	}

    	$items_list = [];

    	$this->view->form = $form;
    	$this->view->entidad = $entidad;
    }

}

