<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
    	$config->application->formsDir,
    	$config->application->pluginsDir,
    	$config->application->libraryDir
    ]
)->register();

$loader->registerNamespaces([
		'Thesaurus\Thesauri' 		    => $config->application->modelsDir . '/thesauri',
		'Thesaurus\Sistema'      		=> $config->application->modelsDir . '/sistema',
		'Thesaurus\Controllers'			=> $config->application->controllersDir,
		'Thesaurus\Controllers\Sistema'	=> $config->application->controllersDir . '/sistema',
]);

$loader->registerClasses([
		'Services' => APP_PATH . '/Services.php'
]);
