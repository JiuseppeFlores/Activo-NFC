<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include("../conexion.php");
    
    $idAsignacion = $_POST['idAsignacion'];
    $idUsuarioCreador = $_POST['revisor'];
    $fecha = str_replace('T', ' ', $_POST['fecha']);
    $observacion = $_POST['observacion'];
    
    $sql = "INSERT INTO tblInventario (idAsignacion, idUsuarioCreador, fecha, observacion) VALUES ('$idAsignacion', '$idUsuarioCreador', '$fecha', '$observacion')";
    $query = sqlsrv_query($con, $sql);
    
    if ($query) {
        echo 1;
    } else {
        echo 2;
    }
}