<?php
require_once(__DIR__ . '/src/ProductosSoapHandler.php');

$productosHandler = new ProductosSoapHandler();

$resultado = $productosHandler->listarProductos();
// $resultado = $productosHandler->nuevoProducto('Adj2', 'Madera', 151, 5);
// $resultado = $productosHandler->detalleProducto(30);
// $resultado = $productosHandler->eliminarProducto(30);

var_dump($resultado);
?>