<?php

function getPrestamos(){
    include_once '../modelo/conexion.php';
    $conexion = connect();

    $sql = "SELECT * FROM prestamo INNER JOIN socio ON prestamo.cod_socio = socio.cod_socio";
    $statement = $conexion->prepare($sql);
    $statement->execute();

    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

?>