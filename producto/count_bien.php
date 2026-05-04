<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    include("../conexion.php");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    header("Expires: 0");
    $idDepreciacionDetalle = $_POST['idDepreciacionDetalle'];
    $sql = "SELECT COUNT(*) as count FROM tblProducto WHERE idDepreciacionDetalle = $idDepreciacionDetalle;";
    $query = sqlsrv_query($con, $sql);
    if ($query) {
        $row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
        echo $row['count'];
    }
} else {
    echo "No tiene acceso a esta parte del sistema.";
}