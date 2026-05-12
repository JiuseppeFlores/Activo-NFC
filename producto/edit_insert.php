<?php

include("../conexion.php");
$id = intval($_POST["idProducto"]);

$producto = $_POST["descripcion"];
$codigoBarras = $_POST["codigoBarras"];
$uidTag = $_POST['uidTag'] ?? '';
$idDepreciacion = intval($_POST['tipoProducto']);
$idDepreciacionDetalle = intval($_POST['bien']);
$costoAdquisicion = floatval($_POST['costoAdquisicion']);
$fechaIngreso = $_POST['fechaIngreso'];
$marca = $_POST['marca'];
$tipoAdquisicion = $_POST['tipoAdquisicion'];
$estado = $_POST['estado'];
$valoracion = $_POST['valoracion'];
$idUsuarioResponsable = intval($_POST['idUsuario']);

$update = "UPDATE tblProducto SET producto = ?, codigoBarras = ?, uidTag = ?, idDepreciacion = ?, idDepreciacionDetalle = ?, costoAdquisicion = ?, fechaIngreso = ?, marca = ?, tipoAdquisicion = ?, estado = ?, valoracion = ?, idUsuarioResponsable = ? WHERE idProducto = ?";
$params_update = array($producto, $codigoBarras, $uidTag, $idDepreciacion, $idDepreciacionDetalle, $costoAdquisicion, $fechaIngreso, $marca, $tipoAdquisicion, $estado, $valoracion, $idUsuarioResponsable, $id);

$sql_rep = "SELECT * FROM tblProducto WHERE codigoBarras = ? AND idProducto <> ?";
$params_rep = array($codigoBarras, $id);
$query_rep = sqlsrv_query($con, $sql_rep, $params_rep);
$count_rep = sqlsrv_has_rows($query_rep);
if ($count_rep === false) {
    $query = sqlsrv_query($con, $update, $params_update);
    if ($query) {
        if (isset($_POST['idbase1']) && !empty($_POST['idbase1'])) {
            $imagen = $_POST['idbase1'];
            $base_to_php = explode(',', $imagen);
            $data = base64_decode($base_to_php[1]);
            $filepath = "../Images/producto/" . $id . ".png";
            file_put_contents($filepath, $data);
        }
        echo 1;
    } else {
        print_r(sqlsrv_errors());
        echo 2;
    }
} else {
    echo 7;
}
