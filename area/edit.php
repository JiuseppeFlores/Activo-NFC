<?php
include("../conexion.php");
$id = $_POST["id"];
$sql = "SELECT * FROM tblArea WHERE idArea='$id' ";
$query = sqlsrv_query($con, $sql);
$row = sqlsrv_fetch_array($query);
$area = $row["area"];
$t = time();

?>

<form id="edit_area" class="card">
    <input type="hidden" name="idArea" value="<?php echo $id; ?>">
    <div class="card-header">
        <h3 class="card-title">Editar Área</h3>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-center mb-3">
            <div class="col-md-3 col-lg-2">
                <label class="form-label mb-0">Área</label>
            </div>
            <div class="col-md-9 col-lg-10">
                <input type="text" name="area" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $area ?>">
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <button type="button" onclick="listar_area(1)" class="btn btn-secondary me-2">Volver</button>
        <button type="submit" class="btn btn-success">Actualizar</button>
    </div>
</form>