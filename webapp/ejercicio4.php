<?php
require_once(__DIR__ . '/../soapserver/conf/conf.php');


// if (isset($_POST['submit'])) {
//     echo "<pre>";
//     var_dump($_POST);
//     echo "</pre>";
//     $cod = trim(filter_input(INPUT_POST, 'cod', FILTER_SANITIZE_SPECIAL_CHARS));
//     $desc = trim(filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_STRING));
//     $precio = floatval(trim(filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT))) ;
//     $stock = intval(trim(filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT)));

//     $producto = new Producto($cod, $desc, $precio, $stock);
//     echo "<pre>";
//     var_dump($producto);
//     echo "</pre>";

        try {
            // Creamos el cliente con la url a el archivo wsdl del servidor.

            $client = new SoapClient(SOAP_URL);

            // $resultado = $client->__getFunctions();
            // $resultado = $client->nuevoProducto('A282', 'Hola', 51, 5);

            // llamamos el método listarProducto a través del cliente Soap
            $listaProductos = $client->listarProductos();

            // var_dump($client);
            // var_dump($resultado);
            var_dump($listaProductos);
        } catch (\SoapFault $e) {
            // var_dump($e);
            throw $e;
        }
    // } else {
    //     echo "Error: " . "El Código del producto es obligatorio";
    // }
    // var_dump($resultado);
    // var_dump($resultado2);
    // if ($resultado->result===) {
    // }
?>