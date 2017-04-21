<?php

namespace Thesaurus\Controllers;

use Thesaurus\Thesauri\ThTermino;
use Thesaurus\Forms\TerminoForm;
use Thesaurus\Thesauri\ThThesaurus;
use Phalcon\Version;
use Thesaurus\Forms\AdUsuarioForm;

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

     		if ($this->is_logged()) {
				$th_list =  ThThesaurus::find(['is_activo = TRUE']);
				foreach($th_list as $th) {
					$permisos_usuario = json_decode($th->aprobar_list, TRUE);

					if (! empty($permisos_usuario[ $this->view->auth['id'] ])) {
						return $this->response->redirect( $th->rdf_uri );
					}
				}

     		}
     		else {
     			// Mostrar thesauri x defecto (primario)

     			$entidad = ThThesaurus::findFirst(['is_activo = TRUE AND is_publico = TRUE AND is_primario = TRUE']);
     			if ($entidad)
     			{
     				return $this->response->redirect( $entidad->rdf_uri );
     			}
     		}


     	}
 		// Mostrar listado
 		return $this->dispatcher->forward([ 'controller' => "database", 'action' => 'index' ]);
    }

    public function sha1Action() {
    	$this->view->disable();
    	// $this->logger->error(PHP_EOL.PHP_EOL.' *********** EJEMPLO DE LOG ************* '.PHP_EOL);
    	// echo date('Ymd') . '33'; // Version::getId();

    	echo sha1('1');

    }

    /**
     * enviar termino
     */
    public function enviarAction($id_thesaurus = NULL)
    {
    	$language_list = $this->get_isocodes();
    	$thesaurus_list = [];
    	$thesaurus_lang = [];

    	foreach (ThThesaurus::find(['is_activo = TRUE', 'order' => 'nombre']) as $row)
    	{
    		// Permisos de usuarios x thesaurus
    		$permisos_usuario = json_decode($row->aprobar_list, TRUE);
    		if ($this->is_logged() && isset($permisos_usuario[ $this->view->auth['id'] ])) {
    			if (in_array($permisos_usuario[ $this->view->auth['id'] ] , [AdUsuarioForm::PERMISO_EDITOR, AdUsuarioForm::PERMISO_EXPERTO])) {
    				$thesaurus_list[ $row->id_thesaurus ] = $row->nombre;
    			}
    		}

    		// Listado de idiomas por thesaurus
    		$thesaurus_lang[ $row->id_thesaurus ] =
	    		array_filter($language_list, function($v, $k) use ($row) {
	    			return strpos($row->iso25964_language, $k) !== false;
				}, ARRAY_FILTER_USE_BOTH);
    	}
    	$this->th_options['thesaurus_list'] = $thesaurus_list;
    	$this->th_options['language_list'] = [];

   		//$id_termino = $this->request->isPost() ? $this->request->getPost("id_termino") : FALSE;
   		//$this->logger->error('fallo test!');

    	//if (is_numeric($id_termino)) {
    	//	$entidad = $this->get_termino($id_termino);
    	//	if (!$entidad) {
    	//		$this->flash->error("Termino [$id_termino] no encontrado");
    	//		return $this->dispatcher->forward([ 'controller' => "index", 'action' => 'index' ]);
    	//	}
    	//}
    	//else {
    		$entidad = new ThTermino();
    		$thesaurus = NULL;

    		if (is_numeric($id_thesaurus)) {
    			$thesaurus = $this->get_thesaurus($id_thesaurus);
    		}
    		else {
    			$thesaurus = ThThesaurus::findFirst(['is_primario = TRUE']);
    		}
    		if ($thesaurus) {
    			$entidad->id_thesaurus = $thesaurus->id_thesaurus;
    		}
    	//}

    	$form = new TerminoForm($entidad, $this->th_options);

    	//$this->logger->error('ejemplo!');

    	try {
    		if ($this->request->isPost() && $form->guardar($entidad)) {

    			$this->logger->error('sample test');

    			$thesaurus = $this->get_thesaurus($entidad->id_thesaurus);
    			return $this->response->redirect($this->config->rdf->baseUri . $thesaurus->iso25964_identifier);
    		}
    		else {
    			$this->logger->error('fallo guardar!');
    		}

    	} catch(Exception $e) {

    		$this->logger->error(  print_r(e, true) );

    	} finally {

    		$this->logger->error(  'bad :)' );

    	}



    	if (empty($thesaurus_list)) {
    		$this->flash->error("No tiene permisos para enviar un término. Por favor solicite permiso de [Editor] al Administrador.");
    		return $this->dispatcher->forward([ 'controller' => "index", 'action' => 'index' ]);
    	}

    	$items_list = [];

    	$this->view->thesaurus_lang = json_encode($thesaurus_lang);
    	$this->view->form = $form;
    	$this->view->entidad = $entidad;

    	$this->view->thesaurus = $thesaurus;
    	$this->view->myheading = 'Nuevo Término';
    }

}

