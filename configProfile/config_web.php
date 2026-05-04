<?php
include("../conexion.php");
$id = $_SESSION['id'];

$sql = "SELECT TOP 1 * FROM tblPersona WHERE idPersona=$id";
$query = sqlsrv_query($con, $sql);
$row = sqlsrv_fetch_array($query);
$nombre = $row['nombre'];
$usuario = $row['usuario'];
$ci = $row['ci'];
$password = $row['password'];
$t = time();
if (file_exists("../images/persona/" . $id . ".png")) {
    $url = "../images/persona/" . $id . ".png?r=" . $t;
} else {
    $url = "../images/empty.jpg";
}
?>
<form style="padding:10px" id="configSistem">
    <div class="row g-3 align-items-center">
        <div class="" style="margin:30px auto">
            <button type="submit" class="btn btn-primary btn-lg">GUARDAR</button>
        </div>
    </div>
    <div class="row g-3 align-items-center">
        <div class="col-12" style="  text-align: center;  margin: 10px;" id="prev1">
            <img src='<?php echo $url; ?>' style="border-radius:150px;width:300px;height:300px;box-shadow: rgb(0 0 0 / 25%) 0px 54px 55px, rgb(0 0 0 / 12%) 0px -12px 30px, rgb(0 0 0 / 12%) 0px 4px 6px, rgb(0 0 0 / 17%) 0px 12px 13px, rgb(0 0 0 / 9%) 0px -3px 5px;" alt="">
        </div>
    </div>
    <div class="row g-3 align-items-center">
        <div class="" style="margin:30px auto">
            <input type="file" id="file-previ1" onchange="previ('prev1','idbase1','file-previ1')" class="form-control" autocomplete="off" aria-describedby="nombre">
            <input type="hidden" id="idbase1" name="idbase1" value="">
        </div>
    </div>
    <div class="row g-3 align-items-center">
        <div class="col-2">
        </div>
        <div class="col-1">
            <label class="col-form-label">Nombre</label>
        </div>
        <div class="col-6">
            <input type="text" name="nombre" required autocomplete="off" class="form-control" placeholder="Escriba..." required value="<?php echo $nombre; ?>">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
        </div>
        <div class="col-1">
            <label class="col-form-label">C.i.</label>
        </div>
        <div class="col-6">
            <input type="text" name="ci" required autocomplete="off" class="form-control" placeholder="Escriba..." required value="<?php echo $ci; ?>">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
        </div>
        <div class="col-1">
            <label class="col-form-label">Usuario</label>
        </div>
        <div class="col-6">
            <input type="text" name="usuario" required autocomplete="off" class="form-control" placeholder="Escriba..." required value="<?php echo $usuario; ?>">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
        </div>
        <div class="col-1">
            <label class="col-form-label">password</label>
        </div>
        <div class="col-6">
            <input type="text" name="password" required autocomplete="off" class="form-control" placeholder="Escriba..." required value="<?php echo $password; ?>">
        </div>
    </div><br>
</form>