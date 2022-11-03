<form>
    <div class="container-xxl flex-grow-1">
        <div class="d-flex align-items-center justify-content-between py-3">
            <h4 class="fw-bold py-1 m-0"><span class="text-muted fw-light">Préstamos /</span> Nuevo préstamo</h4>
            <div>
                <button type="button" class="btn btn-primary" id="btn-reg-prestamo" onclick="registrarPrestamo()">Registrar préstamo</button>
                <button type="button" class="btn btn-outline-danger" id="btn-cancel-prestamo">Cancelar</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card p-4">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="input-buscar-socio">Buscar socio</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text" id="basic-addon-search31"><i
                                        class="bx bx-search"></i></span>
                                <input id="input-buscar-socio" type="search" class="form-control"
                                    placeholder="Buscar socio" aria-label="Search..."
                                    onkeypress="buscarSocio()"
                                    aria-describedby="basic-addon-search31" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="input-cod-socio">Código de socio</label>
                            <input type="text" class="form-control" id="input-cod-socio" placeholder="000000"
                                readonly />
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label" for="input-nom-socio">Nombre completo</label>
                            <input type="text" class="form-control" id="input-nom-socio" placeholder="Nombre del socio"
                                readonly />
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
                            <label class="form-label" for="input-monto-prestamo">Monto</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text" id="basic-addon-search31">$</span>
                                <input id="input-monto-prestamo" type="text" class="form-control" placeholder="0.00" />
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="input-num-cuotas">N° de cuotas</label>
                            <input type="number" class="form-control" id="input-num-cuotas" placeholder="0" />
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="select-destino">Destino</label>
                            <select class="form-select" id="select-destino" aria-label="Default select example" onchange="mostrarInteres()">
                                <option selected>Seleccione</option>
                                <?php 
                                    include_once '../modelo/conexion.php';
                                    $pdo = conexionBaseDeDatos();
                                    $sql = "SELECT * FROM destino";
                                    $query = $pdo->prepare($sql);
                                    $query->execute();

                                    $resultado = $query->fetchAll();
                                    
                                    foreach($resultado as $dato){ //Recorremos el array de datos
                                        echo '<option value="'.$dato['id_destino'].'">'.$dato['nom_destino'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="input-interes">Tasa de Interés (%)</label>
                            <input type="text" class="form-control" value="" id="input-interes" placeholder="0.00%" disabled />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="select-forma-pago">Forma de Pago</label>
                            <select class="form-select" id="select-forma-pago" aria-label="Default select example">
                                <option selected>Seleccione</option>
                                <option value="day">Diario</option>
                                <option value="week">Semanal</option>
                                <option value="week 5 day">Quincenal</option>
                                <option value="month">Mensual</option>
                            </select>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label" for="input-fecha-inicio">Fecha de inicio</label>
                            <input type="date" class="form-control" id="input-fecha-inicio" required pattern="\d{4}-\d{2}-\d{2}"/>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="">&nbsp</label><br>
                            <button type="button" class="btn btn-outline-info w-100" id="btn-calcular-cuotas" onclick="calcularCuotas()">Generar</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="input-monto-cuota">Monto por cuota</label>
                            <div class="input-group input-group-merge disabled">
                                <span class="input-group-text" id="basic-addon-search31">$</span>
                                <input id="input-monto-cuota" type="text" class="form-control" value=""
                                    placeholder="0.00" disabled />
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="input-monto-interes">Total interés</label>
                            <div class="input-group input-group-merge disabled">
                                <span class="input-group-text" id="basic-addon-search31">$</span>
                                <input id="input-monto-interes" type="text" class="form-control" value=""
                                    placeholder="0.00" disabled />
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="input-seguro-prestamo">Seguro</label>
                            <div class="input-group input-group-merge disabled">
                                <span class="input-group-text" id="basic-addon-search31">$</span>
                                <input id="input-seguro-prestamo" type="text" class="form-control" value=""
                                    placeholder="0.00" disabled />
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="input-monto-total">Monto total</label>
                            <div class="input-group input-group-merge disabled">
                                <span class="input-group-text" id="basic-addon-search31">$</span>
                                <input id="input-monto-total" type="text" class="form-control" value=""
                                    placeholder="0.00" disabled />
                            </div>
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

                                <tbody id="tabla-cuotas">
                                    <tr>
                                        <td colspan="3" class="text-center">No hay datos</td>
                                    </tr>
                                </tbody>

                                <!--Generar cuotas -->

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>