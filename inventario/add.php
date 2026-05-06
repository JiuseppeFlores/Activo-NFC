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

<form style="padding:10px" id="add_inventario">
    <div class="row g-3 align-items-center">
        <div class="" style="margin:30px auto">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <button type="button" onclick="listar_inventario(1)" class="btn btn-danger">Volver</button>
        </div>
    </div>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Bien Asignado</label>
        </div>
        <div class="col-9">
            <select id="idAsignacion" name="idAsignacion" required autocomplete="off" class="form-control" onchange="getUsuario()">
                <option value="">Seleccione un bien asignado</option>
                <?php foreach($listaProductos as $id => $value){ ?>
                    <option value="<?php echo $id ?>" title="<?php echo $value ?>"><?php echo $value ?></option>
                <?php } ?>
            </select>
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Usuario Asignado</label>
        </div>
        <div class="col-9">
            <input type="text" id="usuario" name="usuario" required autocomplete="off" class="form-control" value="" readonly>
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Revisor</label>
        </div>
        <div class="col-9">
            <select id="revisor" name="revisor" required autocomplete="off" class="form-control">
                <option value="">Seleccione un revisor</option>
                <?php foreach($listaUsuarios as $id => $value){ ?>
                    <option value="<?php echo $id ?>"><?php echo $value ?></option>
                <?php } ?>
            </select>
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Fecha</label>
        </div>
        <div class="col-9">
            <input type="datetime-local" id="fecha" name="fecha" required autocomplete="off" class="form-control" value="<?php echo date('Y-m-d H:i') ?>">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Observación</label>
        </div>
        <div class="col-9">
            <input type="text" id="observacion" name="observacion" autocomplete="off" class="form-control" placeholder="Escriba...">
        </div>
    </div><br>
</form>

<script>
    // $(document).ready(function() {
    //     getBien();
    // });
</script>
