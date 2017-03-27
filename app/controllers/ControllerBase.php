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
	 * MultiLingual Support
	 *
	 * @return \Phalcon\Translate\Adapter\NativeArray
	 */
	protected function getTranslation() {
		require $this->config->application->messagesDir."es.php";
		return new NativeArray ( [ "content" => $messages ] );
	}
}
