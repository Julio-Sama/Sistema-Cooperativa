<?php

function conexionBaseDeDatos(){
	$user = 'root';
	$password = '';

    try{
        $connection = "mysql:host=localhost;dbname=bd_cooperativa";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        
        $pdo = new PDO($connection, $user, $password, $options);

        return $pdo;
    }catch(PDOException $e){
        print_r('Error connection: ' . $e->getMessage());
    }
}

?>