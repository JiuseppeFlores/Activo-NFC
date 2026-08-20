<?php
include("../conexion.php");
?>

<form id="add_usuario" class="card">
    <div class="card-header">
        <h3 class="card-title">Añadir Usuario</h3>
    </div>
    <div class="card-body">
        <div class="text-center mb-3">
            <div id="prev1" class="mb-2">
                <img src="../images/empty.jpg" class="avatar avatar-xl rounded" style="width:120px;height:120px;object-fit:cover;" alt="Vista previa">
            </div>
            <div class="w-50 mx-auto">
                <input type="file" id="file-previ1" onchange="previ('prev1','idbase1','file-previ1')" required class="form-control" autocomplete="off">
                <input type="hidden" id="idbase1" name="idbase1" value="">
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" required autocomplete="off" class="form-control" placeholder="Escriba...">
            </div>
            <div class="col-md-6">
                <label class="form-label">Apellido Paterno</label>
                <input type="text" name="apellidoPaterno" id="apellidoPaterno" required autocomplete="off" class="form-control" placeholder="Escriba...">
            </div>
            <div class="col-md-6">
                <label class="form-label">Apellido Materno</label>
                <input type="text" name="apellidoMaterno" required autocomplete="off" class="form-control" placeholder="Escriba...">
            </div>
            <div class="col-md-6">
                <label class="form-label">Usuario</label>
                <div class="input-group">
                    <input type="text" name="usuario" id="usuario" required autocomplete="off" class="form-control" placeholder="Escriba..." onchange="verificarUsuario()">
                    <button type="button" class="btn btn-outline-info" onclick="generarUsuario()">
                        <i class="ti ti-wand icon me-1"></i> Generar
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Contraseña</label>
                <input type="text" name="password" required autocomplete="off" class="form-control" placeholder="Escriba...">
            </div>
            <div class="col-md-6">
                <label class="form-label">CI</label>
                <input type="text" name="ci" id="ci" required autocomplete="off" class="form-control" placeholder="Escriba..." onchange="verificarCi()">
            </div>
            <div class="col-md-6">
                <label class="form-label">Correo</label>
                <input type="email" name="correo" id="correo" required autocomplete="off" class="form-control" placeholder="ejemplo@dominio.com">
            </div>
            <div class="col-md-6">
                <label class="form-label">Cargo</label>
                <input type="text" name="cargo" id="cargo" required autocomplete="off" class="form-control" placeholder="Escriba...">
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
                        echo  " <option value='" . $value . "'>" . $texto . "</option> ";
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
                        echo  " <option value='" . $value . "'>" . $texto . "</option> ";
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <button type="button" onclick="listar_usuario(1)" class="btn btn-secondary me-2">Volver</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>

<script src="../js/usuario.js"></script>