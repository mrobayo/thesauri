<?php

namespace Thesaurus\Controllers;

use Thesaurus\Thesauri\ThThesaurus;
use Thesaurus\Forms\ThesaurusForm;
use Thesaurus\Thesauri\ThTermino;
use Thesaurus\Forms\TerminoForm;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url;
use Thesaurus\Forms\AdUsuarioForm;

/**
 * Database
 * https://www.w3.org/TR/rdf-syntax-grammar/#example8
 * https://www.w3.org/TR/REC-rdf-syntax/
 *
 * https://www.w3schools.com/xml/xml_rdf.asp
 *
 * @author mrobayo@gmail.com
 */
class DatabaseController extends \ControllerBase
{

	public function initialize()
	{
		$this->tag->setTitle('Database');
		parent::initialize();

		// Transalation messages/es.php
		$this->view->t = $this->getTranslation();

		$this->view->TYPES = ThesaurusForm::DEFAULT_TYPES;
		$this->view->RELATION_TYPES = TerminoForm::RELATION_TYPES;
	}

	/**
	 * Index
	 * @param string $identifier
	 */
    public function indexAction($identifier = NULL)
    {
    	$this->view->myheading = $this->config->application->appTitle;
    	$this->view->id = $identifier;

    	$items_list = [];

    	$entidad = FALSE;
    	$terms_list = [];
    	$letras_list = [];

    	$permiso_enviar = false;
    	$permiso_thesaurus = FALSE;


    	if ($identifier != null) {
    		$entidad = ThThesaurus::findFirst([ 'iso25964_identifier = :identifier:', 'bind'=>['identifier'=> $identifier]]);
    	}

    	if (! $entidad)
    	{
    		$items = ThThesaurus::find(["conditions" => "is_activo = TRUE AND is_publico = TRUE"]);
	    	foreach ($items as $c) {
    			$c->ultima_actividad = date( $this->get_ts_format(), strtotime($c->ultima_actividad));
	    		$items_list[ $c->id_thesaurus ] = $c;
	    	}
    	}
    	else
    	{
    		$entidad->ultima_actividad = date( $this->get_ts_format(), strtotime($entidad->ultima_actividad));

    		// mostrar terminos

    		//$terms_list = ThTermino::find([ 'id_thesaurus = ?1 AND estado_termino = ?2',
    		//		'bind' => [1 => $entidad->id_thesaurus, 2 => TerminoForm::APROBADO ], 'order' => 'nombre ASC']);

    		$params = [$entidad->id_thesaurus];

    		if ($this->is_logged()) {
    			$permisos_usuario = json_decode($entidad->aprobar_list, TRUE);

    			if (isset($permisos_usuario[ $this->view->auth['id'] ])) {
    				$permiso_thesaurus = $permisos_usuario[ $this->view->auth['id'] ];
    				$permiso_enviar = in_array($permisos_usuario[ $this->view->auth['id'] ] , [AdUsuarioForm::PERMISO_EDITOR, AdUsuarioForm::PERMISO_EXPERTO]);
    			}

    			$sql_letras =
    			"SELECT CHR(ALPHA.LETRA) LETRA, T.NUM FROM (SELECT GENERATE_SERIES( ASCII('A'), ASCII('Z')) LETRA) ALPHA
				   LEFT JOIN (SELECT UPPER(SUBSTR(T.NOTILDE, 1, 1)) LETRA, SUM(CASE T.ESTADO_TERMINO WHEN 'APROBADO' THEN 1 WHEN 'CANDIDATO' THEN 1 ELSE 0 END) NUM
 				   FROM TH_TERMINO T WHERE T.ID_THESAURUS = ?
    	  		  GROUP BY UPPER(SUBSTR(T.NOTILDE, 1, 1))) T ON (T.LETRA = CHR(ALPHA.LETRA))";
    		}
    		else {
    			$sql_letras =
    			"SELECT CHR(ALPHA.LETRA) LETRA, T.NUM FROM (SELECT GENERATE_SERIES( ASCII('A'), ASCII('Z')) LETRA) ALPHA
				   LEFT JOIN (SELECT UPPER(SUBSTR(T.NOTILDE, 1, 1)) LETRA, SUM(CASE T.ESTADO_TERMINO WHEN 'APROBADO' THEN 1 WHEN 'CANDIDATO' THEN 1 ELSE 0 END) NUM
 				   FROM TH_TERMINO T WHERE T.ID_THESAURUS = ? AND T.ESTADO_TERMINO = ?
    	  		  GROUP BY UPPER(SUBSTR(T.NOTILDE, 1, 1))) T ON (T.LETRA = CHR(ALPHA.LETRA))";

    			$params[] = TerminoForm::APROBADO;
    		}

    		// letras habilitadas del alfabeto
    		$result = $this->db->query($sql_letras, $params);

    		while ($row = $result->fetch()) {
    			$letras_list[ $row['letra'] ] = $row['num'];
    		}
    	}

    	// Thesauri info
    	$this->view->entidad = $entidad;
    	$this->view->letras_list = $letras_list;
    	$this->view->terms_list = $terms_list;
    	$this->view->permiso_enviar = $permiso_enviar;
    	$this->view->permiso_thesaurus = $permiso_thesaurus;

    	// Listado de thesaurus
    	$this->view->items_list = $items_list;
    }

