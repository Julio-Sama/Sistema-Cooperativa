<form autocomplete="off">
    <div class="container-xxl flex-grow-1">
        <div class="d-flex align-items-center justify-content-between py-3">
            <h4 class="fw-bold py-1 m-0"><span class="text-muted fw-light">Préstamos /</span> Nuevo préstamo</h4>
            <div>
                <button type="button" class="btn btn-primary" id="btn-reg-prestamo" onclick="registrarPrestamo()" disabled>Registrar préstamo</button>
                <button type="button" class="btn btn-outline-danger" id="btn-cancel-prestamo" onclick="window.location.href='controlPanel.php?modulo=prestamos'">Cancelar</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card p-4">
                    <div>
                        <h5 class="fw-bold">Datos del socio</h5>
                        <hr>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="input-buscar-socio">Buscar socio</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text" id="basic-addon-search31"><i
                                        class="bx bx-search"></i></span>
                                <input id="input-buscar-socio" type="search" class="form-control"
                                    placeholder="Buscar socio" aria-label="Search..."
                                    onkeypress="buscarSocio()"
                                    aria-describedby="basic-addon-search31" autofocus/>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="input-cod-socio">Código de socio</label>
                            <input type="text" class="form-control" id="input-cod-socio" placeholder="000000"
                                readonly />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="input-nom-socio">Nombre completo</label>
                            <input type="text" class="form-control" id="input-nom-socio" placeholder="Nombre del socio"
                                readonly />
                        </div>

                    </div>

                    <div class="row">
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mt-4">
                <div class="card p-4">
                    <div>
                        <h5 class="fw-bold">Datos del prestamo</h5>
                        <hr>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="input-monto-prestamo">Monto</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text" id="basic-addon-search31">$</span>
                                <input id="input-monto-prestamo" type="text" class="form-control" placeholder="0.00" onkeypress="return filterFloat(event,this);"/>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="input-num-cuotas">N° de cuotas</label>
                            <input type="number"min="5" class="form-control" id="input-num-cuotas" placeholder="0" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="select-forma-pago">Frecuencia de Pago</label>
                            <select class="form-select" id="select-forma-pago">
                                <option value="0" selected>Seleccione</option>
                                <option value="1">Diario</option>
                                <option value="2">Semanal</option>
                                <option value="3">Quincenal</option>
                                <option value="4">Mensual</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="select-destino">Destino</label>
                            <select class="form-select" id="select-destino" onchange="mostrarInteres()">
                                <option value="0" selected>Seleccione</option>

                                <?php
                                    include_once '../modelo/conexion.php';
                                    $conexion = conexionBaseDeDatos();
            
                                    try{
                                        $sql = "SELECT * FROM destino";
                                        $consulta = $conexion->prepare($sql);
                                        $consulta->execute();
            
                                        if($consulta->rowCount() <= 0){
                                            echo "<option value='0'>No hay destinos</option>";
                                        }else{
                                            while($resultado = $consulta->fetch(PDO::FETCH_ASSOC)){
                                                echo "<option value='".$resultado['id_destino']."'>".$resultado['nom_destino']."</option>";
                                            }
                                        }
            
                                    }catch(PDOException $e){
                                        echo $e->getMessage();
                                    }
                                ?>

                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="input-interes">Tasa de Interés Anual (%)</label>
                            <input type="text" class="form-control" value="" id="input-interes" placeholder="0.00%" disabled />
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            
                            <label class="form-label" for="input-fecha-inicio">Fecha de inicio</label>
                            <input type="date" class="form-control" id="input-fecha-inicio" min="today" required pattern="\d{4}-\d{2}-\d{2}"/>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="">&nbsp</label><br>
                            <button type="button" class="btn btn-outline-info w-100" id="btn-calcular-cuotas" onclick="calcularCuotas()">Calcular cuotas</button>
                        </div>
                    </div>

                    <div class="row">                       
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="input-total-seguro">Total seguro</label>
                            <div class="input-group input-group-merge disabled">
                                <span class="input-group-text" id="basic-addon-search31">$</span>
                                <input id="input-total-seguro" type="text" class="form-control" value=""
                                    placeholder="0.00" disabled />
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="input-total-interes">Total Interés</label>
                            <div class="input-group input-group-merge disabled">
                                <span class="input-group-text" id="basic-addon-search31">$</span>
                                <input id="input-total-interes" type="text" class="form-control" value=""
                                    placeholder="0.00" disabled />
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="input-total-pagar">Total a pagar</label>
                            <div class="input-group input-group-merge disabled">
                                <span class="input-group-text" id="basic-addon-search31">$</span>
                                <input id="input-total-pagar" type="text" class="form-control" value=""
                                    placeholder="0.00" disabled />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mt-4">
                <div class="card">
                    
                    <div class="card-body">
                        <div>
                            <h5 class="fw-bold">Plan de pagos</h5>
                            <hr>
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr class="text-nowrap">
                                        <th>#</th>
                                        <th>Fecha</th>
                                        <th>Capital</th>
                                        <th>Interés</th>
                                        <th>Seguro</th>
                                        <th>Total cuota</th>
                                        <th>Saldo capital</th>
                                    </tr>
                                </thead>

                                <tbody id="tabla-cuotas">
                                    <tr>
                                        <td colspan="7" class="text-center">No hay datos para mostrar</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

    </div>
</form>