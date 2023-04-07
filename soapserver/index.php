<?php
ob_start();
require_once(__DIR__ . '/logger.php');
require_once(__DIR__.'/conf/conf.php');
require_once(__DIR__.'/src/ProductosSoapHandler.php');
    try {

        $server = new SoapServer(SOAP_URL, array('trace' => true));
        $server->setClass('ProductosSoapHandler');
        ob_clean();
        $server->handle();
        _l("Index.php - ".PHP_EOL.htmlentities(ob_get_contents()));
        ob_end_flush();
    } catch (SoapFault $e) {
        //catch error
        throw $e;
    }