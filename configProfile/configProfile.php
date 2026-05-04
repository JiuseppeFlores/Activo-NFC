<?php

include("../conexion.php");
$id = $_SESSION["id"];
$nombre = $_POST["nombre"];
$usuario = $_POST["usuario"];
$password = $_POST["password"];
 $ci = $_POST["ci"]; 

$update = " UPDATE tblPersona set  nombre = '$nombre' , usuario = '$usuario' , password = '$password' , ci = '$ci'  WHERE idPersona=$id ";
$query = sqlsrv_query($con, $update);
if ($query) {
    if(isset($_POST['idbase1']) && !empty($_POST['idbase1'])){
        $imagen = $_POST['idbase1'];
        $base_to_php = explode(',', $imagen);
        $data = base64_decode($base_to_php[1]);
        $filepath = "../images/persona/".$id.".png";
        file_put_contents($filepath, $data);
    }
    echo 1;
} else {
    echo 2;
}

?>