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

    if(empty($id_destino) || $id_destino == 0){
        $resultado = ["", ""];
    }else{
        $resultado = mostrarInteres($id_destino);
    }

    echo json_encode($resultado);
}else if($_REQUEST['opcion'] == 'calcularCuotas'){
    $resultado = [];

    $monto = doubleval($_REQUEST['monto']);
    $num_cuotas = $_REQUEST['num_cuotas'];
    $id_destino = $_REQUEST['id_destino'];
    $forma_pago = $_REQUEST['forma_pago'];
    $fecha_inicio = $_REQUEST['fecha_inicio'];

    if(empty($monto) || empty($num_cuotas) ||  $id_destino == "0" || empty($forma_pago) || empty($fecha_inicio)){
        $resultado = ['error', 'Complete todos los campos'];
    }else{
        $resultado = calcularCuotas($monto, $num_cuotas, $id_destino, $forma_pago, $fecha_inicio);
    }

    echo json_encode($resultado);
}else if($_REQUEST['opcion'] == 'registrarPrestamo'){
    $resultado = [];

    $id_socio = $_REQUEST['cod_socio'];
    $monto = doubleval($_REQUEST['monto']);
    $num_cuotas = $_REQUEST['num_cuotas'];
    $id_destino = $_REQUEST['id_destino'];
    $fecha_inicio = $_REQUEST['fecha_inicio'];
    $forma_pago = $_REQUEST['forma_pago'];

    if(empty($id_socio) || empty($monto) || empty($num_cuotas) || $id_destino == "0" || empty($fecha_inicio) || empty($forma_pago)){
        $resultado = ['error', 'Complete todos los campos'];
    }else{
        $resultado = registrarPrestamo($id_socio, $monto, $num_cuotas, $id_destino, $fecha_inicio, $forma_pago);
    }

    echo json_encode($resultado);
}else if($_REQUEST['opcion'] == 'obtenerPlanDePagos'){
    $resultado = [];

    $id_prestamo = $_REQUEST['cod_prestamo'];

    if(empty($id_prestamo)){
        $resultado = ['error', 'Complete todos los campos'];
    }else{
        $resultado = obtenerPrestamo($id_prestamo);
    }

    echo json_encode($resultado);
}else if($_REQUEST['opcion'] == 'obtenerDestinos'){
    $resultado = [];

    $id_forma_pago = $_REQUEST['id_forma_pago'];

    if(empty($id_forma_pago)){
        $resultado = ['error', 'Complete todos los campos'];
    }else{
        $resultado = obtenerDestinos($id_forma_pago);
    }

    echo json_encode($resultado);
}else{
    echo json_encode(['error', 'Petición no válida']);
}

function obtenerPrestamo($id_prestamo){
    include_once '../modelo/conexion.php';
    $conexion = conexionBaseDeDatos();
    $resultado = [];

    try{

        $sql = "SELECT s.cod_socio, s.nombre_socio, s.apellido_socio,
            (SELECT COUNT(*) FROM cuota 
                    WHERE cuota.id_prestamo = p.id_prestamo) AS cant_cuota,
            p.monto_prestamo, p.frecuencia_pago_prestamo, d.nom_destino, d.interes_destino, p.seguro_prestamo,
            IF(
                (SELECT COUNT(*) FROM cuota 
                    WHERE cuota.id_prestamo = p.id_prestamo 
                    AND cuota.estado_cuota = 'Pendiente') > 0, 'Pendiente', 'Cancelado') AS estado
        FROM prestamo AS p
        INNER JOIN socio AS s ON s.cod_socio = p.cod_socio
        INNER JOIN destino AS d ON d.id_destino = p.id_destino
        WHERE p.id_prestamo = :id_prestamo";

        $consulta = $conexion->prepare($sql);
        $consulta->execute(array(':id_prestamo' => $id_prestamo));

        if($consulta->rowCount() > 0){
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            $seguro = $resultado['seguro_prestamo'];
            $monto = $resultado['monto_prestamo'];
            $interes = $resultado['interes_destino'];
            $num_cuotas = $resultado['cant_cuota'];
            $frecuencia_de_pago = $resultado['frecuencia_pago_prestamo'];

            $cuotas = obtenerCuotas($id_prestamo, $seguro, $monto, $interes, $num_cuotas, $frecuencia_de_pago);

            $resultado = [
                $resultado['cod_socio'],
                $resultado['nombre_socio'], 
                $resultado['apellido_socio'], 
                $resultado['cant_cuota'], 
                number_format($resultado['monto_prestamo'], 2), 
                $resultado['frecuencia_pago_prestamo'], 
                $resultado['nom_destino'], 
                number_format($resultado['interes_destino'], 2), 
                $resultado['estado'],
                $cuotas
            ];
        }else{
            $resultado = ['error', 'No se encontró el préstamo'];
        }
    }catch(PDOException $e){
        $resultado = ['error :)', $e->getMessage()];
    }

    return $resultado;
}

