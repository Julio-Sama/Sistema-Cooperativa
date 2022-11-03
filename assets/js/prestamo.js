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
        confirmButtonColor: '#217373'
    });
}