<?php
include("../conexion.php");
date_default_timezone_set('America/La_Paz');
$sql = "SELECT idUsuario, nombre, apellidoPaterno, apellidoMaterno, ci FROM tblUsuario ORDER BY nombre ASC;";
$query = sqlsrv_query($con, $sql);
$listaUsuarios = array();
while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)){
    $listaUsuarios[$row['idUsuario']] = $row['nombre'] . ' ' . $row['apellidoPaterno'] . ' ' . $row['apellidoMaterno'] . ' - CI: ' . $row['ci'];
}
$sqlProductos = "SELECT ta.*, tp.producto, tp.codigoBarras FROM tblAsignacion ta LEFT JOIN tblProducto tp ON ta.idProducto = tp.idProducto WHERE ta.estado = 'ASIGNADO' ORDER BY tp.producto ASC";
$queryProductos = sqlsrv_query($con, $sqlProductos);
$listaProductos = array();
while($row = sqlsrv_fetch_array($queryProductos, SQLSRV_FETCH_ASSOC)){
    $listaProductos[$row['idAsignacion']] = $row['producto'] . ' - Cód. Barras: ' . $row['codigoBarras'];
}
?>

<form id="add_inventario" class="card">
    <div class="card-header">
        <h3 class="card-title">Nueva Inspección</h3>
    </div>
    <div class="card-body">
        <div class="hr-text">Datos de la inspección</div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Bien Asignado</label>
                <select id="idAsignacion" name="idAsignacion" required autocomplete="off" class="form-select" onchange="getUsuario()">
                    <option value="">Seleccione un bien asignado</option>
                    <?php foreach($listaProductos as $id => $value){ ?>
                        <option value="<?php echo $id ?>" title="<?php echo $value ?>"><?php echo $value ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Usuario Asignado</label>
                <input type="text" id="usuario" name="usuario" required autocomplete="off" class="form-control" value="" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Revisor</label>
                <select id="revisor" name="revisor" required autocomplete="off" class="form-select">
                    <option value="">Seleccione un revisor</option>
                    <?php foreach($listaUsuarios as $id => $value){ ?>
                        <option value="<?php echo $id ?>"><?php echo $value ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha</label>
                <input type="datetime-local" id="fecha" name="fecha" required autocomplete="off" class="form-control" value="<?php echo date('Y-m-d H:i') ?>">
                <small class="form-hint">Registre la fecha y hora en que se realizó la inspección.</small>
            </div>
            <div class="col-md-12">
                <label class="form-label">Observación</label>
                <input type="text" id="observacion" name="observacion" autocomplete="off" class="form-control" placeholder="Escriba...">
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <button type="button" onclick="listar_inventario(1)" class="btn btn-secondary me-2">Volver</button>
        <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Guardar</button>
    </div>
</form>

<script>
    // $(document).ready(function() {
    //     getBien();
    // });
</script>
