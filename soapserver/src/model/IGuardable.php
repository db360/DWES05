<?php
namespace interfaces;

require_once(__DIR__ . '/../conn.php');

$pdo = connect();

 interface IGuardable {

    public function guardar($pdo);
    public static function rescatar($pdo, $id);
    public static function borrar($pdo, $id);
}

?>