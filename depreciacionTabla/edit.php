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

<form style="padding:10px" id="edit_depreciacion">
    <input type="hidden" name="idDepreciacion" value="<?php echo $id; ?>">
    <div class="row g-3 align-items-center">
        <div class="" style="margin:30px auto">
            <button type="submit" class="btn btn-success">Actualizar</button>
            <button type="button" onclick="listar_depreciacion(1)" class="btn btn-danger">Volver</button>
        </div>
    </div>

    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Bien</label>
        </div>
        <div class="col-9">
            <input type="text" name="bien" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $bien ?>" readonly>
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Vida útil (años)</label>
        </div>
        <div class="col-9">
            <input type="number" name="vidaUtil" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $vidaUtil ?>" min="1" max="100" readonly>
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Estado</label>
        </div>
        <div class="col-9">
            <select name="estado" class="form-select form-control" required>
                <option value="1" <?php if ($estado == 1) echo 'selected'; ?>>Activo</option>
                <option value="0" <?php if ($estado == 0) echo 'selected'; ?>>Inactivo</option>
            </select>
        </div>
    </div><br>
</form>