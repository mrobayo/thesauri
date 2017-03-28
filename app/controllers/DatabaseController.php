<?php

namespace Thesaurus\Controllers;

use Thesaurus\Thesauri\ThThesaurus;
use Thesaurus\Forms\ThesaurusForm;
use Phalcon\Db\RawValue;
use Thesaurus\Thesauri\ThTermino;
use Thesaurus\Forms\TerminoForm;

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
	    		$items_list[ $c->id_thesaurus ] = $c;
	    	}
    	}
    	else
    	{
    		// mostrar terminos
    		$terms_list = ThTermino::find([ 'id_thesaurus = ?1 AND estado_termino = ?2',
    				'bind' => [1 => $entidad->id_thesaurus, 2 => TerminoForm::APROBADO ], 'order' => 'nombre ASC']);

    		// letras habilitadas del alfabeto
    		$result = $this->db->query(
    	  		"SELECT CHR(ALPHA.LETRA) LETRA, T.NUM FROM (SELECT GENERATE_SERIES( ASCII('A'), ASCII('Z')) LETRA) ALPHA
				   LEFT JOIN (SELECT UPPER(SUBSTR(T.NOTILDE, 1, 1)) LETRA, SUM(CASE T.ESTADO_TERMINO WHEN 'APROBADO' THEN 1 ELSE 0 END) NUM
 				   FROM TH_TERMINO T WHERE T.ID_THESAURUS = ?
    	  		  GROUP BY UPPER(SUBSTR(T.NOTILDE, 1, 1))) T ON (T.LETRA = CHR(ALPHA.LETRA))", [$entidad->id_thesaurus]);

    		while ($row = $result->fetch()) {
    			$letras_list[ $row['letra'] ] = $row['num'];
    		}
    	}

    	$this->view->entidad = $entidad;
    	$this->view->letras_list = $letras_list;
    	$this->view->terms_list = $terms_list;

    	$this->view->items_list = $items_list;

    }


    /**
     * Presenta un termino
     */
    public function terminoAction($identifier, $id_termino) {
		$this->view->disable();
		echo 'muestra un termino: '. $id_termino . ' = '. $identifier;
    }

	/**
	 * Consulta terminos alfabeticamente
	 * @param integer $id_thesaurus
	 */
    public function jsonAction($id_thesaurus, $letra)
    {
    	$result = ThTermino::find([
        			"columns" => "id_termino, nombre, rdf_uri",
        			"conditions" => "id_thesaurus = ?1 AND estado_termino = ?2 AND notilde ILIKE ?3",
        			"bind"       => [1 => $id_thesaurus, 2 => TerminoForm::APROBADO, 3 => $letra.'%']
        ]);

    	$terminos = [];
        foreach ($result as $c) {
        	$terminos[ $c->id_termino ] = [ $c->nombre, $c->rdf_uri ];
        }

    	$this->json_response();
    	echo json_encode(['id' => $id_thesaurus, 'letra' => $letra, 'result' => $terminos]);
    }

}

