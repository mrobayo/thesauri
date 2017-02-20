<?php

namespace Thesaurus\Controllers;
use Phalcon\Mvc\Controller;
use Phalcon\Translate\Adapter\NativeArray;

/**
 * Base
 *
 * @author mrobayo@gmail.com $date$
 */
class ControllerBase extends Controller
{
	protected function initialize() {
		$this->tag->prependTitle ( $this->config->application->appTitle . ' | ' );
		$this->view->setTemplateAfter ( 'main' );
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
