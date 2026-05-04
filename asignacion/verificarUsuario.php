<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    include("../conexion.php");
    $devoluciones = $_POST['devoluciones'];
    $row = array('cantidad' => 0);
    if (is_array($devoluciones)) {
        $idAsignacion = array_column($devoluciones, 'idAsignacion');
        $idAsignacionString = implode(',', $idAsignacion);
        $sql = "SELECT COUNT(DISTINCT idUsuario) AS cantidad FROM tblAsignacion WHERE idAsignacion IN ($idAsignacionString)";
        $query = sqlsrv_query($con, $sql);
        $row = sqlsrv_fetch_array($query);
    }
    echo json_encode($row);
} else {
    echo "No tiene acceso a esta parte del sistema.";
}