<?php
include("../conexion.php");
$id = $_POST["id"];
$sql = "SELECT * FROM tblArea WHERE idArea='$id' ";
$query = sqlsrv_query($con, $sql);
$row = sqlsrv_fetch_array($query);
$area = $row["area"];
$t = time();

?>

<form style="padding:10px" id="edit_area">
    <input type="hidden" name="idArea" value="<?php echo $id; ?>">
    <div class="row g-3 align-items-center">
        <div class="" style="margin:30px auto">
            <button type="submit" class="btn btn-success">Actualizar</button>
            <button type="button" onclick="listar_area(1)" class="btn btn-danger">Volver</button>
        </div>
    </div>

    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Area</label>
        </div>
        <div class="col-9">
            <input type="text" name="area" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $area ?>">
        </div>
    </div><br>
</form>