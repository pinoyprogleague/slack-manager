<?php

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/app/autoload.php';

global $routes;
$routes = array( );
require_once __DIR__.'/app/routes.php';


/**
 * ALLEN: Initialise constants
 */
define( 'ROOT', __DIR__ );



$url_parsed = parse_url($_SERVER['REQUEST_URI']);
$_path = isset($url_parsed['path']) ? $url_parsed['path'] : null;
$_query = isset($url_parsed['query']) ? $url_parsed['query'] : null;

if (!isset($routes[$_path])) {
    header('location: /404');
    exit;
}

// Otherwise call it
$call_meta = explode('@', $routes[$_path]);
$class = $call_meta[0];
$method = $call_meta[1];

$instance = new $class();
$instance->$method();