<?php

include("../conexion.php");
ob_start();
$search_in_sql = "";
if (isset($_POST['texto']) && !empty($_POST['texto'])) {
    $texto = $_POST['texto'];
    $search_in_sql .= " WHERE (tu.nombre LIKE '%$texto%' OR tu.apellidoPaterno LIKE '%$texto%' OR tu.apellidoMaterno LIKE '%$texto%' OR tp.producto LIKE '%$texto%' OR tp.codigoBarras LIKE '%$texto%' OR tu2.nombre LIKE '%$texto%' OR tu2.apellidoPaterno LIKE '%$texto%' OR tu2.apellidoMaterno LIKE '%$texto%') ";
}

if (isset($_POST['gestion']) && !empty($_POST['gestion'])) {
    $gestion = $_POST['gestion'] . "-01-01 00:00:00";

    $search_in_sql = $search_in_sql == "" ? " WHERE ti.fecha >= '$gestion' AND ti.fecha < DATEADD(YEAR, 1, '$gestion')" : $search_in_sql . " AND ti.fecha >= '$gestion' AND ti.fecha < DATEADD(YEAR, 1, '$gestion')";
}

$sql = "SELECT COUNT(ti.idInventario) FROM tblInventario ti LEFT JOIN tblAsignacion ta ON ti.idAsignacion = ta.idAsignacion LEFT JOIN tblProducto tp ON ta.idProducto = tp.idProducto LEFT JOIN tblUsuario tu ON ta.idUsuario = tu.idUsuario LEFT JOIN tblUsuario tu2 ON ti.idUsuarioCreador = tu2.idUsuario $search_in_sql";


$ejecutar = sqlsrv_query($con, $sql);
while ($row = sqlsrv_fetch_array($ejecutar)) {
    $total_records = $row[0];
}

$total_pages = ceil($total_records / 10);

$table = "<nav style='display: inline-block; list-style-type: none;margin:10px'><ul class='pagination' style='margin:0px'>";

for ($i = 1; $i <= $total_pages; $i++) {

    if ($texto != "") {
        $table .= "
        <li><a href='index.php?page=" . $i . "&busqueda=" . $texto . "'>" . $i . "</a></li>";
    } else {
        $table .= "
        <li><a href='index.php?page=" . $i . "'>" . $i . "</a></li>";
    }
};
$table .= "</ul></nav>";

$mensaje = array('tabla' => $table, 'records' => $total_records);
ob_end_clean();
echo json_encode($mensaje);
