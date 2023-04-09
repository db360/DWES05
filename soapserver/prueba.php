<?php
use classes\Producto;
require_once(__DIR__ . '/src/conn.php');
require_once(__DIR__ . '/src/model/Producto.php');

$pdo = connect();

try {

    // $resultado = Producto::rescatar($pdo, 'A01');
    // $resultado2 = Producto::listar($pdo, 10, 0);
    $resultado3 = Producto::borrar($pdo, 11);


    // foreach ($resultado as $producto) {

    //     $id = $producto->id;# code...
    //     $cod = $producto->cod;# code...
    //     $desc = $producto->desc;# code...
    //     $precio = $producto->precioprecio;# code...
    //     $stock = $producto->stock;# code...
    // }
    //code...
} catch (\PDOException $th) {
    // var_dump($pdo);
    var_dump($th);
    //throw $th;
}

// var_dump($resultado);
// var_dump($resultado2);
var_dump($resultado3);
?>

lass ProductosSoapHandler
{
    // ...

    public function incrementarStockProducto($codProducto, $incStock)
    {
        $resultado = new stdClass();

        if (!is_string($codProducto)) {
            $resultado->descResult = 'El código de producto debe ser un string';
            $resultado->result = -2;
            return $resultado;
        }

        if (!is_int($incStock) || $incStock <= 0) {
            $resultado->descResult = 'El incremento de stock debe ser un número mayor a 0';
            $resultado->result = -2;
            return $resultado;
        }

        $producto = Producto::getProductoByCod($this->pdo, $codProducto);

        if (!$producto) {
            $resultado->descResult = 'El producto no existe';
            $resultado->result = -1;
            return $resultado;
        }

        $producto->setStock($producto->getStock() + $incStock);

        $producto->update($this->pdo);

        $resultado->result = 1;
        $resultado->descResult = 'Stock incrementado correctamente';
        return $resultado;
    }

    public function modificarProducto($codProducto, $datosProducto)
    {
        $resultado = new stdClass();

        if (!is_string($codProducto)) {
            $resultado->descResult = 'El código de producto debe ser un string';
            $resultado->result = -2;
            return $resultado;
        }

        $producto = Producto::getProductoByCod($this->pdo, $codProducto);

        if (!$producto) {
            $resultado->descResult = 'El producto no existe';
            $resultado->result = -1;
            return $resultado;
        }

        if (isset($datosProducto->desc)) {
            $producto->setDesc(strval($datosProducto->desc));
        }

        if (isset($datosProducto->precio)) {
            $producto->setPrecio(floatval($datosProducto->precio));
        }

        if (isset($datosProducto->stock)) {
            $producto->setStock(intval($datosProducto->stock));
        }

        $producto->update($this->pdo);

        $resultado->result = 1;
        $resultado->descResult = 'Producto modificado correctamente';
        return $resultado;
    }

    // ...
}