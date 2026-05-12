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

<form style="padding:10px" id="edit_asignacion">
    <input type="hidden" name="idAsignacion" value="<?php echo $id; ?>">
    <div class="row g-3 align-items-center">
        <div class="" style="margin:30px auto">
            <button type="button" class="btn btn-success" id="btnActualizar">Actualizar</button>
            <button type="button" onclick="listar_asignacion(1)" class="btn btn-danger">Volver</button>
        </div>
    </div>
    <!-- para la adventencia cuando la fecha final es menor a la fecha actual -->
    <?php if ($estadoAsignacion == 'VENCIDO') { ?>
        <div class="alert bg-warning" role="alert">
            <strong>¡Advertencia!</strong> La asignación ya ha expirado. Actualice la fecha final o realice la devolución del bien.
        </div>
    <?php } ?>

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
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Producto</label>
        </div>
        <div class="col-6">
            <select class="form-control" name="idProducto" id="selectProducto" required onchange="vidaRestante()">
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
        </div>
        <div class="col-3">
            <input type="text" name="vidaUtilRestante" id="vidaUtilRestante" readonly class="form-control" value="Vida útil restante: ">
        </div>
        <div class="col-1">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalVerProducto" onclick="verProducto()">
                <i class="fas fa-eye"></i>
            </button>
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Fecha Inicial</label>
        </div>
        <div class="col-9">
            <input type="datetime-local" name="fechaInicial" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo formato_fechas_server($fechaInicial, 'Y-m-d H:i'); ?>">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Fecha Final</label>
        </div>
        <div class="col-9">
            <input type="datetime-local" name="fechaFinal" autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo formato_fechas_server($fechaFinal, 'Y-m-d H:i'); ?>">
        </div>
    </div><br>
</form>

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