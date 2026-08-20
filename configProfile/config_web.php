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
<form id="configSistem" class="card">
    <div class="card-header">
        <h3 class="card-title">Configuración del Sistema</h3>
    </div>
    <div class="card-body">
        <div class="text-center mb-3">
            <div id="prev1" class="mb-2">
                <img src="<?php echo $url; ?>" class="avatar avatar-xl rounded-circle" style="width:140px;height:140px;object-fit:cover;" alt="Avatar de usuario">
            </div>
            <div class="w-50 mx-auto">
                <input type="file" id="file-previ1" onchange="previ('prev1','idbase1','file-previ1')" class="form-control" autocomplete="off">
                <input type="hidden" id="idbase1" name="idbase1" value="">
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $nombre; ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">C.I.</label>
                <input type="text" name="ci" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $ci; ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Usuario</label>
                <input type="text" name="usuario" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $usuario; ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Contraseña</label>
                <input type="text" name="password" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $password; ?>">
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>