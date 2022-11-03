function validarCredenciales(){
    var parametros = {
        email : $("#email").val(),
        clave : $("#clave").val(),
        opcion : "validarCredenciales"
    };

    $.ajax({
        data: parametros,
        url: '../../pages/modelo/daoInicioSesion.php',
        type: 'post',
        dataType: 'json',
        error: function (thrownError) {
            mostrarMensaje("Error", thrownError, "error");
        },
        
        success: function (respuesta) {
            if(respuesta[0] == "error"){
                mostrarMensaje("Mensaje de error", respuesta[1], "error");
            }else{
                if(respuesta[0] == "success"){
                    window.location.replace("../../pages/vista/controlPanel.php");
                }
            }
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
