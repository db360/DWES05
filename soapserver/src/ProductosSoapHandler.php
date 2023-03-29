<?php
require_once(__DIR__.'/conn.php');
/**
 * [Description ProductosSoapHandler]
 * Actividad Tema 5: DWES
 * @author David Martínez de la Torre
 * Documentación para la generación automática de documento WSDL
 */
class ProductosSoapHandler {

    private $pdo;
    /**
     * Constructor(establecer conexión con la base de datos y guardar la conexion)
     */
    public function __construct() {
        $this->pdo = connect();
    }
    /**
     * Método para agregar un nuevo producto a la base de datos.
     *
     * @param string $cod
     * @param string $descripcion
     * @param float $precio
     * @param int $stock
     * @return int id del nuevo producto insertado
     */
    public function nuevoProducto($id=null, $cod, $descripcion, $precio, $stock) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO productos (cod, `desc`, precio, stock ) VALUES (?, ?, ?, ?)");
            $stmt->execute([$cod, $descripcion, $precio, $stock]);
            return $this->pdo->lastInsertId();
        } catch (\SoapFault $e) {
            // Error
            die("Error: " . $e->getMessage());
        }
    }
    /**
     * @param int $codProducto
     *
     * @return [array]
     */
    public function detalleProducto($codProducto) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM productos WHERE id = ?");
            $stmt->execute([$codProducto]);
            $producto = $stmt->fetch(PDO::FETCH_OBJ);
            return $producto;
        } catch (\SoapFault $e) {
            // Error
            die("Error: " . $e->getMessage());
        }
    }
    /**
     * @param int $idProducto
     *
     * @return [int]
     */
    public function eliminarProducto($idProducto) {
        try {
            $stmt = $this->pdo->prepare("DELETE * FROM productos WHERE id = ?");
            $stmt->execute([$idProducto]);
            $resultadosAccion =  $stmt->rowCount();
            return $resultadosAccion;
        } catch (\SoapFault $e) {
            // Error
            die("Error: " . $e->getMessage());
        }
    }
    /**
     * @return [array]
     */
    public function listarProductos() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM productos");
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (\SoapFault $e) {
            // Error
            die("Error: " . $e->getMessage());
        }
    }
}


?>