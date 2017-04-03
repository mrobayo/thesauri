<?php
namespace Thesaurus\Controllers\Sistema;

use Phalcon\Flash;
use Phalcon\Session;
use Phalcon\Tag;
use Thesaurus\Forms\ThesaurusForm;
use Thesaurus\Forms\AdUsuarioForm;
use Thesaurus\Thesauri\ThThesaurus;
use Thesaurus\Sistema\AdUsuario;
use Thesaurus\Sistema\AdConfig;

/**
 * Administracion
 * @author mrobayo
 */
class AdminController extends ControllerBase
{

	static $_global_config = [];

	/*
	 * {@inheritDoc}
	 * @see \Helpdesk\Controllers\Sistema\ControllerBase::initialize()
	 */
    public function initialize()
    {
        $this->tag->setTitle('Administración');
        parent::initialize();

        // Transalation messages/es.php
        $this->view->t = $this->getTranslation();

        // Registra function en Volt
        $volt = $this->di->getShared("volt", [$this->view, $this->di]);
        $volt->getCompiler()->addFunction('config_tag', function ($resolvedArgs, $exprArgs) {
        	return get_class($this)."::config_tag(" . $resolvedArgs . ")";
        });


        // Leer configuracion
        $config = AdConfig::find([
        			"columns" => "id_config, descripcion",
        			"conditions" => "tipo_config = ?1",
        			"bind"       => [1 => "global_config"]
        ]);

        foreach ($config as $c) {
        	SELF::$_global_config[$c->id_config] = $c->descripcion;
        }
    }

    /**
     * Config Tag
     *
     * @param string $ckey
     * @param string $cvalue
     * @return string
     */
    public static function config_tag($ckey, $cvalue) {
    	$c = explode('|', $cvalue);
    	$value = isset(SELF::$_global_config[$ckey]) ? SELF::$_global_config[$ckey] : FALSE;

    	if ($value === FALSE) {
    		$value = isset($c[1])? $c[1] :'';
    	}

    	if ($c[0] == 'text') {
    		$tag = Tag::textField(["id_config[$ckey]", 'class' => 'form-control', 'value' => $value]);
    	}
    	elseif ($c[0] == 'password') {
    		$tag = Tag::passwordField(["id_config[$ckey]", 'class' => 'form-control', 'value' => $value]);
    	}
    	elseif ($c[0] == 'integer') {
    		$tag = Tag::numericField(["id_config[$ckey]", 'class' => 'form-control', 'value' => $value]);
    	}
    	elseif ($c[0] == 'select') {
    		$options = [];
    		if ($c[1] == 'boolean') {
    			$options = ['1'=>'SI', '0'=>'NO'];
    		}
    		elseif ($c[1] == 'enum') {
    			$options = array_slice($c, 2);
    		}
    		$tag = Tag::selectStatic(["id_config[$ckey]", 'class' => 'form-control', 'value' => $value], $options);
    	}
    	else {
    		$tag = $cvalue;
    	}

    	$tag .= Tag::hiddenField(["tipo_valor[$ckey]", 'value' => $c[0]]);
    	return $tag;
    }

    /**
     * Guardar configuracion
     */
    public function guardarAction()
    {
    	if ($this->request->isPost()) {
    		$exito = false;

    		$config_form = $this->request->getPost('id_config');
    		$tvalor_form = $this->request->getPost('tipo_valor');

    		foreach ($config_form as $config_key => $config_value) {
    			$this->db->begin();

    			$ad = new AdConfig();

    			$ad->id_config = $config_key;
    			$ad->tipo_config = 'global_config';
    			$ad->is_activo = 1;
    			$ad->descripcion = $config_value;
    			$ad->nombre = $config_key;
    			$ad->orden = 0;

    			$ad->tipo_prop = 'user';
    			$ad->is_requerido = 0;
    			$ad->tipo_valor = $tvalor_form[$config_key];

    			if ($ad->save() == false) {
    				foreach ($ad->getMessages() as $message) {
    					// $this->logger->error((string) $message);
    					$this->flash->error((string) $message);
    					break;
    				}
    				$this->db->rollback();
    			}
    			else {
    				$exito = true;
    				$this->db->commit();
    			}
    		}
    		if ($exito) $this->flash->success('Configuración guardada exitosamente');
    	}

    	return $this->response->redirect('sistema/admin/'.$this->request->getPost('success_forward'));
    }

