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
$server = new SoapServer('http://localhost/dwes05/soapserver/index.php?WSDL', array('uri'=>''));
$server->setClass('ProductosSoapHandler');
$server->handle();
var_dump($server);
// }
