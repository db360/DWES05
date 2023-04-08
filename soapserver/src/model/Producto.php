<?php
namespace classes;

require_once(__DIR__.'/IGuardable.php');
require_once(__DIR__.'/IListable.php');

use interfaces\IGuardable;
use interfaces\IListable;

/* Clase Producto */
/**
 * [Description Producto]
 */
class Producto implements IListable, IGuardable {

    /* PARÁMETROS */
    private $cod;
    private $desc;
    private $precio;
    private $stock;
    private $id;

    // /* CONSTRUCTOR */
    // /**
    //  * @param string $cod
    //  * @param string $desc
    //  * @param float $precio
    //  * @param int $stock
    //  * @param int $id
    //  */
    public function __construct($cod, $desc, $precio, $stock, $id = null) {

        $this->cod = $cod;
        $this->desc = $desc;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->id = $id;
    }
    /**
     * @return [int]
     */
    public function getId() {
        return $this->id;
    }
    /**
     * @return [float]
     */
    public function getPrecio() {
        return floatval($this->precio);
    }
    /**
     * @return [string]
     */
    public function getDesc() {
        return $this->desc;
    }
    /**
     * @return [string]
     */
    public function getCod() {
        return $this->cod;
    }
    /**
     * @return [string]
     */
    public function getStock () {
        return $this->stock;
    }
    /**
     * @param mixed $precio
     *
     * @return [float]
     */
    /**
     * @param float $precio
     *
     * @return [float|boolean]
     */
    public function setPrecio($precio) {
        if(is_numeric($precio) && $precio > 0) {
            $this->precio = $precio;
            $retorno = true;
        } else {
            $retorno = false;
        };
        return $retorno;
    }
    /**
     * @param int $stock
     *
     * @return [int|boolean]
     */
    public function setStock($stock) {
        if(is_numeric($stock) && $stock > 0) {
        $this-> stock = $stock;
        $retorno = true;
        } else {
        $retorno = false;
        }
        return $retorno;
    }
    /**
     * @param string $cod
     *
     * @return [string|boolean]
     */
    public function setCod($cod) {
        if (preg_match('/^[a-zA-Z0-9]+$/', $cod)) {
            $this->cod = $cod;
            $retorno = true;
        } else {
            $retorno = true;
        }
        return $retorno;
    }
    /**
     * @param string $desc
     *
     * @return [string]
     */
    public function setDesc($desc) {
        if(strlen($desc) > 1) {
            $this->desc = $desc;
            $retorno = true;
        } else {
            $retorno = false;
        }
        return $retorno;
    }
    /**
     * @param mixed $pdo
     *
     * @return [int/boolean]
     */
    public function guardar($pdo) {
        try {

                $sql = "INSERT INTO productos (cod, `desc`, precio, stock) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$this->cod, $this->desc, $this->precio, $this->stock]);
                $this->id = $pdo->lastInsertId();

                return 1;

        } catch (\PDOException $e) {
            $retorno = $e->getMessage();
            return $retorno;
        }
    }
    /**
     * @param mixed $pdo
     * @param mixed $id
     *
     * @return [array]
     */
    public static function rescatar($pdo, $cod) {

        try {

            $stmt = $pdo->prepare("SELECT * FROM productos WHERE cod = ?");
            $stmt->execute([$cod]);
            $producto = $stmt->fetch(\PDO::FETCH_ASSOC);

            if($producto) {

                $resultado = new \stdClass();
                $resultado->cod = $producto['cod'];
                $resultado->desc = $producto['desc'];
                $resultado->precio = floatval($producto['precio']);
                $resultado->stock = intval($producto['stock']);
                $resultado->id = intval($producto['id']);

                return $resultado;

            } else {

                return -1;
                
            }

        } catch (\PDOException $e) {

            return -2;
        }
    }
    /**
     * @param mixed $pdo
     * @param mixed $id
     *
     * @return [int/boolean]
     */
    public static function borrar($pdo, $id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM productos WHERE id=?");
            $stmt->execute([$id]);
            return $stmt->rowCount();

        } catch (\PDOException $e) {
            return false;
        }
    }
    /**
     * @param mixed $pdo
     * @param int $lim
     * @param int $offset
     *
     * @return [array]
     */
    public static function listar($pdo, $lim, $offset) {

        try {
            $stmt = $pdo->prepare("SELECT id FROM productos LIMIT ? OFFSET ?");
            $stmt->bindValue(1, $lim, \PDO::PARAM_INT);
            $stmt->bindValue(2, $offset, \PDO::PARAM_INT);
            $stmt->execute();
            $productos = $stmt->fetchAll(\PDO::FETCH_COLUMN);
            $listaProductos = array();
            foreach ($productos as $producto) {
                $listaProductos[] = Producto::rescatar($pdo, $producto);
            }
            return $listaProductos;

        } catch (\PDOException $e) {
            // echo $e;
            return false;
        }
    }

    // Código para contar la cantidad total de objetos en la base de datos
    /**
     * @param mixed $pdo
     *
     * @return [type]
     */
    public static function contar($pdo) {
        try {
            $stmt = $pdo->query("SELECT count(id) from productos");
            return  $stmt->fetchColumn();
        } catch (\PDOException $e){
            return $e;
        }
    }
}
?>