function obtenerCuotas($id_prestamo, $seguro_total, $monto, $tasa, $num_cuotas, $frecuencia_de_pago){
    include_once '../modelo/conexion.php';
    $conexion = conexionBaseDeDatos();
    $tabla_cuotas = "";

    $sql = "SELECT * FROM cuota WHERE id_prestamo = :id_prestamo";

    $consulta = $conexion->prepare($sql);
    $consulta->execute(array(':id_prestamo' => $id_prestamo));

    $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

    $i = 1;
    $tasa = obtenerInteres($tasa, obtenerFrecuenciaPago($frecuencia_de_pago));
    $total_cuota = obtenerCuota($monto, $tasa, $num_cuotas);
    $seguro_cuota = $seguro_total / $num_cuotas;
    $total_cuota = $total_cuota + $seguro_cuota;

    foreach($resultado as $cuota){

        $tabla_cuotas .= "<tr>
                            <td>$i</td>
                            <td>" . date('d-m-Y', strtotime($cuota['fecha_pago_cuota'])) . "</td>
                            <td>$". number_format($cuota['capital_cuota'], 2)."</td>
                            <td>$". number_format($cuota['interes_cuota'], 2)."</td>
                            <td>$". number_format($seguro_cuota, 2)."</td>
                            <td>$". number_format($total_cuota, 2)."</td>
                            <td>$". number_format($cuota['saldo_capital'], 2)."</td>
                            <td>{$cuota['estado_cuota']}</td>
                        </tr>";

        $i = $i + 1;
    }

    return $tabla_cuotas;
}

function registrarPrestamo($id_socio, $monto, $num_cuotas, $id_destino, $fecha_inicio, $forma_pago){    
    include_once '../modelo/conexion.php';
    $conexion = conexionBaseDeDatos();
    $resultado = null;

    $seguro_prestamo = 0;
    $total_cuota = 0;
    $seguro_cuota = 0;

    try{
        /* Obtener el porcentaje de interes */
        $seguro_prestamo = $monto * 0.058; // Seguro total del prestamo;

        $sql = "INSERT INTO prestamo (
            cod_socio,
            id_destino,
            frecuencia_pago_prestamo,
            monto_prestamo,
            seguro_prestamo,
            fecha_emision_prestamo,
            fecha_inicio_pago
        ) VALUES (
            :cod_socio,
            :id_destino,
            :frecuencia_pago,
            :monto,
            :seguro, 
            :fecha_emision,
            :fecha_inicio
        )";

        $consulta = $conexion->prepare($sql);
        $consulta->execute(array(
            ":cod_socio" => $id_socio,
            ":id_destino" => $id_destino,
            ":frecuencia_pago" => obtenerFrecuenciaPago($forma_pago),
            ":monto" => $monto,
            ":seguro" => $seguro_prestamo,
            ":fecha_emision" => date('Y-m-d'),
            ":fecha_inicio" => $fecha_inicio
        ));

        $id_prestamo = $conexion->lastInsertId(); // Obtenemos el id del prestamo

        $sql = "SELECT * FROM destino WHERE id_destino = :id_destino";
        $consulta = $conexion->prepare($sql);
        $consulta->execute(array(":id_destino" => $id_destino));
        $fila = $consulta->fetch(PDO::FETCH_ASSOC);

        $interes_destino = obtenerInteres($fila['interes_destino'], $forma_pago); // Obtenemos el interes del destino

        $total_cuota = ($monto * $interes_destino) / (1 - pow(1 + $interes_destino, -$num_cuotas)); // Obtenemos el monto de la cuota
        $seguro_cuota = $seguro_prestamo / $num_cuotas; // Seguro de cada cuota

        $saldo_capital = $monto; // Saldo del prestamo

        for($i = 0; $i < $num_cuotas; $i++){

            $interes_cuota = $saldo_capital * pow(1 + $interes_destino, 1) - $saldo_capital; // Interes de la cuota
            $capital = $total_cuota - $interes_cuota; // Capital de la cuota
            $saldo_capital -= $capital; // Saldo del prestamo

            $sql = "INSERT INTO cuota (
                id_prestamo,
                fecha_pago_cuota,
                capital_cuota,
                interes_cuota,
                extra_cuota,
                saldo_capital,
                estado_cuota
            ) VALUES (
                :id_prestamo,
                :fecha_pago,
                :capital_cuota,
                :interes_cuota,
                :extra_cuota,
                :saldo_capital,
                :estado
            )";

            $consulta = $conexion->prepare($sql);
            $consulta->execute(array(
                ":id_prestamo" => $id_prestamo,
                ":fecha_pago" => obtenerFechaPago($fecha_inicio, $forma_pago, $i),
                ":capital_cuota" => $capital,
                ":interes_cuota" => $interes_cuota,
                ":extra_cuota" => 0,
                ":saldo_capital" => $saldo_capital,
                ":estado" => 'Pendiente'
            ));
        }

        $resultado = ['success', 'Prestamo registrado correctamente'];
    }catch(PDOException $e){
        $resultado = ["error", $e->getMessage()];
    }

    return $resultado;
}

