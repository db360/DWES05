<?php

$error = null;
$success = false;
$hidden = false;



if (isset($_POST['submit']) && isset($_POST['cod'])) {

    try {

    $codProducto = filter_input(INPUT_POST, 'cod', FILTER_SANITIZE_STRING);

    var_dump($_POST);
    var_dump($codProducto);

        $wsdl = 'http://localhost/dwes05/soapserver/tarea05.wsdl';
        $client = new SoapClient($wsdl);
        $resultado = $client->detalleProducto($_POST['cod']);

        var_dump($resultado);

    } catch (\SoapFault $e) {

        throw new SoapFault($e->getCode(), $e->getMessage());
    }
}


if (isset($resultado) && $resultado->id > 0) {

    $resultado->precio = floatval($resultado->precio);


    $_POST['id'] = $resultado->id;
    $_POST['cod'] = $resultado->cod;
    $_POST['desc'] = $resultado->desc;
    $_POST['precio'] = $resultado->precio;
    $_POST['stock'] = $resultado->stock;


    $hidden = true;
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
var_dump($hidden);
// var_dump($_POST);
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
            <div class="campo campo-resultado <?= $hidden ? "" : "display-none" ?>">
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
                <div class="<?= $hidden ? "" : "hidden" ?>">
                    <div class="campo volver">
                        <button type="submit" value="volver" class="btn btn-nuevo  w40" name="submit"><a class="enlace"
                                href="ejercicio5.php">Volver</a> </button>
                    </div>
                    <form action="ejercicio8.php" method="POST">
                        <div class="campo volver">
                            <input type="hidden" name="cod" value="<?php echo $resultado->cod ?>">
                            <input type="hidden" name="id" value="<?php echo $resultado->id ?>">
                            <input type="hidden" name="desc" value="<?php echo $resultado->desc ?>">
                            <input type="hidden" name="precio" value="<?php echo $resultado->precio ?>">
                            <input type="hidden" type="text" name="stock" value="<?php echo $resultado->stock ?>">
                            <button type="submit" value="modificar" class="btn btn-nuevo w40 error" name="operacion"><a
                                    class="enlace">Modificar</a> </button>
                        </div>
                    </form>
                </div>
            </div>
            <form action="ejercicio5.php" method="POST" class="w20">
                <div class="campo <?= $hidden ? "display-none" : "" ?>">
                    <h3 class="texto-modificar">Inserte el código del producto</h3>
                </div>
                <div class="campo <?= $hidden ? "display-none" : "" ?>">
                    <input type="text" name="cod" value="">
                    <button type="submit" value="buscar" name="submit"
                        class="btn btn-nuevo btn-modificar w40">Buscar</button>
                </div>
            </form>

        <?php } ?>

    </div>
</body>

</html>