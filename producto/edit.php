<?php

include("../conexion.php");
$id = $_POST["id"];
$sql = "SELECT * FROM tblProducto WHERE idProducto='$id' ";
$query = sqlsrv_query($con, $sql);
$row = sqlsrv_fetch_array($query);

$sql = "SELECT * FROM tblDepreciacion WHERE estado=1 ORDER BY bien ASC;";
$query = sqlsrv_query($con, $sql);
$listaDepreciacion = array();
while($rowDepreciacion = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)){
    $listaDepreciacion[] = $rowDepreciacion;
}
$sqlDetalle = "SELECT * FROM tblDepreciacionDetalle WHERE idDepreciacion = ".$row['idDepreciacion'] . " ORDER BY bienDetalle ASC;";
$queryDetalle = sqlsrv_query($con, $sqlDetalle);
$listaDepreciacionDetalle = array();
while($rowDetalle = sqlsrv_fetch_array($queryDetalle, SQLSRV_FETCH_ASSOC)) {
    $listaDepreciacionDetalle[] = $rowDetalle;
}

$sqlUsuarios = "SELECT * FROM tblUsuario ORDER BY apellidoPaterno, apellidoMaterno, nombre ASC;";
$queryUsuarios = sqlsrv_query($con, $sqlUsuarios);
$listaUsuarios = array();
while($rowUsuarios = sqlsrv_fetch_array($queryUsuarios, SQLSRV_FETCH_ASSOC)){
    $listaUsuarios[$rowUsuarios['idUsuario']] = $rowUsuarios;
}

$producto = $row["producto"];
$codigoBarras = $row["codigoBarras"];
// echo $row['idUsuarioResponsable']; die();
$t = time();

?>

<form style="padding:10px" id="edit_producto">
    <input type="hidden" name="idProducto" value="<?php echo $id; ?>">
    <div class="row g-3 align-items-center">
        <div class="" style="margin:30px auto">
            <button type="submit" class="btn btn-success">Actualizar</button>
            <button type="button" onclick="listar_producto(1)" class="btn btn-danger">Volver</button>
        </div>
    </div>


    <?php
    $url = "../Images/producto/" . $id . ".png";
    if (!file_exists($url)) {
        $url = "../Images/empty.jpg";
    } else {
        $url = "../Images/producto/" . $id . ".png?r=" . $t;
    }
    ?>

    <div class="row g-3 align-items-center">
        <div class="col-12" style="  text-align: center;  margin: 10px;" id="prev1">
            <img src="<?php echo $url; ?>" style="width:200px;height:200px;border-radius:10px" alt="">
        </div>
    </div>

    <div class="row g-3 align-items-center">
        <div class="" style="margin:30px auto">
            <input type="file" id="file-previ1" onchange="previ('prev1','idbase1','file-previ1')" class="form-control" autocomplete="off" aria-describedby="nombre">
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
                <?php foreach($listaDepreciacion as $value){ 
                    $selected = "";
                    if($value['idDepreciacion'] == $row['idDepreciacion']){
                        $selected = "selected";
                    }
                    ?>
                    <option value="<?php echo $value['idDepreciacion'] ?>" <?php echo $selected ?>><?php echo $value['bien'] . ' (Vida útil: '.$value['vidaUtil'].' años)' ?></option>
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
                <?php foreach($listaDepreciacionDetalle as $value){ 
                    $selected = "";
                    if($value['idDepreciacionDetalle'] == $row['idDepreciacionDetalle']){
                        $selected = "selected";
                    }
                    ?>
                    <option value="<?php echo $value['idDepreciacionDetalle'] ?>" <?php echo $selected ?>><?php echo $value['bienDetalle'] ?></option>
                <?php } ?>
            </select>
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Descripción</label>
        </div>
        <div class="col-9">
            <input type="text" name="descripcion" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $producto ?>">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Código</label>
        </div>
        <div class="col-9">
            <input type="text" id="codigoBarras" name="codigoBarras" required autocomplete="off" class="form-control" placeholder="Auto Generado" readonly value="<?php echo $codigoBarras ?>">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">UID NFC</label>
        </div>
        <div class="col-9">
            <input type="text" id="uidTag" name="uidTag" autocomplete="off" class="form-control" placeholder="Escriba el UID NFC..." value="<?php echo isset($row['uidTag']) ? $row['uidTag'] : '' ?>">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Marca</label>
        </div>
        <div class="col-9">
            <input type="text" name="marca" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $row['marca'] ?>">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Tipo de Adquisición</label>
        </div>
        <div class="col-9">
            <input type="text" name="tipoAdquisicion" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $row['tipoAdquisicion'] ?>">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Costo de Adquisición</label>
        </div>
        <div class="col-9">
            <input type="number" name="costoAdquisicion" required autocomplete="off" class="form-control" placeholder="Escriba..." min="1" value="<?php echo $row['costoAdquisicion'] ?>">
        </div>        
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Estado del Activo</label>
        </div>
        <div class="col-9">
            <select name="valoracion" class="form-control" required>
                <option value="BUENO" <?php echo $row['valoracion'] === 'BUENO' ? 'selected' : '' ?>>Bueno</option>
                <option value="REGULAR" <?php echo $row['valoracion'] === 'REGULAR' ? 'selected' : '' ?>>Regular</option>
                <option value="MALO" <?php echo $row['valoracion'] === 'MALO' ? 'selected' : '' ?>>Malo</option>
            </select>
        </div>        
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Fecha de Ingreso</label>
        </div>
        <div class="col-9">
            <input type="date" name="fechaIngreso" required autocomplete="off" class="form-control" value="<?php echo $row['fechaIngreso']->format('Y-m-d') ?>" max="<?php echo date('Y-m-d') ?>">
        </div>
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Usuario Responsable</label>
        </div>
        <div class="col-9">
            <select name="idUsuario" class="form-control" required>
                <?php foreach($listaUsuarios as $usuario): ?>
                    <option value="<?php echo $usuario['idUsuario']; ?>" <?php echo $row['idUsuarioResponsable'] === $usuario['idUsuario'] ? 'selected' : '' ?>><?php echo $usuario['apellidoPaterno'] . ' ' . $usuario['apellidoMaterno'] . ' ' . $usuario['nombre'] . ' (CI: ' . $usuario['ci'] . ')'; ?></option>
                <?php endforeach; ?>
            </select>
        </div>        
    </div><br>
    <div class="row g-3 align-items-center">
        <div class="col-2">
            <label class="col-form-label">Disponibilidad</label>
        </div>
        <div class="col-9">
            <select id="estado" name="estado" required autocomplete="off" class="form-control">
                <option value="ACTIVO" <?php echo $row['estado'] === 'ACTIVO' ? 'selected' : '' ?>>Activo</option>
                <option value="INACTIVO" <?php echo $row['estado'] === 'INACTIVO' ? 'selected' : '' ?>>Inactivo</option>
            </select>
        </div>
    </div><br>
</form>