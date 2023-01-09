$(document).ready(function (){  
	$("#input-monto-prestamo, #input-num-cuotas, #select-destino, #select-forma-pago, #input-fecha-inicio").change(function () {   
        $("#btn-reg-prestamo").attr("disabled", true);
	}); 
}); /* Validación por cambios en el formulario de registro */

/* funcion de registro de prestamos */
function registrarPrestamo(){
    var parametro = {
        opcion : "registrarPrestamo",
        cod_socio : $("#input-cod-socio").val(),
        monto : $("#input-monto-prestamo").val(),
        num_cuotas : $("#input-num-cuotas").val(),
        id_destino : $("#select-destino").val(),
        fecha_inicio : $("#input-fecha-inicio").val(),
        forma_pago : $("#select-forma-pago").val()
    }

    $.ajax({
        data: parametro,
        url: '../../pages/modelo/daoPrestamo.php',
        type: 'post',
        dataType: 'json',

        error: function (thrownMessage) {
            mostrarMensaje("Error", thrownMessage, "error");
        },

        
        success: function (respuesta) {
            if(respuesta[0] == "error"){
                mostrarMensaje("Error", respuesta[1], "error");
            }else{
                mostrarMensaje("Mensaje", respuesta[1], respuesta[0]);

                setTimeout(function(){
                    window.location.replace("controlPanel.php?modulo=prestamos");
                }, 1000); 
            }  
        }


    })
}

/* Mostrar el plan de pagos */
function mostrarPlanDePagos(id_prestamo){
    var parametros = {
        opcion : "obtenerPlanDePagos",
        cod_prestamo : id_prestamo
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
            $("#cod-socio-plan").html(respuesta[0]);
            $("#nombre-socio-plan").html(respuesta[1] + " " + respuesta[2]);
            $("#numero-cuotas-plan").html(respuesta[3]);
            $("#monto-plan").html("$" + respuesta[4]);
            $("#forma-pago-plan").html(respuesta[5]);
            $("#destino-plan").html(respuesta[6]);
            $("#interes-plan").html(respuesta[7] + "%");
            $("#estado-plan").html(respuesta[8]);
            $("#tabla-plan-pagos").html(respuesta[9]);
        }
    });
}

/* funcion para calcular cuotas */
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
                
                $("#btn-reg-prestamo").attr("disabled", false);
            }
        }
    })
}

/* funcion mostrar el procentaje de interes en el input */
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

function mostrarDestinos(){
    var parametros = {
        opcion : "obtenerDestinos",
        id_forma_pago : $("#select-forma-pago").val()
    }

    if(parametros.id_forma_pago == '0'){
        $("#select-destino").prop("disabled", true);
        $("#select-destino").html("<option value='0'>Seleccione</option>");
        $("#input-interes").val("");
    }else{
        $.ajax({
            data: parametros,
            url: '../../pages/modelo/daoPrestamo.php',
            type: 'post',
            dataType: 'json',
            error: function (thrownError) {
                mostrarMensaje("Error", thrownError, "error");
            },

            success: function (respuesta) {
                $("#select-destino").html(respuesta);
                $("#select-destino").prop("disabled", false);
                $("#input-interes").val("");
            }
        });
    }

    
}

/* funcion buscar el socio en tiempo real */
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

/* Función para validar el monto */
function filterFloat(evt,input){
    var key = window.Event ? evt.which : evt.keyCode;    
    var chark = String.fromCharCode(key);
    var tempValue = input.value+chark;
    
    if(key >= 48 && key <= 57){
        if(filter(tempValue)=== false){
            return false;
        }else{       
            return true;
        }
    }else{
        if(key == 8 || key == 13 || key == 46 || key == 0) { 
            return true;
        }else{
            return false;
        }
    }
}

function filter(__val__){
    var preg = /^([0-9]+\.?[0-9]{0,2})$/; 
    if(preg.test(__val__) === true){
        return true;
    }else{
       return false;
    }
    
}

/* Función para mostrar mensajes */
function mostrarMensaje(titulo, texto, tipo){
    Swal.fire({
        title: titulo,
        text: texto,
        icon: tipo,
        confirmButtonColor: '#217373',
        confirmButtonText: 'Cerrar',
        allowOutsideClick: false
    });
}