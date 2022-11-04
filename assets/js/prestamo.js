function registrarPrestamo(){
    var parametro = {
        opcion : "registrarPrestamo",
        cod_socio : $("#input-cod-socio").val(),
        monto : $("#input-monto-prestamo").val(),
        num_cuotas : $("#input-num-cuotas").val(),
        id_destino : $("#select-destino").val(),
        forma_pago : $("#select-forma-pago").val(),
        fecha_inicio : $("#input-fecha-inicio").val()
    }

    $.ajax({
        data: parametro,
        url: '../../pages/modelo/daoPrestamo.php',
        type: 'post',
        dataType: 'json',
        error: function (thrownError) {
            mostrarMensaje("Error", thrownError, "error");
        },

        success: function (respuesta) {
            mostrarMensaje("Mensaje", respuesta[1], respuesta[0]);

            setTimeout(function(){
                window.location.replace("controlPanel.php?modulo=prestamos");
            }, 1000);   
        }
    })
}

function calcularCuotas(){
    var parametros = {
        opcion : "calcularCuotas",
        monto : $("#input-monto-prestamo").val(),
        num_cuotas : $("#input-num-cuotas").val(),
        id_destino : $("#select-destino").val(),
        forma_pago : $("#select-forma-pago").val(),
        fecha_inicio : $("#input-fecha-inicio").val()
    }

    $.ajax({
        data: parametros,
        url: '../../pages/modelo/daoPrestamo.php',
        type: 'post',
        dataType: 'json',
        error: function (thrownError) {
            mostrarMensaje("Error", thrownError, "error");
        },

        success: function (respuesta) {
            if(respuesta[0] == "error"){
                mostrarMensaje("Error", respuesta[1], "error");
            }else{
                $("#input-monto-cuota").val(respuesta[0]);
                $("#input-monto-interes").val(respuesta[1]);
                $("#input-seguro-prestamo").val(respuesta[2]);
                $("#input-monto-total").val(respuesta[3]);
                $("#tabla-cuotas").html(respuesta[4]);
            }
        }
    })
}

function mostrarInteres(){
    var parametros = {
        opcion : "obtenerInteres",
        codigo_destino : $("#select-destino").val() //obtengo el valor del select
    }

    $.ajax({
        data: parametros,
        url: '../../pages/modelo/daoPrestamo.php',
        type: 'post',
        dataType: 'json',
        error: function (thrownError) {
            mostrarMensaje("Error", thrownError, "error");
        },

        success: function (respuesta) {
            $("#input-interes").val(respuesta[0]);
        }
    });
}

function buscarSocio(){
    var parametros = {
        busqueda : $("#input-buscar-socio").val(),
        opcion :"buscarSocio"
    }

    $.ajax({
        data: parametros,
        url: '../../pages/modelo/daoPrestamo.php',
        type: 'post',
        dataType: 'json',
        error: function (thrownError) {
            mostrarMensaje("Error", thrownError, "error");
        },

        success: function (respuesta) {
            $("#input-cod-socio").val(respuesta[0]);
            $("#input-nom-socio").val(respuesta[1]);
        }
    });
}

function mostrarMensaje(titulo, texto, tipo){
    Swal.fire({
        title: titulo,
        text: texto,
        icon: tipo,
        confirmButtonColor: '#217373',
        confirmButtonText: 'Cerrar'
    });
}