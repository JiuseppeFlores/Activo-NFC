<?php
include("../conexion.php");
date_default_timezone_set('America/La_Paz');
$fechaActual = date('Y-m-d H:i');
$anioActual = date("Y");
$listaProductos = array();
$sqlProductos = "SELECT DISTINCT tp.*, td.bien, td.coeficiente, td.vidaUtil FROM tblProducto tp LEFT JOIN tblAsignacion ta ON ta.idProducto = tp.idProducto LEFT JOIN tblDepreciacion td ON td.idDepreciacion = tp.idDepreciacion WHERE (ta.idAsignacion IS NULL OR ta.estado = 'DEVUELTO') AND tp.estado = 'ACTIVO' ORDER BY tp.producto ASC;";
$queryProductos = sqlsrv_query($con, $sqlProductos);
while ($row = sqlsrv_fetch_array($queryProductos)) {
    $value = $row["idProducto"];
    $texto = $row["producto"];
    $codigoBarras = $row["codigoBarras"];
    $bien = $row["bien"];
    $coeficiente = $row["coeficiente"];
    $anioIngreso = $row["fechaIngreso"];
    $anioIngreso = $anioIngreso->format("Y");
    $diferenciaAnios = $anioActual - $anioIngreso;
    $vidaUtil = $row['vidaUtil'];
    $restanteVida = $vidaUtil - $diferenciaAnios;
    if ($restanteVida < 0) {
        $restanteVida = 0;
    }
    $listaProductos[] = array("idProducto" => $value, "producto" => $texto, "codigoBarras" => $codigoBarras, "bien" => $bien, "coeficiente" => $coeficiente, "vidaUtil" => $vidaUtil, "restanteVida" => $restanteVida);
}
?>

<form id="add_asignacion" class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">Añadir Asignación</h3>
    </div>
    <div class="card-body">
        <div class="hr-text">Datos de la asignación</div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Usuario</label>
                <select class="form-select" name="idUsuario" id="selectUsuario" required>
                    <?php
                    $sql = "SELECT * FROM tblUsuario ORDER BY apellidoPaterno ASC";
                    $query = sqlsrv_query($con, $sql);
                    while ($row = sqlsrv_fetch_array($query)) {
                        $value = $row["idUsuario"];
                        $texto = $row["apellidoPaterno"] . ' ' . $row["apellidoMaterno"] . ' ' . $row["nombre"];
                        echo  " <option value='" . $value . "'>" . $texto . "</option> ";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Bien</label>
                <div class="input-group">
                    <select class="form-select" name="idProducto" id="selectProducto" required onchange="vidaRestante()">
                        <?php
                        if (count($listaProductos) == 0) {
                            echo  " <option value='-1'>No hay productos disponibles</option> ";
                        } else {
                            foreach ($listaProductos as $producto) {
                                echo  " <option value='" . $producto["idProducto"] . "' data-vida='" . $producto["restanteVida"] . "'>" . $producto["producto"] . " (Código: " . $producto["codigoBarras"] . ")</option> ";
                            }
                        }
                        ?>
                    </select>
                    <button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#modalVerProducto" data-bs-toggle="modal" data-bs-target="#modalVerProducto" onclick="verProducto()">
                        <i class="ti ti-eye icon"></i>
                    </button>
                    <button type="button" class="btn btn-outline-success" onclick="agregarSeleccion()">
                        <i class="ti ti-plus icon"></i> Agregar
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Vida Útil Restante</label>
                <input type="text" name="vidaUtilRestante" id="vidaUtilRestante" readonly class="form-control" value="Vida útil restante: ">
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha Inicial</label>
                <input type="datetime-local" name="fechaInicial" required autocomplete="off" class="form-control" value="<?php echo $fechaActual; ?>">
                <small class="form-hint">Indique cuándo comienza la asignación.</small>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha Final</label>
                <input type="datetime-local" name="fechaFinal" autocomplete="off" class="form-control">
                <small class="form-hint">Opcional. Defina la fecha límite de devolución.</small>
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <button type="button" onclick="listar_asignacion(1)" class="btn btn-secondary me-2">Volver</button>
        <button type="button" class="btn btn-primary" id="btnGuardar"><i class="ti ti-device-floppy me-1"></i>Guardar</button>
    </div>
</form>

<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">Bienes Seleccionados</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive" id="tablaSeleccionados">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th class="w-1">#</th>
                        <th>Nombre</th>
                        <th>Vida Útil Restante</th>
                        <th class="w-1">Acciones</th>
                    </tr>
                </thead>
                <tbody id="cuerpoTablaSeleccionados">
                    <tr>
                        <td colspan="4" class="text-center text-secondary py-3">No hay bienes seleccionados</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal modal-blur fade" id="modalVerProducto" tabindex="-1" role="dialog" aria-labelledby="modalVerProductoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalVerProductoLabel">Visualización del producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center" id="contenedorImagen">
                <img src="../images/empty.jpg" class="img-fluid rounded" alt="Producto">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const selectUsuario = $("#selectUsuario").select2({
            placeholder: "Seleccione un usuario",
            allowClear: true,
            theme: "bootstrap-5"
        });

        const selectProducto = $("#selectProducto").select2({
            placeholder: "Seleccione un producto",
            allowClear: true,
            theme: "bootstrap-5"
        });
        vidaRestante();
    });
</script>