<?php

namespace Thesaurus\Controllers;

use Thesaurus\Thesauri\ThThesaurus;
use Thesaurus\Forms\ThesaurusForm;
use Thesaurus\Thesauri\ThTermino;
use Thesaurus\Forms\TerminoForm;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url;

/**
 * Database
 * https://www.w3.org/TR/rdf-syntax-grammar/#example8
 * https://www.w3.org/TR/REC-rdf-syntax/
 *
 * https://www.w3schools.com/xml/xml_rdf.asp
 *
 * @author mrobayo@gmail.com
 */
class DatabaseController extends ControllerBase
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
    public function indexAction($identifier)
    {
    	$this->view->myheading = $this->config->application->appTitle;
    	$this->view->id = $identifier;

    	$items_list = [];

    	$entidad = FALSE;
    	$terms_list = [];
    	$letras_list = [];

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

    		// letras habilitadas del alfabeto
    		$result = $this->db->query(
    	  		"SELECT CHR(ALPHA.LETRA) LETRA, T.NUM FROM (SELECT GENERATE_SERIES( ASCII('A'), ASCII('Z')) LETRA) ALPHA
				   LEFT JOIN (SELECT UPPER(SUBSTR(T.NOTILDE, 1, 1)) LETRA, SUM(CASE T.ESTADO_TERMINO WHEN 'APROBADO' THEN 1 WHEN 'CANDIDATO' THEN 1 ELSE 0 END) NUM
 				   FROM TH_TERMINO T WHERE T.ID_THESAURUS = ?
    	  		  GROUP BY UPPER(SUBSTR(T.NOTILDE, 1, 1))) T ON (T.LETRA = CHR(ALPHA.LETRA))", [$entidad->id_thesaurus]);

    		while ($row = $result->fetch()) {
    			$letras_list[ $row['letra'] ] = $row['num'];
    		}
    	}

    	// Thesauri info
    	$this->view->entidad = $entidad;
    	$this->view->letras_list = $letras_list;
    	$this->view->terms_list = $terms_list;

    	// Listado de thesaurus
    	$this->view->items_list = $items_list;
    }

    /**
     * Presenta un termino
     */
    public function terminoAction($identifier, $id_termino) {

    	$entidad = ThTermino::findFirstByid_termino($id_termino);
    	$relaciones_list = [];

    	if (! $entidad) {
    		$entidad = new ThTermino();
    	}
    	else {
    		// Consultar Relaciones
    		$sql = 'select r.tipo_relacion, r.id_termino_rel, t.nombre, t.rdf_uri
					  from th_termino t join th_relacion r on (r.id_termino_rel = t.id_termino)
					 where r.id_termino = ?';

     		$result = $this->db->query($sql, [$entidad->id_termino]);
    		while ($row = $result->fetch()) {
    			$relaciones_list[] = $row;
    		}
    	}

    	$ultima_mod = strtotime(empty($entidad->fecha_modifica) ? $entidad->fecha_ingreso : $entidad->fecha_modifica); //date_create_from_format('Y-m-d H:i:s.u', $entidad->fecha_modifica);
    	$this->view->ultima_mod = date( $this->get_ts_format(), $ultima_mod);

    	$this->view->setRenderLevel( View::LEVEL_ACTION_VIEW );

    	$this->view->entidad = $entidad;
    	$this->view->relaciones_list = $relaciones_list;
    }

    /**
     * Editar terminos
     * @param unknown $identifier
     * @param unknown $id_termino
     */
    public function editarAction($id_termino) {
    	$entidad = ThTermino::findFirstByid_termino($id_termino);

    	if (!$entidad) {
    		$this->flash->error("Termino [$id] no encontrado");
    		return $this->dispatcher->forward([ 'controller' => 'index', 'action' => 'index' ]);
    	}

    	// Consultar thesaurus
    	$thesaurus = ThThesaurus::findFirst(['id_thesaurus = ?1', 'bind'=>[1=> $entidad->id_thesaurus] ]);
    	$isocodes_list = $this->get_isocodes( $thesaurus->iso25964_language );

    	$form = new TerminoForm($entidad, ['language_list'=>$isocodes_list]);

    	$this->view->entidad = $entidad;
    	$this->view->form = $form;

    	$this->view->thesaurus = $thesaurus;
    	$this->view->isocodes_list = $isocodes_list;

    	$this->view->myheading = 'Editar Término';
    }

	/**
	 * Consulta terminos alfabeticamente
	 * @param integer $id_thesaurus
	 */
    public function jsonAction($id_thesaurus, $letra)
    {
    	$is_admin = $this->view->auth['is_admin'];

		$conditions = "id_thesaurus = ?1 AND (estado_termino = ?2 OR estado_termino = ?4) AND notilde ILIKE ?3";
		$bind = [1 => $id_thesaurus, 2 => TerminoForm::APROBADO, 3 => $letra.'%', 4 => TerminoForm::CANDIDATO];


    	$result = ThTermino::find([
        		"columns" => "id_termino, nombre, rdf_uri, estado_termino",
        		"conditions" => $conditions, "bind" => $bind
        ]);

    	$terminos = [];
    	$url = new Url();

        foreach ($result as $c) {
        	if ($is_admin) {
        		$terminos[ $c->id_termino ] = [ $c->nombre, $url->get($c->rdf_uri), $c->estado_termino ];
        	}
        	else {
        		$terminos[ $c->id_termino ] = [ $c->nombre, ($c->estado_termino == TerminoForm::APROBADO) ? $url->get($c->rdf_uri) : '', $c->estado_termino ];
        	}

        }

    	$this->json_response();
    	echo json_encode(['id' => $id_thesaurus, 'letra' => $letra, 'result' => $terminos]);
    }

}

