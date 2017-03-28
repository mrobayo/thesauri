<?php

$router = new Phalcon\Mvc\Router(false);

$router->add('/:controller/:action/:params', [
    'namespace'  => 'Thesaurus\Controllers',
    'controller' => 1,
    'action'     => 2,
    'params'     => 3,
]);

$router->add('/explorar/:params', [
		'namespace'  => 'Thesaurus\Controllers',
		'controller' => 'database',
		'action'     => 'index',
		'params'     => 1
]);

$router->add('/explorar/termino/([0-9]+)/:params', [
		'namespace'  => 'Thesaurus\Controllers',
		'controller' => 'database',
		'action'     => 'termino',
		'id_termino' => 1,
		'params'     => 2
]);


$router->add('/:controller', [
    'namespace'  => 'Thesaurus\Controllers',
    'controller' => 1
]);

$router->add('/sistema/:controller/:action/:params', [
    'namespace'  => 'Thesaurus\Controllers\Sistema',
    'controller' => 1,
    'action'     => 2,
    'params'     => 3,
]);

$router->add('/sistema/:controller', [
    'namespace'  => 'Thesaurus\Controllers\Sistema',
    'controller' => 1
]);

return $router;
