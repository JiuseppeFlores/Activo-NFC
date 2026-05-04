<?php

include("../conexion.php");
$idUsuario = $_POST["idUsuario"];
// $idProducto = $_POST["idProducto"];
$fechaInicial = str_replace("T", " ", $_POST["fechaInicial"]);
$fechaFinal = str_replace("T", " ", $_POST["fechaFinal"]);
$selecciones = json_decode($_POST["selecciones"]);
$idUsuarioCreador = $_SESSION['idUsuario'];
$id = guidv4();
try {
    foreach ($selecciones as $seleccion) {
        $idProducto = $seleccion->idProducto;
        if ($fechaFinal == "") {
            $sql = "  INSERT INTO tblAsignacion (idUsuario,idProducto,fechaInicial,idUsuarioCreador,estado) VALUES ('$idUsuario','$idProducto','$fechaInicial','$idUsuarioCreador','ASIGNADO');";
        } else {
            $sql = "  INSERT INTO tblAsignacion (idUsuario,idProducto,fechaInicial,fechaFinal,idUsuarioCreador,estado) VALUES ('$idUsuario','$idProducto','$fechaInicial','$fechaFinal','$idUsuarioCreador','ASIGNADO');";
        }
    
        $sql_rep = "SELECT * FROM tblAsignacion WHERE idProducto='$idProducto' AND estado='ASIGNADO';";
        $query_rep = sqlsrv_query($con, $sql_rep);
        $count_rep = sqlsrv_has_rows($query_rep);
        if ($count_rep === false) {
            $query = sqlsrv_query($con, $sql);
            if ($query) {
                // sqlsrv_commit($con);
                // echo 1;
            } else {
                throw new Exception("Error Processing Request", 2);
                // echo 2;
            }
        } else {
            throw new Exception("Error Processing Request", 7);
            // echo 7;
        }
    }
    sqlsrv_commit($con);
    echo 1;
} catch (Exception $e) {
    sqlsrv_rollback($con);
    echo $e->getCode();
}
