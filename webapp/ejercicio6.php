<?php

$error = null;
$success = false;

$wsdl = 'http://localhost/dwes05/soapserver/tarea05.wsdl';
$client = new SoapClient($wsdl);


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = trim(filter_input(INPUT_GET, 'idProducto', FILTER_VALIDATE_INT));
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    $id = trim(filter_input(INPUT_POST, 'idProducto', FILTER_VALIDATE_INT));

    var_dump($id);

    if (!$id) {

        $error = "El ID debe de ser un numero entero mayor a 0";

    } else {

        try {
            $resultado = $client->eliminarProducto($id);

        } catch (\SoapFault $e) {
            //throw $th;
            throw new SoapFault($e->getCode(), $e->getMessage());
        }

        if ($resultado->result === 1) {

            $success = true;

        }

        if ($resultado->result <= 0) {

            $error = $resultado->descResult;
        }

    }
}

var_dump($_POST);
var_dump($_SERVER['REQUEST_METHOD']);
var_dump($resultado);
var_dump($error);
var_dump($success);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Producto via Soap</title>
    <link rel="stylesheet" href="styles/lista_productos.css">
    <link rel="stylesheet" href="styles/nuevo_producto.css">
    <link rel="stylesheet" href="styles/modificar_producto.css">
    <link rel="stylesheet" href="styles/borrar_producto.css">
</head>

<body>

    <div class="container">
        <?php if (isset($error)) { ?>
            <div class="campo campo-resultado">
                <h3 class="titulo-producto error">
                    <?= $error ?>
                </h3>
                <div class="campo volver">
                    <button type="submit" value="submit" class="btn btn-nuevo w20" name="submit"><a class="enlace"
                            href="ejercicio6.php">Volver</a> </button>
                </div>
            </div>
        <?php } else { ?>
            <?php if ($success === true) { ?>
                <div class="campo campo-resultado">
                    <h3 class="titulo-producto green">
                        Producto Correctamente eliminado
                    </h3>
                    <div class="campo volver">
                        <button type="submit" value="submit" class="btn btn-nuevo w20" name="submit"><a class="enlace"
                                href=<?= $_SERVER['REQUEST_METHOD'] === 'GET' ? "ejercicio7.php" : "ejercicio6.php" ?>>Volver</a> </button>
                    </div>
                </div>
            <?php } else { ?>

                <form action="ejercicio6.php" method="POST">
                    <div class="campo">
                        <h3 class="texto-modificar">Inserte la ID del producto a eliminar</h3>
                    </div>
                    <div class="campo">
                        <input type="text" name="idProducto" id="idProducto">
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="operacion" value="eliminar">
                    </div>
                    <button type="submit" value="submit" name="submit" class="btn btn-nuevo btn-modificar">Eliminar</button>
                </form>
            <?php } ?>

        <?php } ?>

    </div>
</body>

</html>