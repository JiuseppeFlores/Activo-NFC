<?php
include("../conexion.php");
?>

<form style="padding:10px" id="add_usuario">
    <div class="row g-3 align-items-center">
        <div class="" style="margin:30px auto">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <button type="button" onclick="listar_usuario(1)" class="btn btn-danger">Volver</button>
        </div>
    </div>

    <div class="row g-3 align-items-center">
        <div class="col-12" style="  text-align: center;  margin: 10px;" id="prev1">
            <img src="../images/empty.jpg" style="width:200px;height:200px" alt="">
        </div>
    </div>

    <div class="row g-3 align-items-center">
        <div class="" style="margin:30px auto">
            <input type="file" id="file-previ1" onchange="previ('prev1','idbase1','file-previ1')" required class="form-control" autocomplete="off" aria-describedby="nombre">
            <input type="hidden" id="idbase1" name="idbase1" value="">
        </div>
    </div>
    <br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Nombre</label>
        </div>
        <div class="col-9">
            <input type="text" name="nombre" id="nombre" required autocomplete="off" class="form-control" placeholder="Escriba...">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Apellido Paterno</label>
        </div>
        <div class="col-9">
            <input type="text" name="apellidoPaterno" id="apellidoPaterno" required autocomplete="off" class="form-control" placeholder="Escriba...">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Apellido Materno</label>
        </div>
        <div class="col-9">
            <input type="text" name="apellidoMaterno" required autocomplete="off" class="form-control" placeholder="Escriba...">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Usuario</label>
        </div>
        <div class="col-7">
            <input type="text" name="usuario" id="usuario" required autocomplete="off" class="form-control" placeholder="Escriba..." onchange="verificarUsuario()">
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-info" onclick="generarUsuario()">
                <i class="fas fa-magic"></i> Generar Usuario
            </button>
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Contraseña</label>
        </div>
        <div class="col-9">
            <input type="text" name="password" required autocomplete="off" class="form-control" placeholder="Escriba...">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">CI</label>
        </div>
        <div class="col-9">
            <input type="text" name="ci" id="ci" required autocomplete="off" class="form-control" placeholder="Escriba..." onchange="verificarCi()">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Correo</label>
        </div>
        <div class="col-9">
            <input type="email" name="correo" id="correo" required autocomplete="off" class="form-control" placeholder="ejemplo@dominio.com">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Cargo</label>
        </div>
        <div class="col-9">
            <input type="text" name="cargo" id="cargo" required autocomplete="off" class="form-control" placeholder="Escriba...">
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
                    echo  " <option value='" . $value . "'>" . $texto . "</option> ";
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
                    echo  " <option value='" . $value . "'>" . $texto . "</option> ";
                }
                ?>
            </select>
        </div>
    </div><br>
</form>

<script src="../js/usuario.js"></script>