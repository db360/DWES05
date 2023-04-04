<?php
use classes\Producto;

require_once(__DIR__.'/conn.php');
require_once(__DIR__.'/model/Producto.php');

/**
 * [Description ProductosSoapHandler]
 * Actividad Tema 5: DWES
 * @author David Martínez de la Torre
 *
 */
class ProductosSoapHandler {

    private $pdo;
    /**
     * Constructor para establecer conexión con la base de datos y guardar la conexion.
     */
    public function __construct() {
        $this->pdo = connect();
    }

    /**
     * @param string $cod
     * @param string $desc
     * @param float $precio
     * @param int $stock
     *
     * @return [int|boolean]
     */
    public function nuevoProducto($cod, $desc, $precio, $stock) {

        try {
            $producto = new Producto($cod, $desc, $precio, $stock);
            $resultado = $producto->guardar($this->pdo);
            return $resultado;
        } catch (\SoapFault $e) {
            // Error
            throw new \SoapFault("Error Creando Producto: ",  $e->getMessage());

        }
    }
    /**
     * @param int $codProducto
     *
     * @return [object|null]
     */
    public function detalleProducto($codProducto) {
        try {
            $resultado = Producto::rescatar($this->pdo, $codProducto);
            return $resultado;

        } catch (\SoapFault $e) {
            // Error
            throw new \SoapFault("Error Detalle Producto: ",  $e->getMessage());
        }
    }
    /**
     * @param int $idProducto
     *
     * @return [int]
     */
    public function eliminarProducto($idProducto) {
        try {
            $resultado = Producto::borrar($this->pdo, $idProducto);
            return $resultado;
        } catch (\SoapFault $e) {
            // Error
            throw new \SoapFault("Error Eliminando Producto: ",  $e->getMessage());

        }
    }
    /**
     * @return [array]
     */
    public function listarProductos() {
        try {
            
            $listaProductos = Producto::listar($this->pdo, 10, 0);
            return $listaProductos;

            // $productos = array();
            // foreach ($listaProductos as $producto) {
            //     $typeProducto = new \stdClass();
            //     $typeProducto->id = $producto->getId();
            //     $typeProducto->cod = $producto->getCod();
            //     $typeProducto->desc = $producto->getDesc();
            //     $typeProducto->precio = $producto->getPrecio();
            //     $typeProducto->stock = $producto->getStock();
            //     $productos[] = $typeProducto;
            // }

            // $typeListaProductos = new \stdClass();
            // $typeListaProductos->productos = $productos;

            // return $typeListaProductos;

        } catch ( Error $e) {
            // Error
            throw new Error ("Error Listando Producto: ",  $e->getMessage());
        }
    }
}

?>