<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    include("../conexion.php");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    header("Expires: 0");
    date_default_timezone_set('America/La_Paz');

    $idAsignacion = $_POST['idAsignacion'];
    $observacion = $_POST['observacion'] ?? '';
    $idUsuarioCreador = $_POST['idUsuarioCreador'];
    $fecha = date('Y-m-d H:i:s');

    $sql = "INSERT INTO tblInventario (idAsignacion, observacion, idUsuarioCreador, fecha) VALUES (?, ?, ?, ?)";
    $params = array($idAsignacion, $observacion, $idUsuarioCreador, $fecha);

    $query = sqlsrv_query($con, $sql, $params);

    if ($query) {
        echo json_encode(array('status' => 1, 'message' => 'Inventario creado exitosamente.'));
    } else {
        echo json_encode(array('status' => 0, 'message' => 'Error al crear el inventario: ' . print_r(sqlsrv_errors(), true)));
    }
} else {
    echo "No tiene acceso a esta parte del sistema.";
}