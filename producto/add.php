<?php
include("../conexion.php");
$sql = "SELECT * FROM tblDepreciacion WHERE estado=1 ORDER BY bien ASC;";
$query = sqlsrv_query($con, $sql);
$listaDepreciacion = array();
$listaDepreciacionDetalle = array();
while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)){
    $listaDepreciacion[$row['idDepreciacion']] = $row;
}
$sqlDetalle = "SELECT * FROM tblDepreciacionDetalle";
$queryDetalle = sqlsrv_query($con, $sqlDetalle);
while($rowDetalle = sqlsrv_fetch_array($queryDetalle, SQLSRV_FETCH_ASSOC)) {
    $listaDepreciacionDetalle[$rowDetalle['idDepreciacion']][] = $rowDetalle;
}

$sqlUsuarios = "SELECT * FROM tblUsuario ORDER BY apellidoPaterno, apellidoMaterno, nombre ASC;";
$queryUsuarios = sqlsrv_query($con, $sqlUsuarios);
$listaUsuarios = array();
while($rowUsuarios = sqlsrv_fetch_array($queryUsuarios, SQLSRV_FETCH_ASSOC)){
    $listaUsuarios[$rowUsuarios['idUsuario']] = $rowUsuarios;
}

?>

<?php
include("../conexion.php");
$sql = "SELECT * FROM tblDepreciacion WHERE estado=1 ORDER BY bien ASC;";
$query = sqlsrv_query($con, $sql);
$listaDepreciacion = array();
$listaDepreciacionDetalle = array();
while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)){
    $listaDepreciacion[$row['idDepreciacion']] = $row;
}
$sqlDetalle = "SELECT * FROM tblDepreciacionDetalle";
$queryDetalle = sqlsrv_query($con, $sqlDetalle);
while($rowDetalle = sqlsrv_fetch_array($queryDetalle, SQLSRV_FETCH_ASSOC)) {
    $listaDepreciacionDetalle[$rowDetalle['idDepreciacion']][] = $rowDetalle;
}

$sqlUsuarios = "SELECT * FROM tblUsuario ORDER BY apellidoPaterno, apellidoMaterno, nombre ASC;";
$queryUsuarios = sqlsrv_query($con, $sqlUsuarios);
$listaUsuarios = array();
while($rowUsuarios = sqlsrv_fetch_array($queryUsuarios, SQLSRV_FETCH_ASSOC)){
    $listaUsuarios[$rowUsuarios['idUsuario']] = $rowUsuarios;
}

?>

<form id="add_producto" class="card">
    <div class="card-header">
        <h3 class="card-title">Añadir Activo</h3>
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
                <label class="form-label">Tipo de Activo</label>
                <select id="tipoProducto" name="tipoProducto" required autocomplete="off" class="form-select" onchange="getBien()">
                    <?php foreach($listaDepreciacion as $value){ ?>
                        <option value="<?php echo $value['idDepreciacion'] ?>"><?php echo $value['bien'] . ' (Vida útil: '.$value['vidaUtil'].' años)' ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Activo</label>
                <select id="bien" name="bien" required autocomplete="off" class="form-select" onchange="countBien()">
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Descripción</label>
                <input type="text" name="descripcion" required autocomplete="off" class="form-control" placeholder="Escriba...">
            </div>
            <div class="col-md-6">
                <label class="form-label">Código</label>
                <input type="text" id="codigoBarras" name="codigoBarras" required autocomplete="off" class="form-control" placeholder="Auto Generado" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">UID NFC</label>
                <input type="text" id="uidTag" name="uidTag" autocomplete="off" class="form-control" placeholder="Escriba el UID NFC...">
            </div>
            <div class="col-md-6">
                <label class="form-label">Marca</label>
                <input type="text" name="marca" required autocomplete="off" class="form-control" placeholder="Escriba...">
            </div>
            <div class="col-md-6">
                <label class="form-label">Tipo de Adquisición</label>
                <input type="text" name="tipoAdquisicion" required autocomplete="off" class="form-control" placeholder="Escriba...">
            </div>
            <div class="col-md-6">
                <label class="form-label">Costo de Adquisición</label>
                <input type="number" name="costoAdquisicion" required autocomplete="off" class="form-control" placeholder="Escriba..." min="1">
            </div>
            <div class="col-md-6">
                <label class="form-label">Estado del Activo</label>
                <select name="valoracion" class="form-select" required>
                    <option value="BUENO">Bueno</option>
                    <option value="REGULAR">Regular</option>
                    <option value="MALO">Malo</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha de Ingreso</label>
                <input type="date" name="fechaIngreso" required autocomplete="off" class="form-control" value="<?php echo date('Y-m-d') ?>" max="<?php echo date('Y-m-d') ?>">
            </div>
            <div class="col-md-12">
                <label class="form-label">Usuario Responsable</label>
                <select name="idUsuario" class="form-select" required>
                    <?php foreach($listaUsuarios as $usuario): ?>
                        <option value="<?php echo $usuario['idUsuario']; ?>"><?php echo $usuario['apellidoPaterno'] . ' ' . $usuario['apellidoMaterno'] . ' ' . $usuario['nombre'] . ' (CI: ' . $usuario['ci'] . ')'; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <button type="button" onclick="listar_producto(1)" class="btn btn-secondary me-2">Volver</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        getBien();
    });
</script>
