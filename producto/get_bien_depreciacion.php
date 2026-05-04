<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    include("../conexion.php");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    header("Expires: 0");
    $listaBien = array();
    $idDepreciacion = $_POST['idDepreciacion'];
    $sql = "SELECT * FROM tblDepreciacionDetalle WHERE idDepreciacion = $idDepreciacion ORDER BY bienDetalle ASC;";
    $query = sqlsrv_query($con, $sql);
    if ($query) {
        while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            $listaBien[] = $row;
        }
    }
    echo json_encode($listaBien);
} else {
    echo "No tiene acceso a esta parte del sistema.";
}
