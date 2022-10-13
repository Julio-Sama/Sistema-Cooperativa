<div class="container-xxl flex-grow-2 container-p-y">

    <div class="d-flex align-items-center justify-content-between py-3">
        <h4 class="fw-bold py-1 m-0"><span class="text-muted fw-light">Préstamos /</span> Listado de Préstamos</h4>
        <button class="btn btn-primary" type="button"><span class="tf-icons bx bx-plus-circle"></span> Nuevo</button>
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
        <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Código socio</th>
                <th>Socio</th>
                <th>Monto Inicial</th>
                <th>Fecha de Emisión</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            <?php 
                include_once '../modelo/dao/daoPrestamo.php';
                
                $prestamos = getPrestamos();

                foreach($prestamos as $prestamo){
                    echo '<tr>';
                    echo '<td>'.$prestamo['id_prestamo'].'</td>';
                    echo '<td>'.$prestamo['cod_socio'].'</td>';
                    echo '<td>'.$prestamo['nombre_socio'].'</td>';
                    echo '<td>$'.number_format($prestamo['monto_prestamo']).'</td>';
                    echo '<td>'.$prestamo['fecha_emision_prestamo'].'</td>';
                    echo '<td><span class="badge bg-label-warning me-1">Pendiente</span></td>';
                    echo '<td><a class="text-info" href="prestamo.php?accion=editar&id='.$prestamo['id_prestamo'].'"><span class="tf-icons bx bx-edit"></span></a> <a class="text-danger" href="prestamo.php?accion=eliminar&id='.$prestamo['id_prestamo'].'"><span class="tf-icons bx bx-trash"></span></a></td>';
                    echo '</tr>';
                }
            ?>
        </tbody>
        </table>
    </div>
    </div>
</div>
    
