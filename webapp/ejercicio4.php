<?php
require_once(__DIR__ . '/../soapserver/conf/conf.php');

$error[] = null;

if (isset($_POST['submit'])) {

    echo "<pre>";
    var_dump($_POST);
    echo "</pre>";

    try {
        // Creamos el cliente con la url a el archivo wsdl del servidor.
        $wsdl = 'http://localhost/dwes05/soapserver/tarea05.wsdl';
        $client = new SoapClient($wsdl);

        $cod = trim(filter_input(INPUT_POST, 'cod', FILTER_SANITIZE_STRING));
        $desc = trim(filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_STRING));
        $precio = trim(filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT));
        $stock = trim(filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT));

        $producto = new stdClass();
        $producto->cod = $cod;
        $producto->desc = $desc;
        $producto->precio = floatval($precio);
        $producto->stock = intval($stock);

        // var_dump($producto);
        // llamamos el método nuevoProducto a través del cliente Soap

        $nuevoProducto = new stdClass();
        
        $nuevoProducto = $client->nuevoProducto($producto);

        if (isset($nuevoProducto->faultstring)) {
            var_dump($nuevoProducto->faultstring);
        }
        if (isset($nuevoProducto->error)) {
            var_dump($nuevoProducto->error);
        }
        echo "<pre>";
        var_dump($producto);
        echo "</pre>";
        // print_r($nuevoProducto->result);
        // print_r($nuevoProducto->result);
        print_r($nuevoProducto->descResult);
        // print_r($nuevoProducto->faultstring);
        // var_dump($descResult);


        // // llamamos el método listarProducto a través del cliente Soap
        // $listaProductos = $client->listarProductos();
        // var_dump($listaProductos);

        // var_dump($client);
    } catch (\SoapFault $e) {
        // var_dump($e);
        $error[0] = ("Hubo un error: " . $e->getMessage());

    }

    if (isset($error[0])) {
        echo ($error[0]);
    }

}
// var_dump($resultado);
// var_dump($resultado2);
// if ($resultado->result===) {
// }




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Nuevo Producto via Soap</title>
    <link rel="stylesheet" href="styles/lista_productos.css">
    <link rel="stylesheet" href="styles/nuevo_producto.css">
</head>

<body>
    <div class="container  {if $errorguardar != '' ||  $success != ''}transparent{/if}">
        <!-- {if $errores}
            <ul>
              {foreach $errores as $error}
                <li class="error">{$error}</li>
              {/foreach}
            </ul>
          {/if}
        {if $errorguardar} -->
        <!-- <div class="resultado">
                <h1 class="succes-title">{$errorguardar}</h1>                            .
                <a href="{$rooturl}/nuevoproducto">
                <form method="post">
                  <button type="submit" class="btn btn-nuevo succes-btn">Volver</button>
                </form>
                </a>
            </div> -->
        <!-- {/if}
        {if $success} -->
        <!-- <div class="resultado">
                <h1 class="succes-title">{$success}</h1>                            .
                <a href="{$rooturl}">
                    <button class="btn btn-nuevo succes-btn">Inicio</button>
                </a>
            </div>
        {/if} -->


        <form action="ejercicio4.php" method="POST">
            <h2 class="formsTitle">Añadir Nuevo Producto via Soap:</h2>
            <div class="campo campoNombre">
                <label for="cod">Código:</label>
                <input class="cod" type="text" id="cod" placeholder="Codigo..." name="cod">
            </div>

            <div class="campo">
                <label for="desc">Descripción:</label>
                <input placeholder="Descripción..." type="text" id="desc" id="desc" name="desc">
            </div>

            <div class="campo">
                <label for="precio">Precio:</label>
                <input placeholder="Precio..." type="text" id="precio" name="precio">
            </div>

            <div class="campo">
                <label for="stock">Stock:</label>
                <input placeholder="Stock..." type="text" id="stock" name="stock">
            </div>
            <div class="campo">
            <button type="submit" value="submit" class="btn btn-nuevo w20" name="submit">Enviar</button>
            </div>
        </form>
    </div>
</body>

</html>