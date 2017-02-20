<?php
/* Parametros de la aplicacion
 * @author mrobayo@gmail.com
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

if (getenv('DATABASE_URL') !== FALSE) {
	$connection_url = parse_url(getenv('DATABASE_URL'));
	$database_cfg = [
		'adapter'     => 'Postgresql',
		'host'        => $connection_url['host'],
		'username'    => $connection_url['user'],
		'password'    => $connection_url['pass'],
		'dbname'      => explode('/', $connection_url['path'])[1],
		//'charset'     => 'utf8',
	];
}
else {
	$database_cfg = [
        'adapter'     => 'Postgresql',
        'host'        => 'localhost',
		'port' 		  => 5434,
        'username'    => 'thesauri',
        'password'    => 'thesauri',
        'dbname'      => 'thesauri'
    ];
}

return new \Phalcon\Config([
    'database' => $database_cfg,
    'application' => [

    	// Titulo
    	'appTitle'	     => 'thesauri',
    	'appPartner'	 => 'UEES',

        'appDir'         => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir'      => APP_PATH . '/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'viewsDir'       => APP_PATH . '/views/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'libraryDir'     => APP_PATH . '/library/',
    	'formsDir'       => APP_PATH . '/forms/',
    	'pluginsDir'     => APP_PATH . '/plugins/',
    	'messagesDir'    => APP_PATH . '/messages/',
        'cacheDir'       => BASE_PATH . '/cache/',

        // By web server rewrite rules. This can also be set to a static path.
        'baseUri'        => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"])
    ]
]);
