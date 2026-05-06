<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    include("../conexion.php");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    header("Expires: 0");
    $usuario = array();
    $idAsignacion = $_POST['idAsignacion'];
    $sql = "SELECT tu.nombre, tu.apellidoPaterno, tu.apellidoMaterno, tu.ci FROM tblAsignacion ta LEFT JOIN tblUsuario tu ON ta.idUsuario = tu.idUsuario WHERE ta.idAsignacion = $idAsignacion;";
    $query = sqlsrv_query($con, $sql);
    if ($query) {
        $row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
        $nombreCompleto = $row['nombre'] . ' ' . $row['apellidoPaterno'] . ' ' . $row['apellidoMaterno'];
        $usuario = $row;
        $usuario['nombreCompleto'] = $nombreCompleto;
    }
    echo json_encode($usuario);
} else {
    echo "No tiene acceso a esta parte del sistema.";
}
