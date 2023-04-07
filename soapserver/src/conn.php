<?php
require_once(__DIR__.'/../conf/conf.php');
/**
 * @return [object/null]
 *
 */
function connect()
{
    try {

        $pdo = new PDO(DB_DSN, DB_USER, DB_PASSWD,);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("SET CHARACTER SET utf8");

        return $pdo;

    } catch (PDOException $e) {
        throw new PDOException('Error de conexión a la base de datos: ' . $e->getMessage());
        // return $resultado->faultstring = "Error en la conexión" . $e;

    } finally {
        $pdo = null;
    }

}