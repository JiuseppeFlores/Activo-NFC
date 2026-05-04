<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('../conexion.php');

    $idBien = $_POST['idBien'];

    $sql = "SELECT idAsignacion FROM tblAsignacion WHERE idProducto = $idBien;";
    $stmt = sqlsrv_query($con, $sql);
    $count = sqlsrv_has_rows($stmt);

    if ($count == true) {
        echo json_encode(array("success" => true), true);
    } else {
        echo json_encode(array("success" => false), true);
    }
} else {
    echo 'No tiene acceso a esta parte del sistema';
    exit();
}