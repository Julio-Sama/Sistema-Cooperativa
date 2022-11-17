<!DOCTYPE html>
<html lang="es" class="layout-menu-collapsed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/"
    data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../../../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../../../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../../../assets/css/demo.css" />

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../../../assets/js/config.js"></script>
</head>

<body>
    <div class="card">
        <div class="card-body">
            <div class="text-nowrap mb-3 text-black">
                <div class="row">
                    <div class="col-md-12 text-center text-black text-uppercase">
                        <span id="nom-asociacion">Cooperativa Don Teco S. A. de C. V.</span>
                    </div>
                    <div class="col-md-12 text-center text-black text-uppercase">
                        <span id="dir-asociacion">El Salvador, San Vicente 3 Calle Oriente</span>
                    </div>
                    <div class="col-md-12 text-center text-black text-uppercase">
                        <span id="tel-asociacion">+503 2309-1022</span>
                    </div>
                    <div class="col-md-12 text-center text-black text-uppercase">
                        Plan de Pagos
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        Código de socio: <span id="n-prestamo">JR20012</span>
                    </div>
                    <div class="col-md-8">
                        Nombre: <span id="fecha-emision">Josue Adonay Aguilar Rivas</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        N° de Préstamo: <span id="n-prestamo">00001</span>
                    </div>
                    <div class="col-md-4">
                        Fecha de emisión: <span id="fecha-emision">15/11/2022</span>
                    </div>
                    <div class="col-md-4">
                        Forma de Pago: <span id="forma-pago">Mensual</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        Cantidad de cuotas: <span id="numero-cuotas-plan">10</span>
                    </div>
                    <div class="col-md-4">
                        Monto: <span id="monto-plan">$11000.00</span>
                    </div>
                    <div class="col-md-4">
                        Interés: <span id="numero-cuotas-plan">$200.00</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        Abono a capital: <span id="forma-pago-plan">$290.00</span>
                    </div>
                    <div class="col-md-4">
                        Destino: <span id="destino-plan">Personal</span>
                    </div>
                    <div class="col-md-4">
                        Seguro: <span id="destino-plan">$174.00</span>
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

            <table class="table table-responsive-lg ">
                <thead>
                    <tr class="text-nowrap">
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Monto</th>
                        <th>Mora</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody id="tabla-plan-pagos">
                    <tr>
                        <td colspan="5" class="text-center">No hay datos</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>