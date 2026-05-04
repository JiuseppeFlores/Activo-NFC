<?php
include("../conexion.php");
$id = intval($_POST["id"]);
$sqlExistencia = "SELECT * FROM tblUsuario WHERE idRol = $id";
$queryExistencia = sqlsrv_query($con, $sqlExistencia);
if (sqlsrv_has_rows($queryExistencia)) {
        echo 3;
        return;
}
$sql = "DELETE FROM tblRol WHERE idRol=" . $id . ";";
$query_delete = sqlsrv_query($con, $sql);
if ($query_delete) {

        echo 1;
} else {
        echo 2;
}
