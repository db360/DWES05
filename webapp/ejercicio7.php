<?php

$error = null;

$wsdl = 'http://localhost/dwes05/soapserver/tarea05.wsdl';
$client = new SoapClient($wsdl);

try {
   $resultado = $client->listarProductos();
   var_dump($resultado);

} catch (\SoapFault $e) {
    //throw $th;
    throw new SoapFault($e->getCode(), $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos via Soap</title>
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
                    Error Cargando la lista de productos: <?= $error ?>
                </h3>
                <div class="campo volver">
                    <button type="submit" value="submit" class="btn btn-nuevo w20" name="submit"><a class="enlace"
                            href="ejercicio7.php">Recargar</a> </button>
                </div>
            </div>
        <?php } else { ?>
            <h1 class="table-title">Lista de Productos</h1>
    <table class="table-container">
        <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Operaciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- {foreach $lista_productos as $producto} -->
            <?php foreach ($resultado->productos as $producto) { ?>
                <tr>
                    <td class="id"><?=$producto->id?></td>
                    <td class="codigo"><?=$producto->cod?></td>
                    <td class="descripcion"><?=$producto->desc?></td>
                    <td class="precio"><?=$producto->precio?>€</td>
                    <td class="stock"><?=$producto->stock?></td>
                    <td class="operaciones">

                        <div class="btn-container btn-operaciones">
                            <form method="GET" action="ejercicio6.php">
                                <a href="#">
                                    <input type="hidden" name="id" value="<?= $producto->id ?>">
                                    <button class="btn btn-editar" type="submit">Borrar!</button>
                                </a>
                            </form>
                        </div>
                    </td>
                <tr>
            <?php } ?>
        </tbody>
    </table>


            <?php } ?>

    </div>
</body>

</html>