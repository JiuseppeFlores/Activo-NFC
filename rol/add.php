<?php
include("../conexion.php");
?>

<form style="padding:10px" id="add_rol">
    <div class="row g-3 align-items-center">
        <div class="" style="margin:30px auto">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <button type="button" onclick="listar_rol(1)" class="btn btn-danger">Volver</button>
        </div>
    </div>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Rol</label>
        </div>
        <div class="col-9">
            <input type="text" name="rol" required autocomplete="off" class="form-control" placeholder="Escriba...">
        </div>
    </div><br>
</form>