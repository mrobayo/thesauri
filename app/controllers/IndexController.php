<?php

namespace Thesaurus\Controllers;

/**
 * Index
 * @author mrobayo@gmail.com
 */
class IndexController extends ControllerBase
{
	public function initialize()
	{
		$this->tag->setTitle('Inicio');
		parent::initialize();
	}

    public function indexAction()
    {
    	//$connection = $this->db;
    	//$robot = $connection->fetchOne("select 1 dummy, version() ver");

    	$this->view->myheading = $this->config->application->appTitle; //print_r($robot, true);
    	$this->view->t = $this->getTranslation();
    }

    public function enviarAction()
    {


    }

}