function obtenerFrecuenciaPago($id_frecuencia_pago){
    switch($id_frecuencia_pago){
        case 1: return "Diario"; break;
        case 2: return "Semanal"; break;
        case 3: return "Quincenal"; break;
        case 4: return "Mensual"; break;
        case 'Diario': return 1; break;
        case 'Semanal': return 2; break;
        case 'Quincenal': return 3; break;
        case 'Mensual': return 4; break;
    }
}

function calcularCuotas($monto, $num_cuotas, $id_destino, $forma_pago, $fecha_inicio){
    include_once '../modelo/conexion.php';
    $conexion = conexionBaseDeDatos();
    $resultado = null;
    $tabla_cuotas = "";

    /* Variables del calculo */
    $capital = 0;
    $monto_total = 0;
    $total_cuota = 0;
    $seguro_prestamo = 0;
    $interes_total = 0;
    $total_pagar = 0;
    $saldo_capital = 0;
    $seguro_cuoto = 0;

    try{
        $sql = "SELECT * FROM destino WHERE id_destino = :id_destino";
        $consulta = $conexion->prepare($sql);
        $consulta->execute(array(":id_destino" => $id_destino));
        $fila = $consulta->fetch(PDO::FETCH_ASSOC);

        $seguro_prestamo = $monto * 0.058; // Seguro total del prestamo

        $interes_destino = obtenerInteres($fila['interes_destino'], $forma_pago); // Interes del destino segun la frecuencia de pago

        $total_cuota = obtenerCuota($monto, $interes_destino, $num_cuotas); // Monto de cada cuota
        $seguro_cuota = $seguro_prestamo / $num_cuotas; // Seguro de cada cuota

        $aux_total_cuota = $total_cuota + $seguro_cuota; // Monto de cada cuota

        $saldo_capital = $monto; // Saldo del prestamo

        for($i = 0; $i < $num_cuotas; $i++){
            $fecha_pago = new DateTime(obtenerFechaPago($fecha_inicio, $forma_pago, $i));
            $interes_cuota = $saldo_capital * pow(1 + $interes_destino, 1) - $saldo_capital; // Interes de la cuota
            $capital = $total_cuota - $interes_cuota; // Capital de la cuota
            $interes_total += $interes_cuota; // Interes total del prestamo
            $total_pagar += $total_cuota; // Total a pagar del prestamo
            $saldo_capital -= $total_cuota - $interes_cuota; // Saldo del prestamo

            $tabla_cuotas .= "<tr>
                                <td>".($i + 1)."</td>
                                <td>" . $fecha_pago->format('d-m-Y') . "</td>
                                <td>$ " . number_format($capital, 2) . "</td>
                                <td>$ " . number_format($interes_cuota, 2) . "</td>
                                <td>$ " . number_format($seguro_cuota, 2) . "</td>
                                <td>$ " . number_format($aux_total_cuota, 2)  . "</td>
                                <td>$ " . number_format($saldo_capital, 2) . "</td>
                            </tr>";
        }

        $total_pagar += $seguro_prestamo; // Total a pagar del prestamo

        $resultado = [
            number_format($seguro_prestamo, 2),
            number_format($interes_total, 2), 
            number_format($total_pagar, 2), 
            $tabla_cuotas
        ];

    }catch(PDOException $e){
        $resultado = ["error :)", $e->getMessage()];
    }

    return $resultado;

}

