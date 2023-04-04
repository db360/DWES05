<?php

/* DB CONSTANSTS */
define ('DB_DSN','mysql:host=localhost;dbname=pedidos');
define ('DB_USER','root');
define ('DB_PASSWD','');
/*COSTANTES RUTAS */

$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
$baseUrl = $protocol . "://" . $_SERVER['SERVER_NAME'] . "/dwes05";

define('BASE_URL', $baseUrl);
define('SOAP_URL', 'http://localhost/dwes05/soapserver/tarea05.wsdl');