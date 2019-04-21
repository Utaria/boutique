<?php
define('VERSION', '0.2.2');

ini_set('session.cookie_domain', '.utaria.fr');
session_start();

error_reporting(E_ALL);
ini_set("display_errors", 1);
date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.utf8','fra');
ini_set('memory_limit', '-1'); 

define('WEBROOT', dirname(__FILE__));
define('ROOT', dirname(WEBROOT));
define('DS', DIRECTORY_SEPARATOR);
define('SRC', ROOT . DS . 'src');

$config = require '../config.php';

require SRC . DS . 'functions.php';
require SRC . DS . 'core/database.php';
require SRC . DS . 'core/Controller.php';
require SRC . DS . 'core/Router.php';

// Init Database
$database = new Database();
$database->connect(
    $config['database']['host'],
    $config['database']['user'],
    $config['database']['pass'],
    $config['database']['name']
);

$router = new Router($config, $database);

$router->addRoute("boutique/choix-produit/*", "boutique/choix-produit");
$router->addRoute("boutique/confirmer-choix-produit/*", "boutique/confirmer-choix-produit");
$router->addRoute("boutique/entrer-code-paysafecard/*", "boutique/entrer-code-paysafecard");

$router->load();
?>