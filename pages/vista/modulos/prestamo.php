<div class="container-xxl flex-grow-2">

    <div class="d-flex align-items-center justify-content-between py-3">
        <h4 class="fw-bold py-1 m-0"><span class="text-muted fw-light">Préstamos /</span> Listado de Préstamos</h4>
        <button class="btn btn-primary" type="button" onclick="window.location.href='?modulo=prestamos&nuevo=true'"><span class="tf-icons bx bx-plus-circle"></span> Nuevo</button>
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
            <table id="tablaPrestamos" class="table table-striped table-sm" wodth>
                <thead>
                    <tr>
                        <th class="th-sm">ID</th>
                        <th>Código socio</th>
                        <th>Socio</th>
                        <th>Monto Inicial</th>
                        <th>Fecha de Emisión</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="pagination-tabla" class="table-border-bottom-0">
                    <?php 
                        include_once '../modelo/conexion.php';
                        $conexion = conexionBaseDeDatos();

                        try{
                            $sql = "SELECT * FROM prestamo INNER JOIN socio ON prestamo.cod_socio = socio.cod_socio";
                            $statement = $conexion->prepare($sql);
                            $statement->execute();
                
                            while($resultado = $statement->fetch(PDO::FETCH_ASSOC)){
                                echo "<tr>";
                                echo "<td>".$resultado['id_prestamo']."</td>";
                                echo "<td>".$resultado['cod_socio']."</td>";
                                echo "<td>".$resultado['nombre_socio']. " " . $resultado['apellido_socio'] ."</td>";
                                echo "<td> $". number_format($resultado['monto_prestamo'], 2) ."</td>";
                                echo "<td>".$resultado['fecha_emision_prestamo']."</td>";
                                echo "<td><span class='badge bg-label-danger me-1'>Pendiente</span></td>";
                                echo "<td><button class='btn btn-outline-info' type='button'><span class='tf-icons bx bx-show'></span> Ver pagos</td>";
                                echo "</tr>";
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
