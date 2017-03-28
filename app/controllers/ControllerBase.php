<?php

namespace Thesaurus\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Translate\Adapter\NativeArray;
use Thesaurus\Sistema\AdConfig;

/**
 * Base
 *
 * @author mrobayo@gmail.com
 */
class ControllerBase extends Controller
{

	/**
	 * Indica si es JSON response
	 * @var boolean
	 */
	protected $_isJsonResponse = false;

	/**
	 * init
	 */
	protected function initialize() {
		$this->tag->prependTitle ( $this->config->application->appTitle . ' | ' );
		$this->view->setTemplateAfter ( 'main' );
	}

	/**
	 * Config
	 *
	 * @param string $id_config
	 * @return AdConfig
	 */
	protected function get_config($id_config) {
		$config_row = AdConfig::findFirstByid_config($id_config);

		if ($config_row) {
			return $config_row;
		}

		return FALSE;
	}

	/**
	 * Config value
	 *
	 * @param string $id_config
	 * @return unknown|boolean
	 */
	protected function get_config_value($id_config, $default = false) {
		$config = $this->get_config($id_config);

		if ($config) {
			return $config->descripcion;
		}

		return $default;
	}

	/**
	 * formato de la fecha/hora
	 */
	protected function get_ts_format() {
		return $this->get_config_value('formato_fecha', 'd/m/Y h:i');
	}

	/**
	 * MultiLingual Support
	 *
	 * @return \Phalcon\Translate\Adapter\NativeArray
	 */
	protected function getTranslation() {
		require $this->config->application->messagesDir."es.php";
		return new NativeArray ( [ "content" => $messages ] );
	}

	/**
	 * Call this func to set json response enabled
	 */
	public function json_response() {
		$this->view->disable();

		$this->_isJsonResponse = true;
		$this->response->setContentType('application/json', 'UTF-8');
	}

	/**
	 * Envia el JSON
	 * @param \Phalcon\Mvc\Dispatcher $dispatcher
	 */
	public function afterExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher) {
		if ($this->_isJsonResponse) {
			$data = $dispatcher->getReturnedValue();
			if (is_array($data)) {
				$data = json_encode($data);
			}
			$this->response->setContent($data);
		}
	}
}
