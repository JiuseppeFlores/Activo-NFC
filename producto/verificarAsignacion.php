<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('../conexion.php');

    $idBien = $_POST['idBien'];

    $sql = "SELECT ta.idAsignacion FROM tblAsignacion ta WHERE ta.idProducto = $idBien AND ta.estado = 'ASIGNADO';";
    $stmt = sqlsrv_query($con, $sql);
    $count = sqlsrv_has_rows($stmt);
    $sqlProducto = "SELECT tp.idProducto, tp.observacion, tp.estado FROM tblProducto tp WHERE tp.idProducto = $idBien";
    $stmtProducto = sqlsrv_query($con, $sqlProducto);
    if ($count == true) {
        echo json_encode(array("success" => true), true);
    } else {
        $row = sqlsrv_fetch_array($stmtProducto, SQLSRV_FETCH_ASSOC);
        echo json_encode(array("success" => false, "idProducto" => $row['idProducto'], "observacion" => $row['observacion'], "estado" => $row['estado']), true);
    }
} else {
    echo 'No tiene acceso a esta parte del sistema';
    exit();
}