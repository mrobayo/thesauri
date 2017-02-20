<?php

namespace Thesaurus\Controllers;
/**
 *
 * @author
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
    	$connection = $this->db;
    	$robot = $connection->fetchOne("select 1 dummy, version() ver");

    	$this->view->myheading = print_r($robot, true);
    	$this->view->t = $this->getTranslation();
    }

}

