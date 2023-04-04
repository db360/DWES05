<?php

$id = trim(filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT));
$cod = trim(filter_input(INPUT_POST, 'cod', FILTER_SANITIZE_SPECIAL_CHARS));
$redirect = trim(filter_input(INPUT_POST, 'redirect', FILTER_SANITIZE_SPECIAL_CHARS));

$operacion= filter_input(INPUT_POST, 'operacion', FILTER_SANITIZE_SPECIAL_CHARS);
if($operacion === 'modificar') {
    //Verficiar datos
    if($ok) {

        $client = SoapClient();
        $resultado = $client->modificarProducto($cod);

    }
} elseif ($operacion === 'incrementar_stock') {
        $cliente->incrementarStockProducto(...);
}