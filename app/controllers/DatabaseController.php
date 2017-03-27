<?php

namespace Thesaurus\Controllers;

use Thesaurus\Thesauri\ThThesaurus;
use Thesaurus\Forms\ThesaurusForm;

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
	 *
	 * @param string $identifier
	 */
    public function indexAction($identifier)
    {
    	$this->view->myheading = $this->config->application->appTitle;
    	$this->view->id = $identifier;

    	if ($identifier != null) {
    		$this->view->entidad = ThThesaurus::findFirst([ 'iso25964_identifier = :identifier:', 'bind'=>['identifier'=> $identifier]]);
    	}
    }


}

