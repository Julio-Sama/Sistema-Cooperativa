<?php

if($_REQUEST['opcion'] == 'buscarSocio'){
    $busqueda = $_REQUEST['busqueda']; /* Variable de búsqueda */
    $resultado = [];

    if(empty($busqueda)){
        $resultado = ["", ""];
    }else{
        $resultado = buscarSocio($busqueda);
    }
    
    echo json_encode($resultado);
}else if($_REQUEST['opcion'] == 'obtenerInteres'){
    $id_destino = $_REQUEST['codigo_destino'];
    $resultado = [];

    if(empty($id_destino)){
        $resultado = ["Error :)", ""];
    }else{
        $resultado = mostrarInteres($id_destino);
    }

    echo json_encode($resultado);
}else if($_REQUEST['opcion'] == 'calcularCuotas'){
    $resultado = [];

    $monto = $_REQUEST['monto'];
    $num_cuotas = $_REQUEST['num_cuotas'];
    $id_destino = $_REQUEST['id_destino'];
    $forma_pago = $_REQUEST['forma_pago'];
    $fecha_inicio = $_REQUEST['fecha_inicio'];

    if(empty($monto) || empty($num_cuotas) || empty($id_destino) || empty($forma_pago) || empty($fecha_inicio)){
        $resultado = ['error', 'Complete todos los campos'];
    }else{
        $resultado = calcularCuotas($monto, $num_cuotas, $id_destino, $forma_pago, $fecha_inicio);
    }

    echo json_encode($resultado);
}

function calcularCuotas($monto, $num_cuotas, $id_destino, $forma_pago, $fecha_inicio){
     include_once '../modelo/conexion.php';
    $conexion = conexionBaseDeDatos();
    $resultado = null;
    $monto_total = 0;
    $tabla_cuotas = "";

    try{
        $sql = "SELECT * FROM destino WHERE id_destino = :id_destino";
        $statement = $conexion->prepare($sql);
        $statement->execute(array(":id_destino" => $id_destino));
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $seguro = $monto * 0.058; // 5.8%
        $interes = $monto * ($result['interes_destino'] / 100); // Interes del destino

        $monto_total = $monto + $interes + $seguro; // Monto total a pagar
        $monto_cuota = $monto_total / $num_cuotas; // Monto de cada cuota

        for($i = 1; $i <= $num_cuotas; $i++){
            $fecha = date("d-m-Y", strtotime($fecha_inicio . " + $i $forma_pago"));
            $tabla_cuotas .= "<tr>
                                <td>".($i)."</td>
                                <td>" . $fecha . "</td>
                                <td>$ " . number_format($monto_cuota, 2) . "</td>
                            </tr>";
        }

        $resultado = [
            number_format($monto_cuota, 2),
            number_format($interes, 2), 
            number_format($seguro, 2), 
            number_format($monto_total, 2),
            $tabla_cuotas
        ];

    }catch(PDOException $e){
        $resultado = ["error :)", $e->getMessage()];
    }

    return $resultado;

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
            $resultado = [number_format($resultado['interes_destino'], 2) . '%', ''];
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