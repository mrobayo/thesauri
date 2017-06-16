<?php

namespace Thesaurus\Controllers;

use \ThTermino;
use \ThThesaurus;
use Thesaurus\Forms\TerminoForm;
use Phalcon\Version;
use Thesaurus\Forms\AdUsuarioForm;
use Phalcon\Db\Column;

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

    /**
     * Download
     */
    public function downloadAction($id_thesaurus) {
    	$sql = "SELECT t.id_termino id_termino_concepto, t.nombre nombre_concepto, t.rdf_uri rdf_uri_concepto,
				       t.notilde notilde_concepto, t.descripcion descripcion_concepto, t.dc_source dc_source_concepto,
				       t.desambiguedad contexto_concepto, t.coip_art coip_art_concepto, t.tipo_termino tipo_termino_concepto,
				       r.tipo_relacion, r.orden_relacion,
				       e.id_termino id_termino_relacion, e.nombre nombre_relacion, e.rdf_uri rdf_uri_relacion, e.notilde notilde_relacion,
				       e.descripcion descripcion_relacion, e.dc_source dc_source_relacion, e.desambiguedad contexto_relacion,
				       e.coip_art coip_art_relacion, e.estado_termino estado_termino_relacion, e.tipo_termino tipo_termino_relacion
				  FROM th_termino t
				  LEFT JOIN th_relacion r ON (t.id_termino = r.id_termino)
				  LEFT JOIN th_termino e ON (r.id_termino_rel = e.id_termino)
				 WHERE t.estado_termino='APROBADO' AND t.tipo_termino='CONCEPTO' AND t.id_thesaurus=?
		      ORDER BY nombre_concepto, orden_relacion, nombre_relacion";
    	$result = $this->db->query($sql, [$id_thesaurus]);

    	$json = [];

    	while ($row = $result->fetch()) {
    		$id = $row['id_termino_concepto'];
    		$rdf_uri = str_replace('%', $id, $row['rdf_uri_concepto']);

    		if (array_key_exists($id, $json)) {
				$concepto =& $json[$id];
    		}
    		else {
    			$concepto = [
    				'id' => $row['id_termino_concepto'],
    				'nombre' => $row['nombre_concepto'],
    				'notilde' => $row['notilde_concepto'],
    				'definicion' => $row['descripcion_concepto'],
    				'contexto' => $row['contexto_concepto'],
    				'tipo' => $row['tipo_termino_concepto'],
    				'fuente' => $row['dc_source_concepto'],
    				'coip_art' => $row['coip_art_concepto'],
    				'rdf_uri' => $rdf_uri,
    				'relaciones' => []
    			];
    			$json[$id] =& $concepto;
    		}

    		$rdf_uri_relacion = str_replace('%', $row['id_termino_relacion'], $row['rdf_uri_relacion']);

    		$concepto['relaciones'][] = [
    			'id' => $row['id_termino_relacion'],
    			'nombre' => $row['nombre_relacion'],
    			'notilde' => $row['notilde_relacion'],
    			'definicion' => $row['descripcion_relacion'],
				'contexto' => $row['contexto_relacion'],
    			'tipo' => $row['tipo_termino_relacion'],
				'coip_art' => $row['coip_art_relacion'],
    			'rdf_uri' => $rdf_uri_relacion,
    			'relacion' => $row['tipo_relacion'],
    			'orden' => $row['orden_relacion']
    		];
    	}

    	// Thesaurus
    	$t = $this->get_thesaurus($id_thesaurus);

    	$json = [
    		'id' => $t->id_thesaurus,
    		'nombre' => $t->nombre,
    		'notilde' => $t->notilde,
    		'descripcion' => $t->iso25964_description,
    		'identifier' => $t->iso25964_identifier,
    	 	'type' => $t->iso25964_type,
    		'language' => $t->iso25964_language,
    		'rdf_uri' => $t->rdf_uri,
    		'creacion' => $t->fecha_ingreso,
    		'modificacion' => $t->fecha_modifica,
    		'ultima_actividad' => $t->ultima_actividad,
    		'terminos' => $json
    	];

    	$this->json_response();
    	echo json_encode($json);
    }

    /**
     * RDF
     */
    public function rdfAction() {

    	echo 'RDF 1 ';
    }

    public function sha1Action() {
    	$this->view->disable();

    	echo '<p>aqui';
    	$id_termino = 92;

//     	$a = TerminoForm::relaciones($id_termino);

    	echo "<pre>";

    	echo sha1('tesamin#');

// 		$x = null;
//     	foreach ($a as $k => /* @var \ThTermino*/ $v) {
//     		echo '<hr>';
//     		echo print_r($v, true);
//     		echo $x;
//     	}
    	echo "</pre>";
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
    	$this->th_options['language_list'] = $thesaurus_lang[ $id_thesaurus ];

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

    			//$this->logger->error('sample test');

    			$thesaurus = $this->get_thesaurus($entidad->id_thesaurus);
    			return $this->response->redirect($this->config->rdf->baseUri . $thesaurus->iso25964_identifier);
    		}
    		else {
    			//$this->logger->error('fallo guardar!');
    		}

    	} catch(Exception $e) {

    		$this->logger->error(  print_r(e, true) );

    	} finally {

    		//$this->logger->error(  'bad :)' );

    	}



    	if (empty($thesaurus_list)) {
    		$this->flash->error("No tiene permisos para enviar un término. Por favor solicite permiso de [Editor] al Administrador.");
    		return $this->dispatcher->forward([ 'controller' => "index", 'action' => 'index' ]);
    	}

    	$items_list = [];

    	// $this->view->thesaurus_lang = json_encode($thesaurus_lang);
    	$this->view->form = $form;
    	$this->view->entidad = $entidad;

    	$this->view->thesaurus = $thesaurus;
    	$this->view->myheading = 'Nuevo Término';
    }

}

