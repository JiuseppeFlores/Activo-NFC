<?php

include("../conexion.php");
$producto = $_POST["descripcion"];
$codigoBarras = $_POST["codigoBarras"];
$idDepreciacion = $_POST['tipoProducto'];
$idDepreciacionDetalle = $_POST['bien'];
$costoAdquisicion = $_POST['costoAdquisicion'];
$fechaIngreso = $_POST['fechaIngreso'];
$marca = $_POST['marca'];
$tipoAdquisicion = $_POST['tipoAdquisicion'];
$idUsuario = $_SESSION['idUsuario'];
$estado = 'ACTIVO';
$id = guidv4();
$sql = "  INSERT INTO tblProducto (producto,codigoBarras,idUsuarioCreador,idDepreciacion,idDepreciacionDetalle,costoAdquisicion,fechaIngreso,marca,tipoAdquisicion,estado) VALUES ('$producto','$codigoBarras','$idUsuario',$idDepreciacion,$idDepreciacionDetalle,$costoAdquisicion,'$fechaIngreso','$marca','$tipoAdquisicion','$estado');";

$sql_rep = "SELECT * FROM tblProducto WHERE codigoBarras='$codigoBarras' ";
$query_rep = sqlsrv_query($con, $sql_rep);
$count_rep = sqlsrv_has_rows($query_rep);
if ($count_rep === false) {
        $query = sqlsrv_query($con, $sql);
        if ($query) {
                $sql_max = "SELECT MAX(idProducto) FROM tblProducto";
                $query_max = sqlsrv_query($con, $sql_max);
                $row_max = sqlsrv_fetch_array($query_max);
                $id = $row_max[0];

                $carpeta = "../Images/producto/";
                if (!file_exists($carpeta)) {
                        mkdir($carpeta, 0777, true);
                }
                $imagen = $_POST['idbase1'];
                $base_to_php = explode(',', $imagen);
                $data = base64_decode($base_to_php[1]);
                $filepath = "../Images/producto/" . $id . ".png";
                file_put_contents($filepath, $data);

                echo 1;
        } else {
                echo 2;
        }
} else {
        echo 7;
}
