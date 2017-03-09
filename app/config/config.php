<?php
/* Parametros de la aplicacion
 * @author mrobayo@gmail.com
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

if (getenv('DATABASE_URL') !== FALSE) {
	// HEROKU
	$connection_url = parse_url(getenv('DATABASE_URL'));
	$database_cfg = [
		'adapter'     => 'Postgresql',
		'host'        => $connection_url['host'],
		'username'    => $connection_url['user'],
		'password'    => $connection_url['pass'],
		'dbname'      => explode('/', $connection_url['path'])[1]
	];
	$base_uri = '/';
	$is_heroku = true;
}
else {
	// LOCAL
	$database_cfg = [
        'adapter'     => 'Postgresql',
        'host'        => 'localhost',
		'port' 		  => 5432,
        'username'    => 'thesauri',
        'password'    => 'thesauri',
        'dbname'      => 'thesauri'
    ];
	$base_uri = '/thesauri/';
	$is_heroku = false;
}

return new \Phalcon\Config([
    'database' => $database_cfg,
    'application' => [
    	'isHeroku'		 => $is_heroku,
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
        'baseUri'        => $base_uri, //preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"])
    	'publicUrl'      => 'thesauri.herokuapp.com',
    ],
	'mail' => [
				'fromName' => 'Thesauri - UEES',
				'fromEmail' => getenv('GMAIL_USER'),
				'smtp' => [
						'server' => 'smtp.gmail.com',
						'port' => 587,
						'security' => 'tls',
						'username' => getenv('GMAIL_USER'),
						'password' => getenv('GMAIL_PASSWD')
				]
	],

	// Dominio de RDF
	'rdf' => [
			'baseUri' => 'database/'
	]
]);