    /**
     * Index
     */
    public function indexAction()
    {
    	$this->view->myheading = 'General';
    	$this->view->config_seccion = 'ajustes_general';
    }

    /**
     * Edit & Save
     */
    public function thesaurusAction($id_thesaurus = NULL)
    {
    	$this->view->myheading = 'Thesaurus';

    	if ($this->request->isPost()) {
    		$id_thesaurus = $this->request->getPost("id_thesaurus");
    	}

    	if (is_numeric($id_thesaurus)) {
    		$entidad = ThThesaurus::findFirstByid_thesaurus($id_thesaurus);

    		if (!$entidad) {
    			$this->flash->error("Thesaurus [$id_thesaurus] no encontrado");
    			return $this->dispatcher->forward([ 'controller' => "admin", 'action' => 'index' ]);
    		}
    	}
    	else {
    		$entidad = new ThThesaurus();
    	}

    	$form = new ThesaurusForm($entidad);

    	if ($this->request->isPost()) {

    		if ($form->guardar($entidad)) {

    			// $this->logger->error("Thesaurus {$entidad->nombre} guardado exitosamente");
    			return $this->dispatcher->forward( ["controller" => "admin", "action" => "index", ] );
    		}
    	}

    	$items_list = [];

    	foreach (ThThesaurus::find() as $c) {
    		// $c->xml_iso25964 = \StringHelper::xmltoArray($c->xml_iso25964);
    		$items_list[ $c->id_thesaurus ] = $c;
    	}

    	$this->view->items_list = $items_list;
    	$this->view->form = $form;
    	$this->view->entidad = $entidad;
    }


    /**
     * Edit & Save
     */
    public function usuariosAction($id = NULL)
    {
    	$this->view->myheading = 'Usuarios';

    	if ($this->request->isPost()) {
    		$id = $this->request->getPost("id_usuario");
    	}

    	if (is_numeric($id)) {
    		$entidad = AdUsuario::findFirstByid_usuario($id);

    		if (!$entidad) {
    			$this->flash->error("Usuario [$id] no encontrado");
    			return $this->dispatcher->forward([ 'controller' => 'admin', 'action' => 'index' ]);
    		}
    	}
    	else {
    		$entidad = new AdUsuario();
    	}

    	$form = new AdUsuarioForm($entidad);

    	if ($this->request->isPost())
    	{
    		if ($form->guardar($entidad))
    		{
    			// $this->logger->error("Usuario {$entidad->nombre} guardado exitosamente");
    			return $this->dispatcher->forward([ 'controller' => 'admin', 'action' => 'index' ]);
    		}
    	}

    	$items_list = [];

    	foreach (AdUsuario::find() as $c) {
    		$c->login_history = explode(',', $c->login_history);
    		if (count($c->login_history) > 0) $c->login_history = $c->login_history[0];
    		$items_list[ $c->id_usuario ] = $c;
    	}

    	$this->view->items_list = $items_list;
    	$this->view->form = $form;
    	$this->view->entidad = $entidad;
    }

    /**
     * Edit the active user profile
     *
     */
    public function profileAction()
    {
        //Get session info
        $auth = $this->session->get('auth');

        //Query the active user
        $user = Users::findFirst($auth['id']);
        if ($user == false) {
            return $this->dispatcher->forward(
                [
                    "controller" => "index",
                    "action"     => "index",
                ]
            );
        }

        if (!$this->request->isPost()) {
            $this->tag->setDefault('name', $user->name);
            $this->tag->setDefault('email', $user->email);
        }
        else {
            $name = $this->request->getPost('name', array('string', 'striptags'));
            $email = $this->request->getPost('email', 'email');

            $user->name = $name;
            $user->email = $email;
            if ($user->save() == false) {
                foreach ($user->getMessages() as $message) {
                    $this->flash->error((string) $message);
                }
            } else {
                $this->flash->success('Your profile information was updated successfully');
            }
        }

    }

}
