<?php
use classes\Producto;

require_once(__DIR__ . '/../logger.php');

require_once(__DIR__ . '/conn.php');
require_once(__DIR__ . '/model/Producto.php');
/**
 * [Description ProductosSoapHandler]
 * Actividad Tema 5: DWES
 * Manejador de los métodos de la clase producto en el lado del servidor SOAP
 * @author David Martínez de la Torre
 *
 */
/**
 * [Description ProductosSoapHandler] Clase para manejar los métodos de la clase producto enn el servidor SOAP
 */
class ProductosSoapHandler
{
    private $pdo;
    /**
     * Constructor para establecer conexión con la base de datos y guardar la conexion,, con *un try / catch podemos capturar cualquier error con la conexión de la base de datos y *devovemos null si lo hay.
     */
    public function __construct()
    {
        try {
            $this->pdo = connect();
        } catch (\PDOException $e) {
            $this->pdo = null;
            return;
        }

    }
    /**
     * [description] método para crear y guardar un nuevo producto en la base de datos
     * @param object $producto objeto standard con los datos para crear el producto
     * @return object $resultado con los parámetros result y descResult
     */
    public function nuevoProducto($producto)
    {
        /// Creamos un objeto stdClass para mandar el resultado via soap
        $resultado = new stdClass();

        /// Validamos la entrada de datos en el lado del servidor
        $cod = filter_var($producto->cod, FILTER_SANITIZE_STRING);
        $desc = filter_var($producto->desc, FILTER_SANITIZE_STRING);
        $precio = filter_var($producto->precio, FILTER_VALIDATE_FLOAT);
        $stock = filter_var($producto->stock, FILTER_VALIDATE_INT);

        // Validaciones del lado del servidor por si las variables no pasan el filtro no tener que llamar a la funcion ni hacer consulta a la base de datos
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

        // Una vez que todo es correcto, creamos un try/catch para realizar la consulta a la base de datos
        try {
            /// Creamos con el constructor de la classe producto un producto con los parámetros de entrada
            $productoGuardar = new Producto($cod, $desc, $precio, $stock);

            ///  Comprobamos si tenemos conexión a la base de datos, si no, devolveremos un error via soap con los parámetros del output result y descResult
            if (!isset($this->pdo) || $this->pdo === null) {

                $resultado->result = -2;
                $resultado->descResult = "Base de datos no disponible";
                return $resultado;

                /// Si no hay problemas con la base de datos, llamamos a la función guardar del objeto de clase producto
            } else {
                $guardar = $productoGuardar->guardar($this->pdo);
            }

            /// He creado dos catchs, uno para los errores de soap, y otro para el error que pueda venir de la base de datos, para poder clasificar el output según sea uno u otro, para los errores devuelvo un numero menor a 0 para así en el cliente poder classificarlso, capturo el mensaje de error que capture el catch y lo concateno con el string del error en la salida.

        } catch (\SoapFault $e) {
            // Error
            $resultado = new stdClass();
            $resultado->descResult = "Error en el servicio SOAP: " . $e->getMessage();
            $resultado->result = -1;

        } catch (\PDOException $r) {
            $resultado = new stdClass();
            $resultado->result = -2;
            $resultado->descResult = "Error en la base de datos: " . $r->getMessage();
        }

        // Si no ha habido problemas, procesamos el retorno de la función guardar mediante un switch, mandando el correspondiente resultado y descripción como retorno.
        switch ($guardar) {
            case 1:
                $resultado->result = 1;
                $resultado->descResult = 'El Producto ha sido correctamente añadido';
                break;

            case -1:
                $resultado->result = -2;
                $resultado->descResult = 'Error al guardar el producto en la base de datos';
                break;

            default:
                $resultado->result = -1;
                $resultado->descResult = 'Error creando el producto ' . $guardar;
                break;
        }

        return $resultado;
    }
    /**
     * [Description] método que recupera los datos de un producto de la base de datos,
     * mediante el código de producto como parámetro de entrada, siempre devuelve un objeto,
     * por lo que he manejado los errores mediante numeros negativos en el objeto de retorno.
     * @param string $codProducto string con el código del producto.
     * @return [object] objeto con los parámetros del producto o con el id de error como id *de producto
     */
    public function detalleProducto($codProducto)
    {
        // Creamos un objeto de clase standard para el resultado
        $resultado = new stdClass();

        // Comprobamos que la conexión a la base de datos no sea null, si lo es, devolvemos un objeto con id de -2

        if (!isset($this->pdo) || $this->pdo === null) {

            $resultado->id = -2;
            $resultado->cod = 0;
            $resultado->desc = 0;
            $resultado->precio = 0;
            $resultado->stock = 0;

            return $resultado;

            // Si la conexión a la BD es correcta, abrimos un try/catch
        } else {

            try {
                // Validamos el parámetro de entrada
                $codProducto = filter_var($codProducto, FILTER_SANITIZE_STRING);
                /// si no ha pasado el filtro, $codProducto es false, por lo que creamos un objeto con id -3 y lo retornamos.
                if (!$codProducto) {

                    $resultado->id = -3;
                    $resultado->cod = 0;
                    $resultado->desc = 0;
                    $resultado->precio = 0;
                    $resultado->stock = 0;

                    return $resultado;

                    // Si el parámetro es correcto, invocamos el método rescatar de la clase producto
                } else {

                    $producto = Producto::rescatar($this->pdo, $codProducto);

                }
                // Si capturamos alguna excepción con SOAP, retornamos un objeto con id de -2
            } catch (\SoapFault $e) {

                $resultado->id = -2;
                $resultado->cod = 0;
                $resultado->desc = 0;
                $resultado->precio = 0;
                $resultado->stock = 2;

                return $resultado;

            }
            // Si lo que retorna el método rescatar es un objeto, asignamos sus valores al objeto de resultado y lo retornamos.

            if (is_object($producto)) {

                $resultado->id = $producto->id;
                $resultado->cod = $producto->cod;
                $resultado->desc = $producto->desc;
                $resultado->precio = $producto->precio;
                $resultado->stock = $producto->stock;
            }

            // Si el retorno es -1, no se pudo crear el producto,, devolvemos un objeto con id = -1
            if ($producto === -1) {

                $resultado->id = -1;
                $resultado->cod = 0;
                $resultado->desc = 0;
                $resultado->precio = 0;
                $resultado->stock = 0;
            }
            // Si el retorno es -2, hubo algún error con la operación en la baase de datos, devolvemos un objeto con id = -1
            if ($producto === -2) {

                $resultado->id = -2;
                $resultado->cod = 0;
                $resultado->desc = 0;
                $resultado->precio = 0;
                $resultado->stock = 1;
            }

            // Retornamos el objeto con el resultado.
            return $resultado;

        }
    }

