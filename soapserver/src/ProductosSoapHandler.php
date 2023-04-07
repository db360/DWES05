<?php

use classes\Producto;

require_once(__DIR__ . '/../logger.php');

require_once(__DIR__ . '/conn.php');
require_once(__DIR__ . '/model/Producto.php');
/**
 * [Description ProductosSoapHandler]
 * Actividad Tema 5: DWES
 * @author David Martínez de la Torre
 *
 */
class ProductosSoapHandler
{
    private $pdo;
    /**
     * Constructor para establecer conexión con la base de datos y guardar la conexion.
     */
    public function __construct()
    {
        try {
            $this->pdo = connect();
            //code...
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage());
        }

    }
    /**
     * @param int $array
     *
     * @return [int|boolean]
     */
    public function nuevoProducto($producto)
    {
        $resultado = new stdClass();

        $cod = strval($producto->cod);
        $desc = strval($producto->desc);
        $precio = floatval($producto->precio);
        $stock = intval($producto->stock);

        if (!is_string($cod)) {
            $resultado->descResult = 'El codigo debe ser un string';
            $resultado->result = -2;
            $resultado->error = true;
            return $resultado;
        }
        if (!is_string($desc)) {
            $resultado->descResult = 'El codigo debe ser un string';
            $resultado->result = -2;
            $resultado->error = true;
            return $resultado;
        }
        if (!is_float($precio) || $precio <= 0) {
            $resultado->descResult = 'El precio debe ser un número mayor a 0';
            $resultado->result = -2;
            $resultado->error = true;
            return $resultado;
        }

        if (!is_int($stock) || $stock <= 0) {
            $resultado->result = -2;
            $resultado->descResult = 'El stock debe se un numero entero mayor a 0';
            $resultado->error = true;
            return $resultado;
        }

        try {
            $productoGuardar = new Producto($cod, $desc, $precio, $stock);

            $guardar = $productoGuardar->guardar($this->pdo);

        } catch (\SoapFault $e) {
            // Error
            $resultado = new stdClass();
            $resultado->descResult = "Error Creando Producto: " . $e->getMessage();
            $resultado->result = -1;
            return $resultado;

        } catch (\PDOException $r) {
            $resultado = new stdClass();
            $resultado->descResult = "Error en la base de datos: " . $r->getMessage();
        }

        switch ($guardar) {
            case 1:
                $resultado->result = 1;
                $resultado->descResult = 'El Producto ha sido correctamente añadido';

                break;
            default:
                $resultado->result = -1;
                $resultado->descResult = 'Error creando el producto: ' . $guardar;
                $resultado->error = true;

                break;
        }

        return $resultado;
    }
    /**
     * @param int $codProducto
     *
     * @return [object|null]
     */
    public function detalleProducto($codProducto)
    {
        if (!is_string($codProducto) || !isset($codProducto) || empty($codProducto))  {

            $resultado = new stdClass();
            $resultado->descResult = "Error: El código debe contener carácteres";
            $resultado->result = -2;
            return $resultado;

        } else {

            try {
                $resultado = Producto::rescatar($this->pdo, $codProducto);
                //code...
            } catch (\SoapFault $e) {
                $resultado = new stdClass();
                $resultado->descResult = "Error rescatando Producto: " . $e->getMessage();
                $resultado->result = -2;
                return $resultado;

            } catch (\PDOException $r) {
                $resultado = new stdClass();
                $resultado->descResult = "Error en la base de datos: " . $r->getMessage();
                $resultado->result = -2;
                return $resultado;
            }
        }

        if ($resultado !== false) {

            $producto = new stdClass();

            $producto->id = $resultado->getId();
            $producto->cod = $resultado->getCod();
            $producto->desc = $resultado->getDesc();
            $producto->precio = doubleval($resultado->getPrecio());
            $producto->stock = $resultado->getCod();

            $producto->result = 1;
            $producto->descResult = 'Producto correctamente rescatado';
            return $producto;

        } else {
            $error = new stdClass();
            $error->result = -1;
            $error->descResult = 'No existe ningún producto con ese código';
            return $error;
        }

    }
    /**
     * @param int $idProducto
     *
     * @return [int]
     */
    public function eliminarProducto($idProducto)
    {
        try {
            $resultado = Producto::borrar($this->pdo, $idProducto);
            return $resultado;
        } catch (\SoapFault $e) {
            // Error
            throw new \SoapFault("Error Eliminando Producto: ", $e->getMessage());
        }
    }
    /**
     * @return [array<Producto>]
     */
    public function listarProductos()
    {
        try {
            $listaProductos = Producto::listar($this->pdo, 5, 0);

            if (isset($listaProductos)) {

                $typeListaProductos = new stdClass();
                $typeListaProductos->productos = array();

                foreach ($listaProductos as $producto) {

                    $typeProducto = new Producto(

                        $producto->getCod(),
                        $producto->getDesc(),
                        floatval($producto->getPrecio()),
                        $producto->getStock(),
                        $producto->getId()

                    );

                    $typeListaProductos->productos[] = $typeProducto;
                }

                return $typeListaProductos;

            }

        } catch (Error $e) {
            // Error
            throw new Error("Error Listando Producto: ", $e->getMessage());
        }
    }
}
?>