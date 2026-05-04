<?php

include("../conexion.php");
$id = $_POST["idUsuario"];

$usuario = $_POST["usuario"];
$password = $_POST["password"];
$nombre = $_POST["nombre"];
$apellidoPaterno = $_POST["apellidoPaterno"];
$apellidoMaterno = $_POST["apellidoMaterno"];
$ci = $_POST["ci"];
$correo = $_POST["correo"];
$idRol = $_POST["idRol"];
$idArea = $_POST["idArea"];
$update = " UPDATE tblUsuario set  usuario = '$usuario' , password = '$password' , nombre = '$nombre' , apellidoPaterno = '$apellidoPaterno' , apellidoMaterno = '$apellidoMaterno' , ci = '$ci' , correo = '$correo' , idRol = '$idRol' , idArea = '$idArea'  WHERE idUsuario=$id; ";

$query = sqlsrv_query($con, $update);
if ($query) {


    if (isset($_POST['idbase1']) && !empty($_POST['idbase1'])) {
        $imagen = $_POST['idbase1'];
        $base_to_php = explode(',', $imagen);
        $data = base64_decode($base_to_php[1]);
        $filepath = "../Images/usuario/" . $id . ".png";
        file_put_contents($filepath, $data);
    }


    echo 1;
} else {
    echo 2;
}
