<?php
date_default_timezone_set('America/El_Salvador'); //Establecemos la zona horaria

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
}else if($_REQUEST['opcion'] == 'registrarPrestamo'){
    $resultado = [];

    $id_socio = $_REQUEST['cod_socio'];
    $monto = $_REQUEST['monto'];
    $num_cuotas = $_REQUEST['num_cuotas'];
    $id_destino = $_REQUEST['id_destino'];
    $forma_pago = $_REQUEST['forma_pago'];
    $fecha_inicio = $_REQUEST['fecha_inicio'];

    if(empty($id_socio) || empty($monto) || empty($num_cuotas) || empty($id_destino) || empty($forma_pago) || empty($fecha_inicio)){
        $resultado = ['error', 'Complete todos los campos'];
    }else{
        $resultado = registrarPrestamo($id_socio, $monto, $num_cuotas, $id_destino, $forma_pago, $fecha_inicio);
    }

    echo json_encode($resultado);
}

function registrarPrestamo($id_socio, $monto, $num_cuotas, $id_destino, $forma_pago, $fecha_inicio){
    include_once '../modelo/conexion.php';
    $conexion = conexionBaseDeDatos();
    $resultado = null;
    $monto_cuota = 0;
    $monto_total = 0;

    try{
        $sql = "SELECT * FROM destino WHERE id_destino = :id_destino";
        $statement = $conexion->prepare($sql);
        $statement->execute(array(":id_destino" => $id_destino));
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $seguro = $monto * 0.058; // 5.8%
        $interes = $monto * ($result['interes_destino'] / 100); // Interes del destino

        $monto_total = $monto + $interes + $seguro; // Monto total a pagar
        $monto_cuota = $monto_total / $num_cuotas; // Monto de cada cuota

        $sql = "INSERT INTO prestamo (
            cod_socio,
            id_destino,
            monto_prestamo, 
            abono_capital_prestamo,
            seguro_prestamo,
            fecha_emision_prestamo,
            fecha_inicio_pago,
            forma_pago_prestamo
        ) VALUES (
            :cod_socio,
            :id_destino,
            :monto, 
            :abono_capital, 
            :seguro, 
            :fecha_emision,
            :fecha_inicio,
            :forma_pago
        )";

        $statement = $conexion->prepare($sql);
        $statement->execute(array(
            ":cod_socio" => $id_socio,
            ":id_destino" => $id_destino,
            ":monto" => $monto_total,
            ":abono_capital" => 0,
            ":seguro" => $seguro,
            ":fecha_emision" => date('Y-m-d'),
            ":fecha_inicio" => $fecha_inicio,
            ":forma_pago" => $forma_pago
        ));

        $id_prestamo = $conexion->lastInsertId(); // Obtenemos el id del prestamo

        for($i = 0; $i < $num_cuotas; $i++){
            $fecha = date("Y-m-d", strtotime($fecha_inicio . " + $i $forma_pago"));

            $sql = "INSERT INTO cuota (
                id_prestamo,
                fecha_pago_cuota,
                monto_cuota,
                mora_cuota,
                estado_cuota
            ) VALUES (
                :id_prestamo,
                :fecha_pago,
                :monto_cuota,
                :mora,
                :estado
            )";

            $statement = $conexion->prepare($sql);
            $statement->execute(array(
                ":id_prestamo" => $id_prestamo,
                ":fecha_pago" => $fecha,
                ":monto_cuota" => $monto_cuota,
                ":mora" => 0,
                ":estado" => 'Pendiente'
            ));
        }

        $resultado = ['success', 'Prestamo registrado correctamente'];
    }catch(PDOException $e){
        $resultado = ["error", $e->getMessage()];
    }

    return $resultado;
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
            $aux = $fecha_inicio . " + $i ";

            if($forma_pago == 'Diario'){
                $aux .= 'day';
            }else if($forma_pago == 'Semanal'){
                $aux .= 'week';
            }else if($forma_pago == 'Quincenal'){
                if(date('d', strtotime($fecha_inicio)) <= 15){
                    $aux .= 'month';
                }
            }else if($forma_pago == 'Mensual'){
                $aux .= 'month';
            }

            $fecha = date("d-m-Y", strtotime($aux));

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