<?php

include("../conexion.php");
$id = $_POST["idRol"];

$rol = $_POST["rol"];
$update = " UPDATE tblRol set  rol = '$rol'  WHERE idRol=$id; ";

$sql_rep = "SELECT * FROM tblRol WHERE (rol='$rol') AND idRol<> $id";
$query_rep = sqlsrv_query($con, $sql_rep);
$count_rep = sqlsrv_has_rows($query_rep);
if ($count_rep === false) {

    $query = sqlsrv_query($con, $update);
    if ($query) {
        echo 1;
    } else {
        echo 2;
    }
} else {
    echo 7;
}
