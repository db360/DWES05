<?php
use classes\Producto;
require_once(__DIR__ . '/src/conn.php');
require_once(__DIR__ . '/src/model/Producto.php');

try {
    if(isset($pdo) || $pdo === null) {
        echo "NO PDO";
    } else {
        $pdo = connect();
        $resultado = Producto::rescatar($pdo, 'A01');
        var_dump($resultado);
    }
    //code...
} catch (\PDOException $th) {
    var_dump($pdo);
    var_dump($th);
    //throw $th;
}


?>