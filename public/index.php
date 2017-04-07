<?php

error_reporting(E_ALL);

use Phalcon\Mvc\Application;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

error_reporting(-1);
ini_set('display_errors', 1);

# ini_set('display_errors', 0);
# error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);

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
