<?php

error_reporting(E_ALL);

use Phalcon\Mvc\Application;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {

    /**
     * Configuracion
     */
    $config = include APP_PATH . "/config/config.php";

    /**
     * Auto-loader
     */
    require APP_PATH . '/config/loader.php';

    $application = new Application(new Services($config));
    echo $application->handle()->getContent();

} catch (Exception $e){
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
