<?php
// ob_start();
require_once(__DIR__ . '/logger.php');
require_once(__DIR__.'/conf/conf.php');
require_once(__DIR__.'/src/ProductosSoapHandler.php');

    try {

        $server = new SoapServer(SOAP_URL);
        $server->setClass('ProductosSoapHandler');
        // ob_clean();
        $server->handle();

    } catch (\SoapFault $e) {
        //catch error
        _l("DATOS GENERADOS - ".PHP_EOL.htmlentities(ob_get_contents()));
        ob_end_flush();
        throw $e;
    }
    var_dump($server);
