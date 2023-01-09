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
            p.monto_prestamo, fp.nom_forma_pago, d.nom_destino, d.interes_destino,
            IF(
                (SELECT COUNT(*) FROM cuota 
                    WHERE cuota.id_prestamo = p.id_prestamo 
                    AND cuota.estado_cuota = 'Pendiente') > 0, 'Pendiente', 'Cancelado') AS estado
        FROM prestamo AS p
        INNER JOIN socio AS s ON s.cod_socio = p.cod_socio
        INNER JOIN destino AS d ON d.id_destino = p.id_destino
        INNER JOIN forma_pago AS fp ON fp.id_forma_pago = d.id_forma_pago
        WHERE p.id_prestamo = :id_prestamo";

        $consulta = $conexion->prepare($sql);
        $consulta->execute(array(':id_prestamo' => $id_prestamo));

        if($consulta->rowCount() > 0){
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            $cuotas = obtenerCuotas($id_prestamo);

            $resultado = [
                $resultado['cod_socio'],
                $resultado['nombre_socio'], 
                $resultado['apellido_socio'], 
                $resultado['cant_cuota'], 
                number_format($resultado['monto_prestamo'], 2), 
                $resultado['nom_forma_pago'], 
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

function obtenerCuotas($id_prestamo){
    include_once '../modelo/conexion.php';
    $conexion = conexionBaseDeDatos();
    $tabla_cuotas = "";

    $sql = "SELECT * FROM cuota WHERE id_prestamo = :id_prestamo";

    $consulta = $conexion->prepare($sql);
    $consulta->execute(array(':id_prestamo' => $id_prestamo));

    $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

    $i = 1;

    foreach($resultado as $cuota){
        $tabla_cuotas .= "<tr>
                            <td>$i</td>
                            <td>" . date('d-m-Y', strtotime($cuota['fecha_pago_cuota'])) . "</td>
                            <td>$". number_format($cuota['monto_cuota'], 2)."</td>
                            <td>$". number_format($cuota['interes_cuota'], 2)."</td>
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
    $monto_cuota = 0;
    $interes_cuota = 0;

    try{
        /* Obtener el porcentaje de interes */
        $sql = "SELECT * FROM destino WHERE id_destino = :id_destino";

        $consulta = $conexion->prepare($sql);
        $consulta->execute(array(":id_destino" => $id_destino));
        $result = $consulta->fetch(PDO::FETCH_ASSOC);

        $seguro = $monto * 0.058; // 5.8%
        $interes = 0; // Interes del destino

        $interes_cuota = 0; // Monto de cada cuota
        $monto_cuota = 0; // Monto de cada cuota

        $sql = "INSERT INTO prestamo (
            cod_socio,
            id_destino,
            monto_prestamo,
            seguro_prestamo,
            fecha_emision_prestamo,
            fecha_inicio_pago
        ) VALUES (
            :cod_socio,
            :id_destino,
            :monto,
            :seguro, 
            :fecha_emision,
            :fecha_inicio
        )";

        $consulta = $conexion->prepare($sql);
        $consulta->execute(array(
            ":cod_socio" => $id_socio,
            ":id_destino" => $id_destino,
            ":monto" => $monto,
            ":seguro" => $seguro,
            ":fecha_emision" => date('Y-m-d'),
            ":fecha_inicio" => $fecha_inicio
        ));

        $id_prestamo = $conexion->lastInsertId(); // Obtenemos el id del prestamo

        for($i = 0; $i < $num_cuotas; $i++){

            $sql = "INSERT INTO cuota (
                id_prestamo,
                fecha_pago_cuota,
                monto_cuota,
                interes_cuota,
                extra_cuota,
                estado_cuota
            ) VALUES (
                :id_prestamo,
                :fecha_pago,
                :monto_cuota,
                :interes_cuota,
                :extra_cuota,
                :estado
            )";

            $consulta = $conexion->prepare($sql);
            $consulta->execute(array(
                ":id_prestamo" => $id_prestamo,
                ":fecha_pago" => obtenerFechaPago($fecha_inicio, $forma_pago, $i),
                ":monto_cuota" => $monto_cuota,
                ":interes_cuota" => $interes_cuota,
                ":extra_cuota" => 0,
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
    $interes_cuota = 0;
    $tabla_cuotas = "";

    try{
        $sql = "SELECT * FROM destino WHERE id_destino = :id_destino";
        $consulta = $conexion->prepare($sql);
        $consulta->execute(array(":id_destino" => $id_destino));
        $result = $consulta->fetch(PDO::FETCH_ASSOC);

        $seguro = $monto * 0.058; // 5.8%
        $interes = 0; // Interes del destino

        $interes_cuota = 0; // Monto de cada cuota
        $monto_cuota = 0; // Monto de cada cuota

        for($i = 0; $i < $num_cuotas; $i++){
            $fecha_pago = new DateTime(obtenerFechaPago($fecha_inicio, $forma_pago, $i));

            $tabla_cuotas .= "<tr>
                                <td>".($i + 1)."</td>
                                <td>" . $fecha_pago->format('d-m-Y') . "</td>
                                <td>$ " . number_format($monto_cuota, 2) . "</td>
                                <td>$ " . number_format($interes_cuota, 2) . "</td>
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

function obtenerDia($fecha_inicio, $cuotas) {
    $fecha = new DateTime($fecha_inicio);
    
    while ($cuotas > 0) {
        $fecha->modify('+1 day');

        if ($fecha->format('N') < 7) {  // 7 es domingo
            $cuotas--;
        }
    }

    return $fecha->format('d-m-Y');
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
            $resultado = [number_format($resultado['interes_destino'], 2) . '%', ''];
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
        $sql = "SELECT * FROM socio WHERE cod_socio LIKE '%" .  $busqueda . "%' OR nombre_socio LIKE '%" .  $busqueda . "%' OR apellido_socio LIKE '%" .  $busqueda ."%' ORDER BY cod_socio DESC LIMIT 1";
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