<form>
    <div class="container-xxl flex-grow-1">
        <div class="d-flex align-items-center justify-content-between py-3">
            <h4 class="fw-bold py-1 m-0"><span class="text-muted fw-light">Préstamos /</span> Nuevo préstamo</h4>
            <div>
                <button type="button" class="btn btn-primary">Registrar préstamo</button>
                <button type="button" class="btn btn-outline-danger" onclick="window.location.href='controlPanel.php?modulo=prestamos'">Cancelar</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card p-4">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="basic-default-fullname">Buscar socio</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text" id="basic-addon-search31"><i class="bx bx-search"></i></span>
                                <input
                                    id="basic-default-fullname"
                                    type="text"
                                    class="form-control"
                                    placeholder="Buscar socio"
                                    aria-label="Search..."
                                    aria-describedby="basic-addon-search31"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="basic-default-fullname">Código de socio</label>
                            <input type="text" class="form-control" id="basic-default-fullname" placeholder="000000" readonly/>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label" for="basic-default-fullname">Nombre completo</label>
                            <input type="text" class="form-control" id="basic-default-fullname" placeholder="Nombre del socio" readonly/>
                        </div>
                    </div> 
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 mt-4">
                <div class="card p-4">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="basic-default-fullname">Monto</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text" id="basic-addon-search31">$</span>
                                <input
                                    id="basic-default-fullname"
                                    type="text"
                                    class="form-control"
                                    placeholder="0.00"
                                    aria-label="Search..."
                                    aria-describedby="basic-addon-search31"
                                />
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="basic-default-fullname">N° de cuotas</label>
                            <input type="number" class="form-control" id="basic-default-fullname" placeholder="0"/>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="basic-default-fullname">Destino</label>
                            <select class="form-select" id="exampleFormControlSelect1" aria-label="Default select example">
                                <option selected>Seleccione</option>
                                <?php 
                                    include_once '../modelo/conexion.php';
                                    $pdo = conexionBaseDeDatos();
                                    $sql = "SELECT * FROM destino";
                                    $query = $pdo->prepare($sql);
                                    $query->execute();

                                    $resultado = $query->fetchAll();
                                    
                                    foreach($resultado as $dato){
                                        echo '<option value="'.$dato['id_destino'].'">'.$dato['nom_destino'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="basic-default-fullname">Tasa de Interés (%)</label>
                            <input type="text" class="form-control" id="basic-default-fullname" placeholder="0" readonly/>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="basic-default-fullname">Forma de Pago</label>
                            <select class="form-select" id="exampleFormControlSelect1" aria-label="Default select example">
                                <option selected>Seleccione</option>
                                <option value="1">Diario</option>
                                <option value="2">Quincenal</option>
                                <option value="3">Mensual</option>
                                <option value="4">Anual</option>
                            </select>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label" for="basic-default-fullname">Fecha de inicio</label>
                            <input type="date" class="form-control" id="basic-default-fullname"/>
                        </div>

                        <div class="col-md-3 mb-3">
                        <label class="form-label" for="">&nbsp</label><br>
                            <button type="button" class="btn btn-outline-info w-100">Calcular cuotas</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="basic-default-fullname">Moton por cuota</label>
                            <input type="text" class="form-control" id="basic-default-fullname" disabled value="$   0.00"/>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="basic-default-fullname">Total interés</label>
                            <input type="text" class="form-control" id="basic-default-fullname" disabled value="$   0.00"/>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="">Monto total</label><br>
                            <input type="text" class="form-control" id="basic-default-fullname" disabled value="$   0.00"/>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mt-4">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr class="text-nowrap">
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                </tr>
                            </thead>

                                <!--Generar cuotas -->
                            
                        </table>
                        </div>
                    </div>
                </div>
            </div>  
        </div>   
    </div>
</form>
