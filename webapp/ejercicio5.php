<?php

$error = null;
$success = false;

$wsdl = 'http://localhost/dwes05/soapserver/tarea05.wsdl';
$client = new SoapClient($wsdl);

if (isset($_POST['submit']) && isset($_POST['codProducto'])) {

    $cod = trim(filter_input(INPUT_POST, 'codProducto', FILTER_SANITIZE_STRING));

    // var_dump($_POST);
    // var_dump($cod);

    try {
        $resultado = $client->detalleProducto($cod);
        // $resultado->precio = floatval($resultado->precio);
        // var_dump($resultado);

    } catch (\SoapFault $e) {
        throw new SoapFault($e->getCode(), $e->getMessage());
    }
}


if (isset($resultado) && $resultado->id > 0) {
    $success = true;
}

if (isset($resultado) && $resultado->id === -1) {
    $error = "Error: El código de producto no existe en la base de datos";
}

if (isset($resultado) && $resultado->id === -2 && $resultado->stock === 0) {
    $error = "Error: Hubo un error en la conexion a la base de datos";
}
if (isset($resultado) && $resultado->id === -2 && $resultado->stock === 1) {
    $error = "Error: Hubo un error en la consulta a la base de datos";
}
if (isset($resultado) && $resultado->id === -2 && $resultado->stock === 2) {
    $error = "Error: Hubo un error con el servicio SOAP";
}
if (isset($resultado) && $resultado->id === -3) {
    $error = "El campo código no puede estar vacío";
}


// var_dump($error);
// var_dump($success);
// var_dump($resultado);

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

    <div class="container">
        <?php if (isset($error[0])) { ?>
            <div class="campo campo-resultado">
                <h3 class="titulo-producto error">
                    <?= $error ?>
                </h3>
                <div class="campo volver">
                    <button type="submit" value="submit" class="btn btn-nuevo w20" name="submit"><a class="enlace"
                            href="ejercicio5.php">Volver</a> </button>
                </div>
            </div>
        <?php } else { ?>


            <?php if ($success === true && isset($resultado)) { ?>
                <div class="campo campo-resultado">
                    <h3 class="titulo-producto">Detalles del Producto
                        <?= $resultado->cod ?>:
                    </h3>
                    <ul class="lista-producto">
                        <li>Código: <span>
                                <?= $resultado->cod ?>
                            </span></li>
                        <li>ID: <span>
                                <?= $resultado->id ?>
                            </span></li>
                        <li>Descripción: <span>
                                <?= $resultado->desc ?>
                            </span></li>
                        <li>Precio: <span>
                                <?= $resultado->precio ?>
                            </span></li>
                        <li>Stock: <span>
                                <?= $resultado->stock ?>
                            </span> </li>
                    </ul>
                    <div class="campo volver">
                        <button type="submit" value="submit" class="btn btn-nuevo w20" name="submit"><a class="enlace"
                                href="ejercicio5.php">Volver</a> </button>
                    </div>
                </div>
            <?php } else { ?>
                <form action="ejercicio5.php" method="POST">
                    <div class="campo">
                        <h3 class="texto-modificar">Inserte el código del producto</h3>
                    </div>
                    <div class="campo">
                        <input type="text" name="codProducto" id="codProducto">
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="operacion" value="modificar">
                    </div>
                    <button type="submit" value="submit" name="submit" class="btn btn-nuevo btn-modificar">Modificar</button>
                </form>

            <?php } ?>

        <?php } ?>
    </div>
</body>

</html>