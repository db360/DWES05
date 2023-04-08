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

            $this->pdo = null;
            
            return;
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
            return $resultado;
        }
        if (!is_string($desc)) {
            $resultado->descResult = 'El codigo debe ser un string';
            $resultado->result = -2;
            return $resultado;
        }
        if (!is_float($precio) || $precio <= 0) {
            $resultado->descResult = 'El precio debe ser un número mayor a 0';
            $resultado->result = -2;
            return $resultado;
        }

        if (!is_int($stock) || $stock <= 0) {
            $resultado->result = -2;
            $resultado->descResult = 'El stock debe se un numero entero mayor a 0';
            return $resultado;
        }

        try {
            $productoGuardar = new Producto($cod, $desc, $precio, $stock);

            if(isset($pdo) || $pdo === null) {

                $resultado->result = -2;
                $resultado->descResult = "Base de datos no disponible";
                return $resultado;

            } else {
                $guardar = $productoGuardar->guardar($this->pdo);

            }

        } catch (\SoapFault $e) {
            // Error
            $resultado = new stdClass();
            $resultado->descResult = "Error Creando Producto: " . $e->getMessage();
            $resultado->result = -1;
            return $resultado;

        } catch (\PDOException $r) {
            $resultado = new stdClass();
            $resultado->result = -2;
            $resultado->descResult = "Error en la base de datos: " . $r->getMessage();
            return $resultado;
        }

        switch ($guardar) {
            case 1:
                $resultado->result = 1;
                $resultado->descResult = 'El Producto ha sido correctamente añadido';
                break;

            case -1:
                $resultado->result = -2;
                $resultado->descResult = 'Error con la base de datos';
                break;

            default:
                $resultado->result = -1;
                $resultado->descResult = 'Error creando el producto';
                break;
        }

        return $resultado;
    }
    /**
     * @param string $codProducto
     *
     * @return [object]
     */
    public function detalleProducto($codProducto)
    {
            $resultado = new stdClass();

            if(!isset($this->pdo) || $this->pdo === null) {

                $resultado->id = -2;
                $resultado->cod = 0;
                $resultado->desc = 0;
                $resultado->precio = 0;
                $resultado->stock = 0;

                return $resultado;
            } else {
                try {
                    //code...
                    if($codProducto === '') {

                        $resultado->id = -3;
                        $resultado->cod = 0;
                        $resultado->desc = 0;
                        $resultado->precio = 0;
                        $resultado->stock = 0;

                        return $resultado;

                    } else {

                        $producto = Producto::rescatar($this->pdo, $codProducto);

                    }

                } catch (\SoapFault $e) {

                    $resultado->id = -2;
                    $resultado->cod = 0;
                    $resultado->desc = 0;
                    $resultado->precio = 0;
                    $resultado->stock = 2;

                    return $resultado;

                }

                if(is_object($producto)) {

                    $resultado->id = intval($producto->id);
                    $resultado->cod = $producto->cod;
                    $resultado->desc = $producto->desc;
                    $resultado->precio = floatval($producto->precio);
                    $resultado->stock = intval($producto->stock);
                    // $resultado->result = 1;
                    // $resultado->descResult = "Producto correctamente rescatado";

                    return $resultado;

                }

                if($producto === -1) {

                    $resultado->id = -1;
                    $resultado->cod = 0;
                    $resultado->desc = 0;
                    $resultado->precio = 0;
                    $resultado->stock = 0;

                    return $resultado;

                }

                if($producto === -2) {

                    $resultado->id = -2;
                    $resultado->cod = 0;
                    $resultado->desc = 0;
                    $resultado->precio = 0;
                    $resultado->stock = 1;

                    return $resultado;

                }
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

                    $typeProducto = new stdClass();

                    $typeProducto->cod = $producto->cod;
                    $typeProducto->desc = $producto->desc;
                    $typeProducto->precio = $producto->precio;
                    $typeProducto->stock = $producto->stock;
                    $typeProducto->id = $producto->id;

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