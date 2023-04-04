<?php
namespace interfaces;

$pdo = connect();
interface IListable {
    public static function listar($pdo, $lim, $offset);
    public static function contar($pdo);

}




?>