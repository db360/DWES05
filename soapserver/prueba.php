<?php
use classes\Producto;
require_once(__DIR__ . '/src/conn.php');
require_once(__DIR__ . '/src/model/Producto.php');

$pdo = connect();

// $cod = 1;
// $desc = 'dsdsdsd';
// $precio = 'd651.51';
// $stock = 2424;

// $cod = filter_var($cod, FILTER_SANITIZE_STRING);
// $desc = filter_var($desc, FILTER_SANITIZE_STRING);
// $precio = filter_var($precio, FILTER_VALIDATE_FLOAT);
// $stock = filter_var($stock, FILTER_VALIDATE_INT);

$resultado = Producto::rescatar($pdo, 'A01');
var_dump($resultado);
// var_dump($cod);
// var_dump($desc);
// var_dump($precio);
// var_dump($stock);

?>

<!--
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


-->