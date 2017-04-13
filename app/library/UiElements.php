<?php

use Phalcon\Mvc\User\Component;

/**
 * Elementos de UI
 * @author mrobayo@gmail.com
 */
class UiElements extends Component
{

    private $_headerMenu = array(
        'navbar-left' => array(

            'index' => array(
                'caption' => '<i class="fa fa-send"></i> Enviar un Término',
                'action' => 'enviar'
            ),
            'database' => array(
                'caption' => '<i class="fa fa-search"></i> Explorar',
                'action' => 'index'
            ),
        	'sistema' => array(
        		'caption' => '<i class="fa fa-cog"></i> Sistema',
        		'action' => 'index',
        		'items' => array(
        			'sistema/admin' => array('caption'=>'Administración', 'action'=>'index')
        		)
        	),
        ),
        'navbar-right' => array(
            'session' => array(
                'caption' => 'Ingresar/ Registrarse',
                'action' => 'index'
            ),
        )
    );

    private $_tabs_admin = array(
        'General' => array(
            'controller' => 'sistema/admin',
            'action' => 'index',
            'any' => false
        ),
    	'Thesaurus' => array(
    		'controller' => 'sistema/admin',
    		'action' => 'thesaurus',
    		'any' => false
    	),
    	'Usuarios' => array(
    		'controller' => 'sistema/admin',
    		'action' => 'usuarios',
    		'any' => false
    	)
    );

    /**
     * Builds header menu
     *
     * @return string
     */
    public function getMenu()
    {
        $auth = $this->session->get('auth');

        // quitar menu admin
        if (! $auth) {
        	unset($this->_headerMenu['navbar-left']['index']);
        	unset($this->_headerMenu['navbar-left']['sistema']);
        }
        else {
        	if (!isset($auth['is_admin']) || !$auth['is_admin']) {
        		unset($this->_headerMenu['navbar-left']['sistema']);
        	}
        }

        // session
		if ($auth) {
        	$this->_headerMenu['navbar-right']['session'] = ['caption' => '<i class="fa '. ($auth['is_admin'] ? 'fa-user': 'fa-user-o') .'"></i> '.$auth['nombre'], 'action' => 'end'];
        }

        $controllerName = $this->view->getControllerName();
        echo '<div class="collapse navbar-collapse" id="navbarMain">';
        foreach ($this->_headerMenu as $position => $menu) {
            echo '<ul class="navbar-nav ',($position=='navbar-left' ? 'mr-auto':''),'">';

            foreach ($menu as $controller => $option) {

  				echo '<li class="nav-item '. (isset($option['items']) ? 'dropdown' :'') .' '.($controllerName == $controller ? 'active': '').'">';

  				if (isset($option['items'])){
  					echo $this->tag->linkTo(
  							[
  							 'action' => "#",
  							 'text' => $option['caption'],
  							 'class'=>"nav-link dropdown-toggle",
  							  'data-toggle'=>"dropdown",  'aria-haspopup'=>"true", 'aria-expanded'=>"false"
  							 ]);

  					echo '<div class="dropdown-menu">';
  					foreach ($option['items'] as $submenu => $suboption) {
  						echo $this->tag->linkTo([
  								'action' => $submenu . '/' . $suboption['action'],
  								'text'=>$suboption['caption'],
  								'class'=>"dropdown-item",
  						]);
  					}
  					echo '</div>';
  				}
  				else {
  					echo $this->tag->linkTo([
  							'action' => $controller . '/' . $option['action'],
  							'text'=>$option['caption'],
  							'class'=> 'nav-link',
  					]);

  				}
                echo '</li>';
            }

            echo '</ul>';
        }

        echo '</div>';

    }

    /**
     * Returns menu tabs for Admin
     */
    public function getTabsAdmin()
    {
        $controllerName = $this->view->getControllerName();
        $actionName = $this->view->getActionName();
        echo '<ul class="nav nav-tabs" role="tablist">';
        foreach ($this->_tabs_admin as $caption => $option) {

            if ( ($option['action'] == $actionName )) {
                echo '<li class="nav-item">';
            } else {
                echo '<li class="nav-item">';
            }
            echo $this->tag->linkTo([
            		'action' => $option['controller'] . '/' . $option['action'],
            		'text' => $caption,
            		'class'=>'nav-link'. ($option['action'] == $actionName ? ' active' : ''),
            		//'data-toggle'=> 'tab', 'role'=>'tab' !esto impide la navegacion!
            ]), '</li>';
        }
        echo '</ul>';
    }

    /**
     * Returns menu tabs for Catalogo
     */
    public function getTabs()
    {
    	$controllerName = $this->view->getControllerName();
    	$actionName = $this->view->getActionName();
    	echo '<ul class="nav nav-tabs">';
    	foreach ($this->_tabs_catalogo as $caption => $option) {
    		// if ($option['controller'] == $controllerName && ($option['action'] == $actionName || $option['any'])) {
    		if ( ($option['action'] == $actionName )) {
    			echo '<li role="presentation" class="active">';
    		} else {
    			echo '<li>';
    		}
    		echo $this->tag->linkTo($option['controller'] . '/' . $option['action'], $caption), '</li>';
    	}
    	echo '</ul>';
    }

}
