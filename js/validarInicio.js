function validarInicio() {
    var parametros = {
        user: $('#email-username').val(),
        pass: $('#password').val(),
        opcion: 'ingresoCredenciales'
    }
    
    $.ajax({
        data: parametros,
        url: '../pages/controlador/controladorInicioSesion.php',
        type: 'post',
        dataType: 'json',
        success: function(respuesta) {
            if (respuesta[0] == "NO") {
                alert(respuesta[1])
            }
        }
    });
}