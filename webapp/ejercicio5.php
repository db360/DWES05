<?php

$error[] = null;
$success = false;

if (isset($_POST['submit']) && isset($_POST['cod'])) {


    $wsdl = 'http://localhost/dwes05/soapserver/tarea05.wsdl';
    $client = new SoapClient($wsdl);

    $cod = trim(filter_input(INPUT_POST, 'cod', FILTER_SANITIZE_STRING));

    var_dump($_POST);

    try {
        $resultado = $client->detalleProducto($cod);
        $resultado->precio = floatval($resultado->precio);
        $success = true;


    } catch (\SoapFault $e) {
        $error[0] = ("Hubo un error: " . $e->getMessage());
    }
}

if (isset($resultado) && isset($resultado->result) && $resultado->result < 0) {
    $error[0] = "Hubo un error: " . $resultado->descResult;
}
if (isset($resultado) && isset($resultado->result) && $resultado->result === 1) {
    echo $resultado->descResult;
}

if (isset($error[0])) {
    echo "ERROR: " . $error[0];
}

var_dump($error);
var_dump($success);
var_dump($resultado);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Producto via Soap</title>
    <link rel="stylesheet" href="styles/lista_productos.css">
    <link rel="stylesheet" href="styles/nuevo_producto.css">
    <link rel="stylesheet" href="styles/modificar_producto.css">
</head>

<body>
    <div class="container  {if $errorguardar != '' ||  $success != ''}transparent{/if}">
        <?php if ($success === true && isset($resultado)) { ?>
            <div class="campo campo-resultado">
                <h3 class="titulo-producto">Producto: </h3>
                <ul class="lista-producto">
                    <li>Código:  <span><?=$resultado->cod ?></span></li>
                    <li>ID:  <span><?=$resultado->id ?></span></li>
                    <li>Descripción:  <span><?=$resultado->desc ?></span></li>
                    <li>Precio:  <span><?=$resultado->precio ?></span></li>
                    <li>Stock:  <span><?=$resultado->stock ?></span> </li>
                </ul>
            </div>
        <?php } else { ?>
            <form action="ejercicio5.php" method="POST">
                <div class="campo">
                    <h3 class="texto-modificar">Inserte el código del producto</h3>
                </div>
                <div class="campo">
                    <input type="text" name="cod" id="">
                    <input type="hidden" name="id" value="">
                    <input type="hidden" name="operacion" value="modificar">
                </div>
                <button type="submit" value="submit" name="submit" class="btn btn-nuevo btn-modificar">Modificar</button>
            </form>

        <?php } ?>
    </div>
</body>

</html>