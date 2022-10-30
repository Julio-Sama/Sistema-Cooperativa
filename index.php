<?php @session_start();

    if(isset($_SESSION['autenticado']) && $_SESSION['autenticado'] == true){
        header("Location: ./pages/vista/controlPanel.php");
    }else{
        header("Location: ./pages/vista/inicioSesion.php");
        exit();
    }
?>