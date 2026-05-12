<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('../conexion.php');
    date_default_timezone_set('America/La_Paz');
    $idBien = $_POST['idBien'];
    $estado = $_POST['estado'] == '' ? 'ACTIVO' : $_POST['estado'];
    $observacion = $_POST['observacion'];
    $sqlObservacion = $observacion != '' ? ", observacion = '$observacion'" : '';
    $fechaModificacion = date('Y-m-d H:i:s');

    if ($estado == 'ACTIVO') {
        $estado = 'INACTIVO';
    } else {
        $estado = 'ACTIVO';
    }

    $sql = "UPDATE tblProducto SET estado = '$estado', fechaModificacion = '$fechaModificacion' $sqlObservacion WHERE idProducto = $idBien;";
    $stmt = sqlsrv_query($con, $sql);
    if ($stmt) {
        echo json_encode(array("success" => true));
    } else {
        echo json_encode(array("success" => false));
    }
} else {
    echo 'No tiene acceso a esta parte del sistema';
}