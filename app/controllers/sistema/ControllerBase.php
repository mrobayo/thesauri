<?php

namespace Thesaurus\Controllers\Sistema;

use Phalcon\Mvc\Controller;
use Phalcon\Translate\Adapter\NativeArray;

/**
 *
 * @author mrobayo
 */
class ControllerBase extends Controller
{
	protected function initialize()
	{
		$this->tag->prependTitle( $this->config->application->appTitle . ' | ');
		$this->view->setTemplateAfter('main');

	}

    public function afterExecuteRoute()
    {
        $this->view->setViewsDir($this->view->getViewsDir() . 'sistema/');
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
