<?php

/* DB CONSTANSTS */
define ('DB_DSN','mysql:host=localhost;dbname=pedidos');
define ('DB_USER','root');
define ('DB_PASSWD','');
/*COSTANTES RUTAS */

$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
$baseUrl = $protocol . "://" . $_SERVER['SERVER_NAME'] . "/dwes05";

define('WSDL_FILE', __DIR__ . '/../tarea05.wsdl');
define('BASE_URL', $baseUrl);
