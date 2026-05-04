<?php
include("../conexion.php");
$id = $_POST["idArea"];
$area = $_POST["area"];
$update = " UPDATE tblArea set  area = '$area'  WHERE idArea=$id; ";
$sql_rep = "SELECT * FROM tblArea WHERE (area='$area') AND idArea<> $id";
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
