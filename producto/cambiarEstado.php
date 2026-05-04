<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('../conexion.php');
    $idBien = $_POST['idBien'];
    $estado = $_POST['estado'] == '' ? 'ACTIVO' : $_POST['estado'];

    if ($estado == 'ACTIVO') {
        $estado = 'INACTIVO';
    } else {
        $estado = 'ACTIVO';
    }

    $sql = "UPDATE tblProducto SET estado = '$estado' WHERE idProducto = $idBien;";
    $stmt = sqlsrv_query($con, $sql);
    if ($stmt) {
        echo json_encode(array("success" => true));
    } else {
        echo json_encode(array("success" => false));
    }
} else {
    echo 'No tiene acceso a esta parte del sistema';
}