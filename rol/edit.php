 <?php
    include("../conexion.php");
    $id = $_POST["id"];
    $sql = "SELECT * FROM tblRol WHERE idRol='$id' ";
    $query = sqlsrv_query($con, $sql);
    $row = sqlsrv_fetch_array($query);

    $rol = $row["rol"];
    $t = time();

    ?>

 <form style="padding:10px" id="edit_rol">
     <input type="hidden" name="idRol" value="<?php echo $id; ?>">
     <div class="row g-3 align-items-center">
         <div class="" style="margin:30px auto">
             <button type="submit" class="btn btn-success">Actualizar</button>
             <button type="button" onclick="listar_rol(1)" class="btn btn-danger">Volver</button>
         </div>
     </div>

     <div class="row g-3 align-items-center">
         <div class="col-2">
             <label class="col-form-label">Rol</label>
         </div>
         <div class="col-9">
             <input type="text" name="rol" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $rol ?>">
         </div>
     </div><br>
 </form>