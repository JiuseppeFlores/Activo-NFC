<?php
include("../conexion.php");
date_default_timezone_set('America/La_Paz');
$fechaActual = date('Y-m-d H:i');
$anioActual = date("Y");
$listaProductos = array();
$sqlProductos = "SELECT DISTINCT tp.*, td.bien, td.coeficiente, td.vidaUtil FROM tblProducto tp LEFT JOIN tblAsignacion ta ON ta.idProducto = tp.idProducto LEFT JOIN tblDepreciacion td ON td.idDepreciacion = tp.idDepreciacion WHERE (ta.idAsignacion IS NULL OR ta.estado = 'DEVUELTO') ORDER BY tp.producto ASC;";
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

<form style="padding:10px" id="add_asignacion">
    <div class="row g-3 align-items-center">
        <div class="" style="margin:30px auto">
            <button type="button" class="btn btn-primary" id="btnGuardar">Guardar</button>
            <button type="button" onclick="listar_asignacion(1)" class="btn btn-danger">Volver</button>
        </div>
    </div>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Usuario</label>
        </div>
        <div class="col-9">
            <select class="form-control" name="idUsuario" id="selectUsuario" required>
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
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Bien</label>
        </div>
        <div class="col-6">
            <select class="form-control" name="idProducto" id="selectProducto" required onchange="vidaRestante()">
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
        </div>
        <div class="col-2">
            <input type="text" name="vidaUtilRestante" id="vidaUtilRestante" readonly class="form-control" value="Vida útil restante: ">
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalVerProducto" onclick="verProducto()">
                <i class="fas fa-eye"></i>
            </button>
            <button type="button" class="btn btn-success" onclick="agregarSeleccion()">
                <i class="fas fa-plus"></i> Agregar
            </button>
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Fecha Inicial</label>
        </div>
        <div class="col-9">
            <input type="datetime-local" name="fechaInicial" required autocomplete="off" class="form-control" value="<?php echo $fechaActual; ?>">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Fecha Final</label>
        </div>
        <div class="col-9">
            <input type="datetime-local" name="fechaFinal" autocomplete="off" class="form-control">
        </div>
    </div><br>
</form>

<div class="card" style="margin:10px">
    <div class="card-header card-header-primary">
        <h3 class="card-title">Bienes Seleccionados</h3>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-center" id="tablaSeleccionados">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Vida Útil Restante</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="cuerpoTablaSeleccionados">
                            <tr>
                                <td colspan="5" class="text-center">No hay bienes seleccionados</td>
                            </tr>
                            <!-- Aquí se agregarán las filas dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalVerProducto" tabindex="-1" role="dialog" aria-labelledby="modalVerProductoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalVerProductoLabel">Visualización del producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center" id="contenedorImagen">
                <!-- Contenido de la modal -->
                <img src="../images/empty.jpg" class="img-fluid rounded" alt="Producto">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const selectUsuario = $("#selectUsuario").select2({
            placeholder: "Seleccione un usuario",
            allowClear: true
        });

        const selectProducto = $("#selectProducto").select2({
            placeholder: "Seleccione un producto",
            allowClear: true
        });
        vidaRestante();
    });
</script>