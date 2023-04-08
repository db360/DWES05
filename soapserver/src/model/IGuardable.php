<?php
namespace interfaces;

require_once(__DIR__ . '/../conn.php');


 interface IGuardable {

    public function guardar($pdo);
    /**
     * @param mixed $pdo
     * @param string $codProducto
     *
     * @return [type]
     */
    public static function rescatar($pdo, $codProducto);
    public static function borrar($pdo, $id);
}

?>