    /**
     * Presenta un termino
     */
    public function terminoAction($identifier, $id_termino) {

    	$entidad = $this->get_termino($id_termino);
    	$relaciones_list = [];
    	$thesaurus = FALSE;
    	$permiso_editar = FALSE;

    	if (! $entidad) {
    		$entidad = new ThTermino();
    	}
    	else {
    		// Consultar Relaciones
    		$sql = 'select r.orden_relacion, r.tipo_relacion, r.id_termino_rel, t.nombre, t.rdf_uri
					  from th_termino t
    				  join th_relacion r on (r.id_termino_rel = t.id_termino)
					 where r.id_termino = ? order by r.orden_relacion, t.nombre';

     		$result = $this->db->query($sql, [$entidad->id_termino]);

    		while ($row = $result->fetch()) {
    			$tipo_relacion = $row['tipo_relacion'];
    			$relaciones_list[ $tipo_relacion ][] = $row;
    		}

    		$thesaurus = $this->get_thesaurus($entidad->id_thesaurus);
    		$permisos_usuario = json_decode($thesaurus->aprobar_list, TRUE);

    		if ($this->is_logged() && isset($permisos_usuario[ $this->view->auth['id'] ])) {
    			$permiso_editar = in_array($permisos_usuario[ $this->view->auth['id'] ] , [AdUsuarioForm::PERMISO_EXPERTO]);
    		}
    	}

    	$ultima_mod = strtotime(empty($entidad->fecha_modifica) ? $entidad->fecha_ingreso : $entidad->fecha_modifica);
    	$this->view->ultima_mod = date( $this->get_ts_format(), $ultima_mod);

    	if ($this->request->isPost()) {
    		$this->view->setRenderLevel( View::LEVEL_ACTION_VIEW );
    	}

    	$this->view->entidad = $entidad;
    	$this->view->thesaurus = $thesaurus;
    	$this->view->permiso_editar = $permiso_editar;
    	$this->view->rdf_uri = str_replace('%', $entidad->id_termino, $entidad->rdf_uri);
    	$this->view->relaciones_list = $relaciones_list;
    }

    /**
     * Editar termino
     *
     * @param integer $id_termino
     */
    public function editarAction($id_termino) {
    	$entidad = $this->get_termino($id_termino);

    	if (! $entidad)
    	{
    		$this->flash->error("Termino [$id_termino] no encontrado");
    		return $this->dispatcher->forward([ 'controller' => 'index', 'action' => 'index' ]);
    	}

    	// Consultar thesaurus
    	$thesaurus = $this->get_thesaurus($entidad->id_thesaurus);
    	$isocodes_list = $this->get_isocodes( $thesaurus->iso25964_language );

    	$form = new TerminoForm($entidad, ['language_list'=>$isocodes_list]);

   		if ($this->request->isPost() && $form->guardar($entidad))
   		{
   			return $this->response->redirect($thesaurus->rdf_uri);
   		}

    	$this->view->entidad = $entidad;
    	$this->view->form = $form;

    	$this->view->thesaurus = $thesaurus;
    	$this->view->isocodes_list = $isocodes_list;
    	$this->view->comentarios = [];

    	$this->view->myheading = 'Editar Término';
    }

    /**
     * Guardar un termino
     */
    public function guardarAction() {
		//$entidad = $this->get_termino( $this->request->getPost('id_termino') );
		//$form = new TerminoForm($entidad, $this->th_options);
    }

    /**
     * Aprobar un termino
     */
    public function aprobarAction() {
    	//$entidad = $this->get_termino( $this->request->getPost('id_termino') );
    	//$form = new TerminoForm($entidad, $this->th_options);
    }

    /**
     * Comentario un termino
     */
    public function comentarAction() {
    	//$entidad = $this->get_termino( $this->request->getPost('id_termino') );
    	//$form = new TerminoForm($entidad, $this->th_options);
    }

	/**
	 * Consulta terminos alfabeticamente
	 * @param integer $id_thesaurus
	 */
    public function jsonAction($id_thesaurus, $letra)
    {
    	$is_admin = $this->view->auth['is_admin'];

    	if ($this->is_logged()) {
    		$conditions = "id_thesaurus = ?1 AND (estado_termino = ?2 OR estado_termino = ?4) AND notilde ILIKE ?3";
    		$bind = [1 => $id_thesaurus, 2 => TerminoForm::APROBADO, 3 => $letra.'%', 4 => TerminoForm::CANDIDATO];
    	}
    	else {
    		$conditions = "id_thesaurus = ?1 AND (estado_termino = ?2) AND notilde ILIKE ?3";
    		$bind = [1 => $id_thesaurus, 2 => TerminoForm::APROBADO, 3 => $letra.'%'];
    	}

    	$result = ThTermino::find([
        		"columns" => "id_termino, nombre, rdf_uri, estado_termino",
        		"conditions" => $conditions, "bind" => $bind
        ]);

    	$terminos = [];
    	$url = new Url();

        foreach ($result as $c) {
        	$rdf_uri = $url->get( str_replace('%', $c->id_termino, $c->rdf_uri) );
        	$rdf_uri = ($is_admin || $c->estado_termino == TerminoForm::APROBADO) ? $rdf_uri : '';

        	$terminos[ $c->id_termino ] = [ $c->nombre, $rdf_uri, $c->estado_termino ];
        }

    	$this->json_response();
    	echo json_encode(['id' => $id_thesaurus, 'letra' => $letra, 'result' => $terminos]);
    }

    /**
     * Arbor
     * @param unknown $identifier
     */
    public function arborAction($identifier = NULL)
    {


    }

}

