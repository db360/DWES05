<?php
use classes\Producto;
require_once(__DIR__ . '/src/conn.php');
require_once(__DIR__ . '/src/model/Producto.php');

$pdo = connect();
$resultado = Producto::rescatar($pdo, 'A01');
var_dump($resultado);

?>