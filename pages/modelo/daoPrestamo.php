<?php

if($_REQUEST['opcion'] == 'buscarSocio'){
    $busqueda = $_REQUEST['busqueda']; /* Variable de búsqueda */

    if(empty($busqueda)){
        $resultado = ["", ""];
    }else{
        $resultado = buscarSocio($busqueda);
    }
    
    echo json_encode($resultado);
}else if($_REQUEST['opcion'] == 'obtenerInteres'){
    $id_destino = $_REQUEST['codigo_destino'];

    if(empty($id_destino)){
        $resultado = ["Error :)", ""];
    }else{
        $resultado = mostrarInteres($id_destino);
    }

    echo json_encode($resultado);
}

function mostrarInteres($id_destino){
    include_once '../modelo/conexion.php';
    $conexion = conexionBaseDeDatos();
    
    $resultado = null;

    try{
        $sql = "SELECT * FROM destino WHERE id_destino = :id_destino";
        $statement = $conexion->prepare($sql);
        $statement->execute(array(":id_destino" => $id_destino));

        $resultado = $statement->fetch(PDO::FETCH_ASSOC);
        
        if($resultado != null){
            $resultado = [$resultado['interes_destino'], ''];
        }

    }catch(PDOException $e){
        $resultado = ["error :)", $e->getMessage()];
    }
    
    return $resultado;
}

function buscarSocio($busqueda){
    include_once '../modelo/conexion.php';
    $conexion = conexionBaseDeDatos();
    
    $resultado = null; /* Variable de resultado */

    try{
        $sql = "SELECT * FROM socio WHERE cod_socio LIKE '%" .  $busqueda . "%' OR nombre_socio LIKE '%" .  $busqueda . "%' OR apellido_socio LIKE '%" .  $busqueda ."%' ORDER BY cod_socio DESC LIMIT 1";
        $statement = $conexion->prepare($sql);
        $statement->execute();

        $resultado = $statement->fetch(PDO::FETCH_ASSOC);

        if($resultado != null){
            $resultado = [$resultado['cod_socio'], $resultado['nombre_socio'] . " " . $resultado['apellido_socio']];
        }else{
            $resultado = ["", ""];
        }

    }catch(PDOException $e){
        $resultado = ["error", $e->getMessage()];
    }
    
    return $resultado;
}

?>