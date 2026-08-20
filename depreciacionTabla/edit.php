<?php
include("../conexion.php");
$id = $_POST["id"];
$sql = "SELECT * FROM tblDepreciacion WHERE idDepreciacion='$id' ";
$query = sqlsrv_query($con, $sql);
$row = sqlsrv_fetch_array($query);
$bien = $row["bien"];
$vidaUtil = $row["vidaUtil"];
$coeficiente = $row["coeficiente"] * 100;
$estado = $row["estado"];
$t = time();

?>

<form id="edit_depreciacion" class="card">
    <input type="hidden" name="idDepreciacion" value="<?php echo $id; ?>">
    <div class="card-header">
        <h3 class="card-title">Editar Depreciación</h3>
    </div>
    <div class="card-body">
        <div class="hr-text">Parámetros de depreciación</div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Bien</label>
                <input type="text" name="bien" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $bien ?>" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Vida útil (años)</label>
                <input type="number" name="vidaUtil" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $vidaUtil ?>" min="1" max="100" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select" required>
                    <option value="1" <?php if ($estado == 1) echo 'selected'; ?>>Activo</option>
                    <option value="0" <?php if ($estado == 0) echo 'selected'; ?>>Inactivo</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <button type="button" onclick="listar_depreciacion(1)" class="btn btn-secondary me-2">Volver</button>
        <button type="submit" class="btn btn-success"><i class="ti ti-device-floppy me-1"></i>Actualizar</button>
    </div>
</form>