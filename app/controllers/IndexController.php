<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
    	$connection = $this->db;
    	$robot = $connection->fetchOne("select 1 dummy, version() ver");
    	    	
    	$this->view->myheading = print_r($robot, true);    	
    }

}

