 <?php
    include("../conexion.php");
    $id = $_POST["id"];
    $sql = "SELECT * FROM tblRol WHERE idRol='$id' ";
    $query = sqlsrv_query($con, $sql);
    $row = sqlsrv_fetch_array($query);

    $rol = $row["rol"];
    $t = time();

    ?>

 <form id="edit_rol" class="card">
     <input type="hidden" name="idRol" value="<?php echo $id; ?>">
     <div class="card-header">
         <h3 class="card-title">Editar Rol</h3>
     </div>
     <div class="card-body">
         <div class="row g-3 align-items-center mb-3">
             <div class="col-md-3 col-lg-2">
                 <label class="form-label mb-0">Rol</label>
             </div>
             <div class="col-md-9 col-lg-10">
                 <input type="text" name="rol" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $rol ?>">
             </div>
         </div>
     </div>
     <div class="card-footer text-end">
         <button type="button" onclick="listar_rol(1)" class="btn btn-secondary me-2">Volver</button>
         <button type="submit" class="btn btn-success">Actualizar</button>
     </div>
 </form>