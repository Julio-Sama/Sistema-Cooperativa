<?php
session_start();

unset($_SESSION["autenticado"]);

session_destroy();

echo "<script language='javascript'>";
echo "location.href='inicioSesion.php';";
echo "</script>";

?>