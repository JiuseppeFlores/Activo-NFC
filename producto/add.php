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

?>

<form style="padding:10px" id="add_producto">
    <div class="row g-3 align-items-center">
        <div class="" style="margin:30px auto">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <button type="button" onclick="listar_producto(1)" class="btn btn-danger">Volver</button>
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
            <label class="col-form-label">Tipo de Activo</label>
        </div>
        <div class="col-9">
            <select id="tipoProducto" name="tipoProducto" required autocomplete="off" class="form-control" onchange="getBien()">
                <?php foreach($listaDepreciacion as $value){ ?>
                    <option value="<?php echo $value['idDepreciacion'] ?>"><?php echo $value['bien'] . ' (Vida útil: '.$value['vidaUtil'].' años)' ?></option>
                <?php } ?>
            </select>
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Activo</label>
        </div>
        <div class="col-9">
            <select id="bien" name="bien" required autocomplete="off" class="form-control" onchange="countBien()">
            </select>
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Descripción</label>
        </div>
        <div class="col-9">
            <input type="text" name="descripcion" required autocomplete="off" class="form-control" placeholder="Escriba...">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Código</label>
        </div>
        <div class="col-9">
            <input type="text" id="codigoBarras" name="codigoBarras" required autocomplete="off" class="form-control" placeholder="Auto Generado" readonly>
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">UID NFC</label>
        </div>
        <div class="col-9">
            <input type="text" id="uidTag" name="uidTag" autocomplete="off" class="form-control" placeholder="Escriba el UID NFC...">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Marca</label>
        </div>
        <div class="col-9">
            <input type="text" name="marca" required autocomplete="off" class="form-control" placeholder="Escriba...">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Tipo de Adquisición</label>
        </div>
        <div class="col-9">
            <input type="text" name="tipoAdquisicion" required autocomplete="off" class="form-control" placeholder="Escriba...">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Costo de Adquisición</label>
        </div>
        <div class="col-9">
            <input type="number" name="costoAdquisicion" required autocomplete="off" class="form-control" placeholder="Escriba..." min="1">
        </div>        
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Valoración</label>
        </div>
        <div class="col-9">
            <select name="valoracion" class="form-control" required>
                <option value="BUENO">Bueno</option>
                <option value="REGULAR">Regular</option>
                <option value="MALO">Malo</option>
            </select>
        </div>        
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Fecha de Ingreso</label>
        </div>
        <div class="col-9">
            <input type="date" name="fechaIngreso" required autocomplete="off" class="form-control" value="<?php echo date('Y-m-d') ?>" max="<?php echo date('Y-m-d') ?>">
        </div>
    </div><br>
</form>

<script>
    $(document).ready(function() {
        getBien();
    });
</script>
