<?php

include("../conexion.php");
$id = $_POST["idProducto"];

$producto = $_POST["descripcion"];
$codigoBarras = $_POST["codigoBarras"];
$idDepreciacion = $_POST['tipoProducto'];
$idDepreciacionDetalle = $_POST['bien'];
$costoAdquisicion = $_POST['costoAdquisicion'];
$fechaIngreso = $_POST['fechaIngreso'];
$marca = $_POST['marca'];
$tipoAdquisicion = $_POST['tipoAdquisicion'];
$estado = $_POST['estado'];

$update = " UPDATE tblProducto set  producto = '$producto' , codigoBarras = '$codigoBarras' , idDepreciacion = $idDepreciacion , idDepreciacionDetalle = $idDepreciacionDetalle , costoAdquisicion = $costoAdquisicion , fechaIngreso = '$fechaIngreso', marca = '$marca', tipoAdquisicion = '$tipoAdquisicion', estado = '$estado' WHERE idProducto=$id; ";

$sql_rep = "SELECT * FROM tblProducto WHERE (codigoBarras='$codigoBarras') AND idProducto<> $id";
$query_rep = sqlsrv_query($con, $sql_rep);
$count_rep = sqlsrv_has_rows($query_rep);
if ($count_rep === false) {
    $query = sqlsrv_query($con, $update);
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
        echo 2;
    }
} else {
    echo 7;
}
