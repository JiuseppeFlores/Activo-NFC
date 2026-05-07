<?php

include("../conexion.php");
$producto = $_POST["descripcion"];
$codigoBarras = $_POST["codigoBarras"];
$uidTag = $_POST['uidTag'] ?? '';
$idDepreciacion = intval($_POST['tipoProducto']);
$idDepreciacionDetalle = intval($_POST['bien']);
$costoAdquisicion = floatval($_POST['costoAdquisicion']);
$fechaIngreso = $_POST['fechaIngreso'];
$marca = $_POST['marca'];
$tipoAdquisicion = $_POST['tipoAdquisicion'];
$valoracion = $_POST['valoracion'];
$idUsuario = $_SESSION['idUsuario'];
$estado = 'ACTIVO';
$id = guidv4();
$sql = "INSERT INTO tblProducto (producto,codigoBarras,uidTag,idUsuarioCreador,idDepreciacion,idDepreciacionDetalle,costoAdquisicion,fechaIngreso,marca,tipoAdquisicion,valoracion,estado) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);";
$params = array($producto, $codigoBarras, $uidTag, $idUsuario, $idDepreciacion, $idDepreciacionDetalle, $costoAdquisicion, $fechaIngreso, $marca, $tipoAdquisicion, $valoracion, $estado);

$sql_rep = "SELECT * FROM tblProducto WHERE codigoBarras=?";
$params_rep = array($codigoBarras);
$query_rep = sqlsrv_query($con, $sql_rep, $params_rep);
$count_rep = sqlsrv_has_rows($query_rep);
if ($count_rep === false) {
        $query = sqlsrv_query($con, $sql, $params);
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
