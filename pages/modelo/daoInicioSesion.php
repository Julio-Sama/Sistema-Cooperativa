<?php
    @session_start();

    if($_REQUEST['opcion'] = "validarCredenciales"){
        $email = $_REQUEST['email'];
        $clave = $_REQUEST['clave'];
        $mensaje = ""; /* Variable de mensaje */

        if(empty($email) || empty($clave)){
            $mensaje = ["error", "Debe ingresar el correo electrónico y la contraseña"];
        }else{
            $mensaje = validarCredenciales($email, $clave);
        }

        echo json_encode($mensaje);
    }

    function validarCredenciales($email, $clave){
        include_once("conexion.php");
        $conexion = conexionBaseDeDatos();
        $mensaje = "";
        
        $clave = hash('sha512', $clave); /* Encriptación de la contraseña */

        try{
            $sql = "SELECT * FROM configuracion WHERE usuario_config = :email AND clave = :clave";
            $statement = $conexion->prepare($sql);
            $statement->bindParam(":email", $email);
            $statement->bindParam(":clave", $clave);
            $statement->execute();

            $resultado = $statement->fetch(PDO::FETCH_ASSOC);

            if($resultado){
                $_SESSION['autenticado'] = true;
                $_SESSION['id'] = $resultado['id_config'];
                
                $mensaje = ["success", "Credenciales correctas"];
            }else{
                $mensaje = ["error", "El correo electrónico o la contraseña son incorrectos"];
            }
        }catch(PDOException $e){
            $mensaje = ["error", "Error al validar credenciales"];
        }

        return $mensaje;
    }
?>