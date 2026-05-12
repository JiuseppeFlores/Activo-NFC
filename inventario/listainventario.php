<?php
include("../conexion.php");

$start_from = $_POST['start'];
$search_in_sql = "";
if (isset($_POST['texto']) && !empty($_POST['texto'])) {
    $texto = $_POST['texto'];
    $search_in_sql .= " WHERE (tu.nombre LIKE '%$texto%' OR tu.apellidoPaterno LIKE '%$texto%' OR tu.apellidoMaterno LIKE '%$texto%' OR tp.producto LIKE '%$texto%' OR tp.codigoBarras LIKE '%$texto%' OR tu2.nombre LIKE '%$texto%' OR tu2.apellidoPaterno LIKE '%$texto%' OR tu2.apellidoMaterno LIKE '%$texto%') ";
}

if (isset($_POST['gestion']) && !empty($_POST['gestion'])) {
    $gestion = $_POST['gestion'] . "-01-01 00:00:00";

    $search_in_sql = $search_in_sql == "" ? " WHERE ti.fecha >= '$gestion' AND ti.fecha < DATEADD(YEAR, 1, '$gestion')" : $search_in_sql . " AND ti.fecha >= '$gestion' AND ti.fecha < DATEADD(YEAR, 1, '$gestion')";
}

$sql = "SELECT ti.*, tu.nombre, tu.apellidoPaterno, tu.apellidoMaterno, tp.producto, tp.codigoBarras, tu2.nombre AS nombreCreador, tu2.apellidoPaterno AS apellidoPaternoCreador, tu2.apellidoMaterno AS apellidoMaternoCreador FROM tblInventario ti LEFT JOIN tblAsignacion ta ON ti.idAsignacion = ta.idAsignacion LEFT JOIN tblProducto tp ON ta.idProducto = tp.idProducto LEFT JOIN tblUsuario tu ON ta.idUsuario = tu.idUsuario LEFT JOIN tblUsuario tu2 ON ti.idUsuarioCreador = tu2.idUsuario $search_in_sql ORDER BY ti.idInventario DESC offset $start_from ROWS FETCH NEXT 10 ROWS ONLY;";
// echo $sql;
$query = sqlsrv_query($con, $sql);
$count_row = sqlsrv_has_rows($query);
if ($count_row === false) {
    echo "<div style='text-align:center'><h2>¡Lista de inspecciones vacía!</h2></div>";
} else {

    $resultado = '
    <div class="table-responsive">
    <table style="text-align:center" class="table table-hover">
    <th>Información</th>
    <th>Usuario</th>
    <th>Activo</th>
    <th>Código</th>
    <th>Revisor</th>
    <th>Observación</th>
    <th>Fecha</th>';
    // $resultado .= '
    // <th>Opciones</th>';
    $resultado .= '
    </tr>';

    $t = time();
    while ($row = sqlsrv_fetch_array($query)) {
        $id = $row['idInventario'];
        $fechaFormato = date_format($row['fecha'], 'd/m/Y H:i');
        $expand = "expand";
        $sector = "sector" . $id;
        $url = "";
        $otro = "
        <div id='sector" . $id . "' class='email' onclick='this.classList.add(\"$expand\")'>
        <div class='from'>
        <div class='from-contents'>
        <div class='avatar me' style='background-image: url($url)'></div>
        <div class='name'>" . $row['producto'] . "</div>
        </div>
        </div>
        <div class='to'>
        <div class='to-contents'>
        <div class='top'>
        <div class='avatar-large me' style='background-image: url()'></div>
        <div class='name-large'>" . $row['producto'] . "</div>
        <div class='x-touch' onclick='document.getElementById(\"$sector\").classList.remove(\"$expand\");event.stopPropagation();'>
        <div class='x'>
        <div class='line1'></div>
        <div class='line2'></div>
        </div>
        </div>
        </div>
        <div class='bottom'>
        <div class='row2'>
        <div class='table-responsive'>
        <table style='margin:5px auto; width: 85%; border-collapse: separate;border:hidden;' class='table tdstyle' border='1' >
        <tr>
        <td>Usuario</td>
        <td>" . $row['nombre'] . " " . $row['apellidoPaterno'] . " " . $row['apellidoMaterno'] . "</td>
        </tr>
        <tr>
        <td>Activo</td>
        <td>" . $row['producto'] . "</td>
        </tr>
        <tr>
        <td>Código</td>
        <td>" . $row['codigoBarras'] . "</td>
        </tr>
        <tr>
        <td>Revisor</td>
        <td>" . $row['nombreCreador'] . " " . $row['apellidoPaternoCreador'] . " " . $row['apellidoMaternoCreador'] . "</td>
        </tr>
        <tr>
        <td>Observación</td>
        <td>" . $row['observacion'] . "</td>
        </tr>
        <tr>
        <td>Fecha</td>
        <td>" . $fechaFormato . "</td>
        </tr>
        </table>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
        ";


        $resultado .= '
        <tr style="cursor:pointer">
        <td>' . $otro . '</td>
        <td>' . $row['nombre'] . ' ' . $row['apellidoPaterno'] . ' ' . $row['apellidoMaterno'] . '</td>
        <td>' . $row['producto'] . '</td>
        <td>' . $row['codigoBarras'] . '</td>
        <td>' . $row['nombreCreador'] . ' ' . $row['apellidoPaternoCreador'] . ' ' . $row['apellidoMaternoCreador'] . '</td>
        <td>' . $row['observacion'] . '</td>
        <td>' . $fechaFormato . '</td>';
        // $resultado .= '
        // <td>
        // <button class="btn btn-danger" data-toggle="modal" data-target="#modal_eliminar_inventario" data-id="' . $id . '"><i class="fas fa-trash"></i></button>
        // <button class="btn btn-primary" onclick="edit_inventario(' . $id . ')"><i class="fas fa-edit"></i></button>
        // </td>';
        $resultado .= '
        </tr>';
    }

    $resultado .= "
    </table>
    </div>
    ";

    echo $resultado;
}
