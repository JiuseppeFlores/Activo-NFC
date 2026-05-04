<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include("../conexion.php");
    $usuario = $_POST["usuario"];
    $id = $_POST["id"];
    $sqlId = "";
    if ($id != 0) {
        $sqlId = " AND idUsuario != '$id' ";
    }
    $sql = "SELECT * FROM tblUsuario WHERE usuario='$usuario' $sqlId";
    $query = sqlsrv_query($con, $sql);
    $count = sqlsrv_has_rows($query);
    if ($count === false) {
        echo 1;
    } else {
        echo 2;
    }
} else {
    echo "No tiene acceso a esta parte del sistema.";
}