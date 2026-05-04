<?php

include("../conexion.php");
$rol = $_POST["rol"];
$idUsuario = $_SESSION['idUsuario'];
$id = guidv4();
$sql = "  INSERT INTO tblRol (rol, idUsuarioCreador) VALUES ('$rol', '$idUsuario');";
$sql_rep = "SELECT * FROM tblRol WHERE rol='$rol' ";
$query_rep = sqlsrv_query($con, $sql_rep);
$count_rep = sqlsrv_has_rows($query_rep);
if ($count_rep === false) {
    $query = sqlsrv_query($con, $sql);
    if ($query) {
        echo 1;
    } else {
        echo 2;
    }
} else {
    echo 7;
}
