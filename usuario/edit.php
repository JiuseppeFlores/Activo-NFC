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

<form style="padding:10px" id="edit_usuario">
    <input type="hidden" name="idUsuario" value="<?php echo $id; ?>">
    <div class="row g-3 align-items-center">
        <div class="" style="margin:30px auto">
            <button type="submit" class="btn btn-success">Actualizar</button>
            <button type="button" onclick="listar_usuario(1)" class="btn btn-danger">Volver</button>
        </div>
    </div>


    <?php
    $url = "../Images/usuario/" . $id . ".png";
    if (!file_exists($url)) {
        $url = "../Images/empty.jpg";
    } else {
        $url = "../Images/usuario/" . $id . ".png?r=" . $t;
    }
    ?>

    <div class="row g-3 align-items-center">
        <div class="col-12" style="  text-align: center;  margin: 10px;" id="prev1">
            <img src="<?php echo $url; ?>" style="width:200px;height:200px;border-radius:10px" alt="">
        </div>
    </div>

    <div class="row g-3 align-items-center">
        <div class="" style="margin:30px auto">
            <input type="file" id="file-previ1" onchange="previ('prev1','idbase1','file-previ1')" class="form-control" autocomplete="off" aria-describedby="nombre">
            <input type="hidden" id="idbase1" name="idbase1" value="">
        </div>
    </div>
    <br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Usuario</label>
        </div>
        <div class="col-9">
            <input type="text" name="usuario" id="usuario" required autocomplete="off" class="form-control" placeholder="Escriba..." onchange="verificarUsuario(<?php echo $id; ?>)" value="<?php echo $usuario ?>">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Contraseña</label>
        </div>
        <div class="col-9">
            <input type="text" name="password" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $password ?>">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Nombre</label>
        </div>
        <div class="col-9">
            <input type="text" name="nombre" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $nombre ?>">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Apellido Paterno</label>
        </div>
        <div class="col-9">
            <input type="text" name="apellidoPaterno" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $apellidoPaterno ?>">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Apellido Materno</label>
        </div>
        <div class="col-9">
            <input type="text" name="apellidoMaterno" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $apellidoMaterno ?>">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">CI</label>
        </div>
        <div class="col-9">
            <input type="text" name="ci" id="ci" required autocomplete="off" class="form-control" placeholder="Escriba..." onchange="verificarCi(<?php echo $id; ?>)" value="<?php echo $ci ?>">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Correo</label>
        </div>
        <div class="col-9">
            <input type="email" name="correo" id="correo" required autocomplete="off" class="form-control" placeholder="ejemplo@dominio.com" value="<?php echo $correo ?>">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Cargo</label>
        </div>
        <div class="col-9">
            <input type="text" name="cargo" id="cargo" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $cargo ?>">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Rol</label>
        </div>
        <div class="col-9">
            <select class="form-control" name="idRol">
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
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Area</label>
        </div>
        <div class="col-9">
            <select class="form-control" name="idArea">
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
    </div><br>
</form>