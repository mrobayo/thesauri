<?php

namespace Thesaurus\Controllers;

/**
 * Database
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
	}

    public function indexAction($id_thesaurus=null)
    {
    	$this->view->myheading = $this->config->application->appTitle;
    	$this->view->t = $this->getTranslation();

    	$this->view->id = $id_thesaurus;
    }


}

