<?php
include(__DIR__.'/logger.php');
require_once(__DIR__.'/conf/conf.php');
require_once(__DIR__.'/src/ProductosSoapHandler.php');


// header('Content-Type: text/xml; charser=utf-8');
// if(!file_exists(WSDL_FILE)) {
//     $server = new SoapServer(WSDL_FILE);
//     $server->setClass('ProductosSoapHandler');
//     $server->handle();
//     // $wsdl = new WSDLDocument('ProductosSoapHandler');
// }

// if(!file_exists(WSDL_FILE)) {
$server = new SoapServer(SOAP_URL);
$server->setClass('ProductosSoapHandler');
$server->handle();
_l("DATOS GENERADOS - ".PHP_EOL.htmlentities(ob_get_contents()));
ob_end_flush();
var_dump($server);
// }
