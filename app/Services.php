<?php

use Phalcon\Events\Manager as EventsManager;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as FileLogger;
use Phalcon\Logger\Adapter\Stream as StreamLogger;
use Phalcon\Logger\Formatter\Line as FormatterLine;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Model\Metadata\Memory as MetaData;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Session\Adapter\Files as SessionAdapter;

/**
 * Servicios
 *
 */
class Services extends \Base\Services
{

	protected function initRouter() {
		return require APP_PATH . '/config/routes.php';
	}

    /**
     * We register the events manager
     */
    protected function initDispatcher()
    {
        $eventsManager = new EventsManager;

        // Check if the user is allowed to access certain action using the SecurityPlugin
        $eventsManager->attach('dispatch:beforeDispatch', new SecurityPlugin);

        // Handle exceptions and not-found exceptions using NotFoundPlugin
        $eventsManager->attach('dispatch:beforeException', new NotFoundPlugin);

        $dispatcher = new Dispatcher;
        $dispatcher->setDefaultNamespace('Thesaurus\Controllers');
        $dispatcher->setEventsManager($eventsManager);

        return $dispatcher;
    }

    /**
     * The URL component is used to generate all kind of urls in the application
     */
    protected function initUrl()
    {
        $url = new UrlProvider();
        $url->setBaseUri($this->get('config')->application->baseUri);
        return $url;
    }

    protected function initView()
    {
        $view = new View();

        $view->setViewsDir($this->get('config')->application->viewsDir);
        $view->setLayoutsDir($this->get('config')->application->viewsDir.'/layouts/');
        $view->registerEngines([ ".volt" => 'volt' ]);

        return $view;
    }

    /**
     * Setting up volt
     */
    protected function initSharedVolt($view, $di)
    {
        $volt = new VoltEngine($view, $di);

        $volt->setOptions(array(
            "compiledPath" => $this->get('config')->application->cacheDir
        ));

        $compiler = $volt->getCompiler();
        $compiler->addFunction('is_a', 'is_a');

        return $volt;
    }

    /**
     * Database connection is created based in the parameters defined in the configuration file
     */
    protected function initDb()
    {
        $dbconfig = $this->get('config')->get('database')->toArray();
        $dbClass = 'Phalcon\Db\Adapter\Pdo\\' . $dbconfig['adapter'];

        unset($dbconfig['adapter']);
        $db = new $dbClass($dbconfig);

//         if (! $this->get('config')->application->isHeroku) {
//         	$formatter = new FormatterLine('%date% [%type%] %message%', 'Y-m-d H:i');
//         	$logger = new FileLogger(BASE_PATH. DIRECTORY_SEPARATOR .'logs'. DIRECTORY_SEPARATOR .'sql.log');
//         	$logger->setFormatter($formatter);
//         	$eventsManager = new \Phalcon\Events\Manager();
//         	$eventsManager->attach('db', function($event, $dbClass) use ($logger) {
//         		if ($event->getType() == 'beforeQuery') {
//         	    	$logger->log($dbClass->getSQLStatement().' '.
//         	    	(is_array($dbClass->getSQLVariables()) ? join(', ', $dbClass->getSQLVariables()) : ''));
//         	    }
//         	});
//         	$db->setEventsManager($eventsManager);
//         }

        return $db;
    }

    /**
     * If the configuration specify the use of metadata adapter use it or use memory otherwise
     */
    protected function initModelsMetadata()
    {
        return new MetaData();
    }

    /**
     * Start the session the first time some component request the session service
     */
    protected function initSession()
    {
        $session = new SessionAdapter();
        $session->start();
        return $session;
    }

    /**
     * Register the flash service with custom CSS classes
     */
    protected function initFlash()
    {
        return new FlashSession(array(
            'error' => 'alert alert-danger',
            'success' => 'alert alert-success',
            'notice' => 'alert alert-info',
            'warning' => 'alert alert-warning'
        ));
    }

    /**
     * Register a user component
     */
    protected function initElements()
    {
        return new UiElements();
    }

    /**
     * Logger service
     */
    protected function initLogger() {
    	$formatter = new FormatterLine('%date% [%type%] %message%', 'Y-m-d H:i');
    	if ($this->get('config')->application->isHeroku)
    	{
    		$logger = new StreamLogger("php://stderr");
    		$logger->setFormatter($formatter);
    		$logger->setLogLevel(Logger::DEBUG); // $config->get('logger')->logLevel);
    		return $logger;
    	}
    	else
    	{
    		$logger = new FileLogger(BASE_PATH. DIRECTORY_SEPARATOR .'logs'. DIRECTORY_SEPARATOR .'app.log');
    		$logger->setFormatter($formatter);
    		$logger->setLogLevel(Logger::DEBUG); // $config->get('logger')->logLevel);
    		return $logger;
    	}
    	return null;
    }


    /**
     * Mail service
     */
    protected function initMail() {
    	return new \Mail\Mail();
    }

}
