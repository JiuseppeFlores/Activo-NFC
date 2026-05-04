<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include("../conexion.php");
    $selecciones = json_decode($_POST['selecciones']);
    try {
        foreach ($selecciones as $seleccion) {
            $idAsignacion = $seleccion->idAsignacion;
            $sql = "UPDATE tblAsignacion SET fechaFinal = GETDATE(), estado = 'DEVUELTO' WHERE idAsignacion = '$idAsignacion';";
            $query = sqlsrv_query($con, $sql);
            if ($query) {
                // echo 1;
            } else {
                throw new Exception("Error Processing Request", 2);
            }
        }
        sqlsrv_commit($con);
        echo 1;
    } catch (Exception $e) {
        sqlsrv_rollback($con);
        echo $e->getCode();
    }
    
} else {
    echo "No tienes permiso para acceder a esta página.";
}