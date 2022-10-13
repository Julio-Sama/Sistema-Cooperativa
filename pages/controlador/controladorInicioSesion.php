<?php
    @session_start();

    if(isset($_SESSION['autenticado']) && $_SESSION['autenticado'] == 'SI'){
        /* Reenviar a Panel de Control */
    }else{
        $mensaje = "";

        if(isset($_REQUEST['email-username']) && isset($_REQUEST['password'])){
            if($_REQUEST['email-username'] == "" || $_REQUEST['password'] == ""){
                $mensaje = "empty";
            }else{
                include_once '../modelo/conexion.php';
                $conexion = connect();
                
                $usuario = $_REQUEST['email-username'];
                $clave = hash('sha512', $_REQUEST['password']);

                $sql = "SELECT * FROM configuracion WHERE usuario_config = :usuario AND clave = :clave";
                $statement = $conexion->prepare($sql);
                $statement->bindParam(':usuario', $usuario);
                $statement->bindParam(':clave', $clave);
                $statement->execute();

                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                
                if($statement->rowCount() > 0){
                    while($fila = $statement->fetch(PDO::FETCH_ASSOC)){
                        $_SESSION['autenticado'] = 'SI';
                    }

                    echo json_encode($result);
                    /* Reenviar a Panel de Control */
                }else{
                    $mensaje = "error";
                }
            } 
        }
        
        include_once '../vista/titulo.php';
        include_once '../vista/inicioSesion.php';
    }
?>