<?php

include("../conexion.php");
date_default_timezone_set('America/La_Paz');
$fechaActual = date('Y-m-d H:i');
$anioActual = date("Y");
$id = $_POST["id"];
$estadoAsignacion = isset($_POST["estadoAsignacion"]) ? $_POST["estadoAsignacion"] : 'VIGENTE';
$sql = "SELECT * FROM tblAsignacion WHERE idAsignacion='$id' ";
$query = sqlsrv_query($con, $sql);
$row = sqlsrv_fetch_array($query);

$idUsuario = $row["idUsuario"];
$idProducto = $row["idProducto"];
$fechaInicial = $row["fechaInicial"];
$fechaFinal = $row["fechaFinal"];
$t = time();
$listaProductos = array();
$sqlProductos = "SELECT tp.*, td.bien, td.coeficiente, td.vidaUtil FROM tblProducto tp LEFT JOIN tblAsignacion ta ON ta.idProducto = tp.idProducto LEFT JOIN tblDepreciacion td ON td.idDepreciacion = tp.idDepreciacion WHERE ta.idAsignacion IS NULL OR ta.idProducto = '$idProducto' AND tp.estado = 'ACTIVO' ORDER BY tp.producto ASC;";
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

<form id="edit_asignacion" class="card mb-3">
    <input type="hidden" name="idAsignacion" value="<?php echo $id; ?>">
    <div class="card-header">
        <h3 class="card-title">Editar Asignación</h3>
    </div>
    <div class="card-body">
        <?php if ($estadoAsignacion == 'VENCIDO') { ?>
            <div class="alert alert-warning mb-3" role="alert">
                <div class="d-flex">
                    <div>
                        <i class="ti ti-alert-triangle icon alert-icon"></i>
                    </div>
                    <div>
                        <h4 class="alert-title">¡Advertencia!</h4>
                        <div class="text-secondary">La asignación ya ha expirado. Actualice la fecha final o realice la devolución del bien.</div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Usuario</label>
                <select class="form-select" name="idUsuario" id="selectUsuario" required>
                    <?php
                    $sql = "SELECT * FROM tblUsuario ORDER BY apellidoPaterno ASC";
                    $query = sqlsrv_query($con, $sql);
                    while ($row = sqlsrv_fetch_array($query)) {
                        $value = $row["idUsuario"];
                        $texto = $row["apellidoPaterno"] . " " . $row["apellidoMaterno"] . ' ' . $row["nombre"];
                        if ($idUsuario == $value) {
                            echo ' <option value="' . $value . '" selected="selected">' . $texto . '</option> ';
                        } else {
                            echo ' <option value="' . $value . '" >' . $texto . '</option> ';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Producto</label>
                <div class="input-group">
                    <select class="form-select" name="idProducto" id="selectProducto" required onchange="vidaRestante()">
                        <?php
                        if (count($listaProductos) == 0) {
                            echo  " <option value='-1'>No hay productos disponibles</option> ";
                        } else {
                            foreach ($listaProductos as $producto) {
                                $value = $producto["idProducto"];
                                echo "<option value=''>Seleccione un producto</option>";
                                if ($idProducto == $value) {
                                    echo  " <option value='" . $producto["idProducto"] . "' selected='selected' data-vida='" . $producto["restanteVida"] . "'>" . $producto["producto"] . " (Cód. Barras: " . $producto["codigoBarras"] . ")</option> ";
                                } else {
                                    echo  " <option value='" . $producto["idProducto"] . "' data-vida='" . $producto["restanteVida"] . "'>" . $producto["producto"] . " (Cód. Barras: " . $producto["codigoBarras"] . ")</option> ";
                                }
                            }
                        }
                        ?>
                    </select>
                    <button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#modalVerProducto" data-bs-toggle="modal" data-bs-target="#modalVerProducto" onclick="verProducto()">
                        <i class="ti ti-eye icon"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Vida Útil Restante</label>
                <input type="text" name="vidaUtilRestante" id="vidaUtilRestante" readonly class="form-control" value="Vida útil restante: ">
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha Inicial</label>
                <input type="datetime-local" name="fechaInicial" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo formato_fechas_server($fechaInicial, 'Y-m-d H:i'); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha Final</label>
                <input type="datetime-local" name="fechaFinal" autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo formato_fechas_server($fechaFinal, 'Y-m-d H:i'); ?>">
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <button type="button" onclick="listar_asignacion(1)" class="btn btn-secondary me-2">Volver</button>
        <button type="button" class="btn btn-success" id="btnActualizar">Actualizar</button>
    </div>
</form>

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
            allowClear: true
        });

        const selectProducto = $("#selectProducto").select2({
            placeholder: "Seleccione un producto",
            allowClear: true
        });
        vidaRestante();
    });
</script>