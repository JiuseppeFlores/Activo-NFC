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

<form id="edit_producto" class="card">
    <input type="hidden" name="idProducto" value="<?php echo $id; ?>">
    <div class="card-header">
        <h3 class="card-title">Editar Activo</h3>
    </div>
    <div class="card-body">
        <?php
        $url = "../Images/producto/" . $id . ".png";
        if (!file_exists($url)) {
            $url = "../Images/empty.jpg";
        } else {
            $url = "../Images/producto/" . $id . ".png?r=" . $t;
        }
        ?>

        <div class="text-center mb-3">
            <div id="prev1" class="mb-2">
                <img src="<?php echo $url; ?>" class="avatar avatar-xl rounded" style="width:120px;height:120px;object-fit:cover;" alt="Imagen de activo">
            </div>
            <div class="w-50 mx-auto">
                <input type="file" id="file-previ1" onchange="previ('prev1','idbase1','file-previ1')" class="form-control" autocomplete="off">
                <input type="hidden" id="idbase1" name="idbase1" value="">
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Tipo de Activo</label>
                <select id="tipoProducto" name="tipoProducto" required autocomplete="off" class="form-select" onchange="getBien()">
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
            <div class="col-md-6">
                <label class="form-label">Activo</label>
                <select id="bien" name="bien" required autocomplete="off" class="form-select" onchange="countBien()">
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
            <div class="col-md-6">
                <label class="form-label">Descripción</label>
                <input type="text" name="descripcion" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $producto ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Código</label>
                <input type="text" id="codigoBarras" name="codigoBarras" required autocomplete="off" class="form-control" placeholder="Auto Generado" readonly value="<?php echo $codigoBarras ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">UID NFC</label>
                <input type="text" id="uidTag" name="uidTag" autocomplete="off" class="form-control" placeholder="Escriba el UID NFC..." value="<?php echo isset($row['uidTag']) ? $row['uidTag'] : '' ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Marca</label>
                <input type="text" name="marca" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $row['marca'] ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Tipo de Adquisición</label>
                <input type="text" name="tipoAdquisicion" required autocomplete="off" class="form-control" placeholder="Escriba..." value="<?php echo $row['tipoAdquisicion'] ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Costo de Adquisición</label>
                <input type="number" name="costoAdquisicion" required autocomplete="off" class="form-control" placeholder="Escriba..." min="1" value="<?php echo $row['costoAdquisicion'] ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Estado del Activo</label>
                <select name="valoracion" class="form-select" required>
                    <option value="BUENO" <?php echo $row['valoracion'] === 'BUENO' ? 'selected' : '' ?>>Bueno</option>
                    <option value="REGULAR" <?php echo $row['valoracion'] === 'REGULAR' ? 'selected' : '' ?>>Regular</option>
                    <option value="MALO" <?php echo $row['valoracion'] === 'MALO' ? 'selected' : '' ?>>Malo</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha de Ingreso</label>
                <input type="date" name="fechaIngreso" required autocomplete="off" class="form-control" value="<?php echo $row['fechaIngreso']->format('Y-m-d') ?>" max="<?php echo date('Y-m-d') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Usuario Responsable</label>
                <select name="idUsuario" class="form-select" required>
                    <?php foreach($listaUsuarios as $usuario): ?>
                        <option value="<?php echo $usuario['idUsuario']; ?>" <?php echo $row['idUsuarioResponsable'] === $usuario['idUsuario'] ? 'selected' : '' ?>><?php echo $usuario['apellidoPaterno'] . ' ' . $usuario['apellidoMaterno'] . ' ' . $usuario['nombre'] . ' (CI: ' . $usuario['ci'] . ')'; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Disponibilidad</label>
                <select id="estado" name="estado" required autocomplete="off" class="form-select">
                    <option value="ACTIVO" <?php echo $row['estado'] === 'ACTIVO' ? 'selected' : '' ?>>Activo</option>
                    <option value="INACTIVO" <?php echo $row['estado'] === 'INACTIVO' ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <button type="button" onclick="listar_producto(1)" class="btn btn-secondary me-2">Volver</button>
        <button type="submit" class="btn btn-success">Actualizar</button>
    </div>
</form>