    /**
     *[description] Método para eliminar un producto de la base de datos mediante un id como parámetro de entrada, devolverá un objeto con un código y un resultado
     * @param int $idProducto id del producto de la base de datos
     * @return [object] objeto con los campos result y descResult para retornar resultado
     */
    public function eliminarProducto($idProducto)
    {
        // Creamos un objeto std para el retorno del resultado.
        $resultado = new stdClass();

        // Validamos el parámetro con un filtro
        filter_var($idProducto, FILTER_VALIDATE_INT);

        // Comprobamos que tengamos conexión con la base de datos
        if (!isset($this->pdo) || $this->pdo === null) {
            $resultado->result = -3;
            $resultado->descResult = 'Error con la conexion a la base de datos';
            return $resultado;

            /// Si todo es correcto y el parámetro no es false
        } else {

            ///comprobamos si el parámetreo ha pasado el filtro y si es incorrecto o menor que 0
            if (!$idProducto || $idProducto < 0) {

                $resultado->result = -1;
                $resultado->descResult = 'La ID del producto debe ser un número entero';

            } else {

                // Si tenemos conexión y el parámetro es correcto, abrimos un try/catch e invocamos el método borrar de la clase prodducto.

                try {

                    $borrarProducto = Producto::borrar($this->pdo, $idProducto);

                    /// una vez invocamos el método, procedemos a capturar el resultado y mandamos una respuesta segun sea uno u otro

                    // Si el resultado es 1, el producto ha sido correctamente eliminado
                    if ($borrarProducto === 1) {
                        $resultado = new stdClass();
                        $resultado->result = 1;
                        $resultado->descResult = 'Producto correctamente eliminado!';
                    }
                    // Si el resultado es 0, no existe ningun producto con el id

                    if ($borrarProducto === 0) {
                        $resultado = new stdClass();
                        $resultado->result = -1;
                        $resultado->descResult = 'No existe ningun producto con ese ID';
                    }
                    // si el resultado es -1, hubo un error al eliminar el producto, devolvemos -2, y el mensaje que hubo un error en la base de datos
                    if ($borrarProducto === -1 || $borrarProducto === false) {
                        $resultado = new stdClass();
                        $resultado->result = -2;
                        $resultado->descResult = 'Error al eliminar producto en la base de datos';
                    }

                    return $resultado;

                } catch (\SoapFault $e) {
                    // Si hubiera algun error con el servidor soap, lanzamos un error soap
                    throw new \SoapFault("Error Eliminando Producto: ", $e->getMessage());
                }
            }
        }

    }
    /**
     * [description] método para listar todos los productos de la base de datos, que devuelve como un array de objetos stdClass con los campos cod, desc,, precio, stock e id,, si no encuentra productos, devuelve un array con 0 productos
     * @return [array<stdClass>]
     */
    public function listarProductos()
    {
        // Comprobamos que tengamos conexión con la base de datos, si no hay conexión,, devolvemos null
        if (!isset($this->pdo) || $this->pdo === null) {

            return null;

            /// Si todo es correcto y el parámetro no es false
        } else {

            try {

                $listaProductos = Producto::listar($this->pdo, 10, 0);

                // Si obtenemos un resultado
                if (isset($listaProductos)) {

                    // Creamos el de array de objetos producto
                    $typeListaProductos = new stdClass();
                    $typeListaProductos->productos = array();

                    // Si hemos obtenido más de 0 productos mediante el metodo listar recuperamos los valores de los propductos obtenidos y los asignamos al array de objetos de salida, si fueran 0, devolvería un array vacío como se nos pide.
                    if (count($listaProductos) > 0) {

                        foreach ($listaProductos as $producto) {

                            $typeProducto = new stdClass();

                            $typeProducto->cod = $producto->cod;
                            $typeProducto->desc = $producto->desc;
                            // formateamos el precio
                            $typeProducto->precio = sprintf('%01.2f', $producto->precio);
                            $typeProducto->stock = $producto->stock;
                            $typeProducto->id = $producto->id;

                            $typeListaProductos->productos[] = $typeProducto;
                        }
                    }

                    // Retornamos los productos en el array de objetos
                    return $typeListaProductos;

                }


            } catch (SoapFault $e) {
                // Capturamos cualquier error de Soap mediante el catch
                throw new SoapFault("Error Listando Producto: ", $e->getMessage());
            }
        }
    }

    /**
     * @param mixed $codProducto
     * @param mixed $datosProducto
     *
     * @return [type]
     */
    public function modificarProducto($codProducto, $datosProducto)
    {
        $resultado = new stdClass();

        if (!isset($this->pdo) || $this->pdo === null) {

            $resultado->result = -2;
            $resultado->descResult = "Problemas con la conexión a la base de datos";

            return $resultado;

            /// Si todo es correcto y el parámetro no es false
        } else {

            try {
                //code...
            } catch (\PDOException $e) {
                //throw $th;
            }

            $resultado->result = 1;
            $resultado->descResult = "POLLAS";

            return $resultado;

            // $datosProducto->id
            // $datosProducto->cod
            // $datosProducto->desc
            // $datosProducto->precio
            // $datosProducto->stock
        }
    }

    public function incrementarStockProducto($codProducto, $incStock)
    {
    }
}
?>