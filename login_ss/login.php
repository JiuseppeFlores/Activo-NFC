<?php
session_start();

$username ="";
$password ="";
if (isset($_POST['name'])) {
$username = $_POST['name'];
}
if (isset($_POST['pwd'])) {

$password = $_POST['pwd'];
}
include("../conexion.php");


$array=array($username,$password);

$consulta = "SELECT * FROM tblUsuarios WHERE usuario=? AND password=?";
$ejecutar=sqlsrv_query($con,$consulta,$array);
$row_count = sqlsrv_has_rows($ejecutar);
        if ($row_count === false){
            echo 2;
        }else{
                $row = sqlsrv_fetch_array($ejecutar);
                $id_usuario=$row['idUsuario'];
                $_SESSION['id'] = $row['idUsuario'];
                $_SESSION['nombre'] = $row['nombres'];

                echo 1;

        }


?>
