<?php

include("../conexion.php");
$id = $_POST["idAsignacion"];

$idUsuario = $_POST["idUsuario"];
$idProducto = $_POST["idProducto"];
$fechaInicial = str_replace("T", " ", $_POST["fechaInicial"]);
$fechaFinal = str_replace("T", " ", $_POST["fechaFinal"]);
if ($fechaFinal == "") {
    $update = " UPDATE tblAsignacion set  idUsuario = '$idUsuario' , idProducto = '$idProducto' , fechaInicial = '$fechaInicial'  WHERE idAsignacion=$id; ";
} else {
    $update = " UPDATE tblAsignacion set  idUsuario = '$idUsuario' , idProducto = '$idProducto' , fechaInicial = '$fechaInicial' , fechaFinal = '$fechaFinal'  WHERE idAsignacion=$id; ";
}
// $update = " UPDATE tblAsignacion set  idUsuario = '$idUsuario' , idProducto = '$idProducto' , fechaInicial = '$fechaInicial' , fechaFinal = '$fechaFinal'  WHERE idAsignacion=$id; ";

$sql_rep = "SELECT * FROM tblAsignacion WHERE (idProducto='$idProducto') AND idAsignacion<> $id";
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
