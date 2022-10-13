<?php
    if(isset($_SESSION['autenticado']) && $_SESSION['autenticado'] == 'SI'){
        
    }else{
        echo "<script language='javascript'>";
        echo "location.href='./pages/controlador/controladorInicioSesion.php';";
        echo "</script>";
    }
?>