function obtenerCuota($monto, $tasa, $n){
    return ($monto * $tasa) / (1 - pow(1 + $tasa, -$n));
}

function obtenerInteres($interes, $forma_pago){
    $interes = ($interes / 100);

    switch($forma_pago){
        case '1': /* Diario */
            return $interes / 365;
        case '2': /* Semanal */
            return $interes / 52;
        case '3': /* Quincenal */
            return $interes / 24;
        case '4': /* Mensual */
            return $interes / 12;
    }
}

function obtenerDia($fecha_inicio, $cuotas) {
    $fecha = new DateTime($fecha_inicio);
    
    while ($cuotas > 0) {
        $fecha->modify('+1 day');

        if ($fecha->format('N') < 7) {  // 7 es domingo
            $cuotas--;
        }
    }

    return $fecha->format('Y-m-d');
}
  
function obtenerFechaPago($fecha_inicio, $forma_pago, $cuotas) {
    $fecha = new DateTime($fecha_inicio);

    switch ($forma_pago) {
        case '1': /* Diario */
            return obtenerDia($fecha_inicio, $cuotas);
        case '2': /* Semanal */
            $fecha->modify("+$cuotas week");
            break;
        case '3': /* Quincenal */
            $dias = $cuotas * 14;
            $fecha->modify("+$dias day");
            break;
        case '4': /* Mensual */
            $fecha->modify("+$cuotas month");
            break;
    }

    if($fecha->format('N') == 7){
        $fecha->modify('+1 day');
    }

    return $fecha->format('Y-m-d');
}

function mostrarInteres($id_destino){
    include_once '../modelo/conexion.php';
    $conexion = conexionBaseDeDatos();
    
    $resultado = null;

    try{
        $sql = "SELECT * FROM destino WHERE id_destino = :id_destino";
        $consulta = $conexion->prepare($sql);
        $consulta->execute(array(":id_destino" => $id_destino));

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        
        if($resultado != null){
            $resultado = [number_format($resultado['interes_destino'], 4) . '%', ''];
        }

    }catch(PDOException $e){
        $resultado = ["error :)", $e->getMessage()];
    }
    
    return $resultado;
}

function obtenerDestinos($id_forma_pago){
    include_once '../modelo/conexion.php';
    $conexion = conexionBaseDeDatos();
    
    $resultado = null;
    $select = "";
    try{
        $sql = "SELECT id_destino, nom_destino FROM destino WHERE id_forma_pago = :id_forma_pago";
        $consulta = $conexion->prepare($sql);
        $consulta->execute(array(":id_forma_pago" => $id_forma_pago));

        $select .= "<option value='0'>Seleccione</option>";
        for($i = 0; $fila = $consulta->fetch(PDO::FETCH_ASSOC); $i++){
            $select .= "<option value='" . $fila['id_destino'] . "'>" . $fila['nom_destino'] . "</option>";
        }

    }catch(PDOException $e){
        $resultado = ["error :)", $e->getMessage()];
    }
    
    $resultado = [$select, ''];

    return $resultado;
}

function buscarSocio($busqueda){
    include_once '../modelo/conexion.php';
    $conexion = conexionBaseDeDatos();
    
    $resultado = null; /* Variable de resultado */

    try{
        $sql = "SELECT * FROM socio WHERE cod_socio LIKE '%" .  
            $busqueda . "%' OR nombre_socio LIKE '%" .  
            $busqueda . "%' OR apellido_socio LIKE '%" .  
            $busqueda ."%' ORDER BY cod_socio DESC LIMIT 1";

        $consulta = $conexion->prepare($sql);
        $consulta->execute();

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

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