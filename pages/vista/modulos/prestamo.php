<div class="container-xxl flex-grow-2">

    <div class="d-flex align-items-center justify-content-between py-3">
        <h4 class="fw-bold py-1 m-0"><span class="text-muted fw-light">Préstamos /</span> Listado de Préstamos</h4>
        <button class="btn btn-primary" type="button"
            onclick="window.location.href='?modulo=prestamos&nuevo=true'"><span
                class="tf-icons bx bx-plus-circle"></span> Nuevo</button>
    </div>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="card-header col-md-12">
            <!-- Search -->
            <div class="row">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Buscar...">
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table id="tablaPrestamos" class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th class="th-sm">ID</th>
                        <th>Código socio</th>
                        <th>Socio</th>
                        <th>Monto</th>
                        <th>Fecha de Emisión</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        include_once '../modelo/conexion.php';
                        $conexion = conexionBaseDeDatos();

                        try{
                            $sql = "SELECT p.id_prestamo, p.fecha_emision_prestamo, s.cod_socio, s.nombre_socio, s.apellido_socio,
                                        p.monto_prestamo,
                                        IF(
                                            (SELECT COUNT(*) FROM cuota 
                                                WHERE cuota.id_prestamo = p.id_prestamo 
                                                AND cuota.estado_cuota = 'Pendiente') > 0, 'Pendiente', 'Cancelado') AS estado
                                    FROM prestamo AS p
                                    INNER JOIN socio AS s ON s.cod_socio = p.cod_socio
                                    INNER JOIN destino AS d ON d.id_destino = p.id_destino
                                    ORDER BY p.id_prestamo ASC";
                            $consulta = $conexion->prepare($sql);
                            $consulta->execute();

                            if($consulta->rowCount() <= 0){
                                echo "<tr><td colspan='7' class='text-center'>No hay datos para mostrar</td></tr>";
                            }else{
                                while($resultado = $consulta->fetch(PDO::FETCH_ASSOC)){
                                    echo "<tr>";
                                    echo "<td>".$resultado['id_prestamo']."</td>";
                                    echo "<td>".$resultado['cod_socio']."</td>";
                                    echo "<td>".$resultado['nombre_socio']. " " . $resultado['apellido_socio'] ."</td>";
                                    echo "<td> $". number_format($resultado['monto_prestamo'], 2) ."</td>";
                                    echo "<td>". date('d-m-Y', strtotime($resultado['fecha_emision_prestamo'])) ."</td>";

                                    if($resultado['estado'] == 'Pendiente'){
                                        echo "<td><span class='badge rounded-pill bg-danger'>Pendiente</span></td>";
                                    }else{
                                        echo "<td><span class='badge rounded-pill bg-success'>Cancelado</span></td>";
                                    }

                                    echo "<td>";
                                    echo    '<div class="dropdown-icon-demo">
                                                <button type="button" class="btn btn-outline-info dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-menu"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modalPlandePagos" onclick="mostrarPlanDePagos('. $resultado['id_prestamo'] .')"><i class="bx bx-show me-1"></i>Plan de pagos</a>
                                                    <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trending-down me-1"></i> Amortización</a>
                                                    <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-coin-stack me-1"></i> Realizar pagos</a>
                                                    
                                                </div>
                                            </div>';
                                    echo "</td>";

                                    echo "</tr>";
                                }
                            }

                        }catch(PDOException $e){
                            echo $e->getMessage();
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal plan de pagos -->
<div class="modal fade" id="modalPlandePagos" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Plan de pagos</h5>
            </div>

            <div class="modal-body">
                <div class="text-nowrap mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            Código de socio: <span id="cod-socio-plan"></span>
                        </div>
                        <div class="col-md-6">
                            Nombre: <span id="nombre-socio-plan"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            Número de cuotas: <span id="numero-cuotas-plan"></span>
                        </div>
                        <div class="col-md-6">
                            Monto: <span id="monto-plan"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            Frecuencia de pago: <span id="forma-pago-plan"></span>
                        </div>
                        <div class="col-md-6">
                            Destino: <span id="destino-plan"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            Interés: <span id="interes-plan"></span>
                        </div>
                        <div class="col-md-6">
                            Estado del préstamo: <span id="estado-plan"></span>
                        </div>
                    </div>
                </div>

                <table class="table table-responsive-lg">
                    <thead>
                        <tr class="text-nowrap">
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Capital</th>
                            <th>Interés</th>
                            <th>Seguro</th>
                            <th>Total cuota</th>
                            <th>Saldo capital</th>
                            <th>Estado</th>
                        </tr>
                    </thead>

                    <tbody id="tabla-plan-pagos">
                        <tr>
                            <td colspan="7" class="text-center">No hay datos</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
                <button type="button" class="btn btn-primary"><i class="bx bx-file"></i> Generar PDF</button>
            </div>
        </div>
    </div>
</div>