<?php
include("../conexion.php");
$id = $_POST['idDepreciacion'];
$estado = $_POST['estado'];
$update = "UPDATE tblDepreciacion SET estado = '$estado' WHERE idDepreciacion = $id;";
$query = sqlsrv_query($con, $update);
if ($query) {
    echo 1; // Actualización exitosa
} else {
    echo 2; // Error al actualizar
}