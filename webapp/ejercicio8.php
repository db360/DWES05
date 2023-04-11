<?php

var_dump($_POST);

$success = false;
$error = null;

$wsdl = 'http://localhost/dwes05/soapserver/tarea05.wsdl';

$client = new SoapClient($wsdl);

$operacion = filter_input(INPUT_POST, 'operacion', FILTER_SANITIZE_SPECIAL_CHARS);
// $redirect = trim(filter_input(INPUT_POST, 'redirect', FILTER_SANITIZE_SPECIAL_CHARS));

if (isset($_POST['id']) && isset($_POST['cod']) && isset($_POST['desc']) && isset($_POST['precio']) && isset($_POST['stock'])) {
        $producto = new stdClass();

        $producto->id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $producto->cod = filter_input(INPUT_POST, 'cod', FILTER_SANITIZE_SPECIAL_CHARS);
        $producto->desc = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_SPECIAL_CHARS);
        $producto->precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
        $producto->stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);

    if ($operacion === 'modificar') {
        //Verficiar datos


        if ($operacion === '') {

        }

        var_dump($producto);

        // $resultado = $client->modificarProducto($cod, $producto);


    } elseif ($operacion === 'incrementar_stock') {
        // $cliente->incrementarStockProducto(...);

    } elseif ($operacion === 'confirmar_modificar') {



        $resultado = $client->modificarProducto($producto->cod, $producto);

        var_dump($resultado);

        var_dump($producto);

    }
} else {
    $error = "Todos los campos son obligatorios";
}

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

            <div class="campo campo-resultado">

                <?php if (isset($producto->id) && isset($producto->id) && isset($producto->id) && isset($producto->id)) { ?>
                    <form action="ejercicio8.php" method="POST">
                        <h2 class="formsTitle">Modifique los datos del Producto:</h2>
                        <div class="campo">
                            <label for="cod">Código:</label>
                            <input class="cod" type="text" id="cod" placeholder="Codigo..." name="cod"
                                value="<?= $producto->id ?>">
                        </div>

                        <div class="campo">
                            <label for="desc">Descripción:</label>
                            <input placeholder="Descripción..." type="text" id="desc" id="desc" name="desc"
                                value="<?= $producto->desc ?>">
                        </div>

                        <div class="campo">
                            <label for="precio">Precio:</label>
                            <input placeholder="Precio..." type="text" id="precio" name="precio"
                                value="<?= $producto->precio ?>">
                        </div>

                        <div class="campo">
                            <label for="stock">Stock:</label>
                            <input placeholder="Stock..." type="text" id="stock" name="stock" value="<?= $producto->stock ?>">
                        </div>
                        <div>
                            <div class="campo volver">
                                <input type="hidden" name="cod" value="<?php echo $producto->cod ?>">
                                <input type="hidden" name="id" value="<?php echo $producto->id ?>">
                                <input type="hidden" name="desc" value="<?php echo $producto->desc ?>">
                                <input type="hidden" name="precio" value="<?php echo $producto->precio ?>">
                                <input type="hidden" type="text" name="stock" value="<?php echo $producto->stock ?>">
                                <button type="submit" value="confirmar_modificar" class="btn btn-nuevo  w40" name="operacion"><a
                                        class="enlace">Confirmar</a> </button>
                            </div>
                            <div class="campo volver">
                                <button type="submit" value="volver" class="btn btn-nuevo w40" name="submit"><a class="enlace"
                                        href="ejercicio5.php">Volver</a> </button>
                            </div>
                        </div>
                    </form>
                <?php } else { ?>
                    <form action="" method="POST">
                        <h2 class="formsTitle">Hubo un error rescatando el producto</h2>
                        <div class="campo volver">
                            <button type="submit" value="volver" class="btn btn-nuevo w40" name="submit"><a class="enlace"
                                    href="ejercicio5.php">Volver</a> </button>
                        </div>
                    <?php } ?>
            </div>
        <?php } ?>
    </div>
</body>

</html>