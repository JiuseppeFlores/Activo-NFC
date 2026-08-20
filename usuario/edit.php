<?php

include("../conexion.php");
$id = $_POST["id"];
$sql = "SELECT * FROM tblUsuario WHERE idUsuario='$id' ";
$query = sqlsrv_query($con, $sql);
$row = sqlsrv_fetch_array($query);

$usuario = $row["usuario"];
$password = $row["password"];
$nombre = $row["nombre"];
$apellidoPaterno = $row["apellidoPaterno"];
$apellidoMaterno = $row["apellidoMaterno"];
$ci = $row["ci"];
$correo = $row["correo"];
$idRol = $row["idRol"];
$idArea = $row["idArea"];
$cargo = $row["cargo"];
$t = time();

?>

<form id="edit_usuario" class="card">
    <input type="hidden" name="idUsuario" value="<?php echo $id; ?>">
    <div class="card-header">
        <h3 class="card-title">Editar Usuario</h3>
    </div>
    <div class="card-body">
        <?php
        $url = "../Images/usuario/" . $id . ".png";
        if (!file_exists($url)) {
            $url = "../Images/empty.jpg";
        } else {
            $url = "../Images/usuario/" . $id . ".png?r=" . $t;
        }
        ?>

        <div class="text-center mb-3">
            <div id="prev1" class="mb-2">
                <img src="<?php echo $url; ?>" class="avatar avatar-xl rounded" style="width:120px;height:120px;object-fit:cover;" alt="Foto de usuario">
            </div>
            <div class="w-50 mx-auto">
                <input type="file" id="file-previ1" onchange="previ('prev1','idbase1','file-previ1')" class="form-control" autocomplete="off">
                <input type="hidden" id="idbase1" name="idbase1" value="">
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Usuario</label>
                <input type="text" name="usuario" id="usuario" required autocomplete="off" class="form-control" placeholder="Escriba..." onchange="verificarUsuario(<?php echo $id; ?>)" value="<?php echo $usuario ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Contraseña</label>
                <input type="text" name="password" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $password ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $nombre ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Apellido Paterno</label>
                <input type="text" name="apellidoPaterno" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $apellidoPaterno ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Apellido Materno</label>
                <input type="text" name="apellidoMaterno" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $apellidoMaterno ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">CI</label>
                <input type="text" name="ci" id="ci" required autocomplete="off" class="form-control" placeholder="Escriba..." onchange="verificarCi(<?php echo $id; ?>)" value="<?php echo $ci ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Correo</label>
                <input type="email" name="correo" id="correo" required autocomplete="off" class="form-control" placeholder="ejemplo@dominio.com" value="<?php echo $correo ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Cargo</label>
                <input type="text" name="cargo" id="cargo" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $cargo ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Rol</label>
                <select class="form-select" name="idRol">
                    <?php
                    $sql = "SELECT * FROM tblRol";
                    $query = sqlsrv_query($con, $sql);
                    while ($row = sqlsrv_fetch_array($query)) {
                        $value = $row["idRol"];
                        $texto = $row["rol"];
                        if ($idRol == $value) {
                            echo ' <option value="' . $value . '" selected="selected">' . $texto . '</option> ';
                        } else {
                            echo ' <option value="' . $value . '" >' . $texto . '</option> ';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Área</label>
                <select class="form-select" name="idArea">
                    <?php
                    $sql = "SELECT * FROM tblArea";
                    $query = sqlsrv_query($con, $sql);
                    while ($row = sqlsrv_fetch_array($query)) {
                        $value = $row["idArea"];
                        $texto = $row["area"];
                        if ($idArea == $value) {
                            echo ' <option value="' . $value . '" selected="selected">' . $texto . '</option> ';
                        } else {
                            echo ' <option value="' . $value . '" >' . $texto . '</option> ';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <button type="button" onclick="listar_usuario(1)" class="btn btn-secondary me-2">Volver</button>
        <button type="submit" class="btn btn-success">Actualizar</button>
    </div>
</form>