<?php
namespace interfaces;

interface IListable {
    /**
     * @param mixed $pdo
     * @param int $lim
     * @param int $offset
     *
     * @return [array|null]
     */
    public static function listar($pdo, $lim, $offset);
    /**
     * @param mixed $pdo
     *
     * @return [int]
     */
    public static function contar($pdo);

}




?>