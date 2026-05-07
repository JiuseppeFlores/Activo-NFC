<?php

include("../conexion.php");
$usuario = $_POST["usuario"];
$password = $_POST["password"];
$nombre = $_POST["nombre"];
$apellidoPaterno = $_POST["apellidoPaterno"];
$apellidoMaterno = $_POST["apellidoMaterno"];
$ci = $_POST["ci"];
$correo = $_POST["correo"];
$idRol = $_POST["idRol"];
$idArea = $_POST["idArea"];
$idUsuario = $_SESSION['idUsuario'];
$cargo = $_POST["cargo"];

$id = guidv4();
$sql = "  INSERT INTO tblUsuario (usuario,password,nombre,apellidoPaterno,apellidoMaterno,ci,correo,idRol,idArea,idUsuarioCreador,cargo) VALUES ('$usuario','$password','$nombre','$apellidoPaterno','$apellidoMaterno','$ci','$correo','$idRol','$idArea','$idUsuario','$cargo');";
// echo $sql;
$query = sqlsrv_query($con, $sql);

if ($query) {
        $sql_max = "SELECT MAX(idUsuario) FROM tblUsuario";
        $query_max = sqlsrv_query($con, $sql_max);
        $row_max = sqlsrv_fetch_array($query_max);
        $id = $row_max[0];

        $carpeta = "../Images/usuario/";
        if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
        }

        $imagen = $_POST['idbase1'];
        $base_to_php = explode(',', $imagen);
        $data = base64_decode($base_to_php[1]);
        $filepath = "../Images/usuario/" . $id . ".png";
        file_put_contents($filepath, $data);

        echo 1;
} else {
        echo 2;
}
