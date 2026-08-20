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
    echo "<div class='empty py-4'><div class='empty-icon'><i class='ti ti-clipboard-check icon-lg text-secondary'></i></div><p class='empty-title'>No hay inspecciones registradas</p><p class='empty-subtitle text-secondary'>No se encontraron resultados para esta búsqueda.</p></div>";
} else {

    $resultado = '
    <div class="table-responsive">
    <table class="table table-vcenter card-table table-hover text-center">
    <thead>
    <tr>
    <th class="w-1">ID</th>
    <th>Usuario</th>
    <th>Activo</th>
    <th>Código</th>
    <th>Revisor</th>
    <th>Observación</th>
    <th>Fecha</th>
    </tr>
    </thead>
    <tbody>';

    $t = time();
    while ($row = sqlsrv_fetch_array($query)) {
        $id = $row['idInventario'];
        $fechaFormato = date_format($row['fecha'], 'd/m/Y H:i');
        $resultado .= '
        <tr>
        <td><span class="text-secondary fw-medium">#' . $id . '</span></td>
        <td>' . $row['nombre'] . ' ' . $row['apellidoPaterno'] . ' ' . $row['apellidoMaterno'] . '</td>
        <td>' . $row['producto'] . '</td>
        <td>' . $row['codigoBarras'] . '</td>
        <td>' . $row['nombreCreador'] . ' ' . $row['apellidoPaternoCreador'] . ' ' . $row['apellidoMaternoCreador'] . '</td>
        <td>' . $row['observacion'] . '</td>
        <td>' . $fechaFormato . '</td>
        </tr>';
    }

    $resultado .= "
    </tbody>
    </table>
    </div>
    ";

    echo $resultado;
}
