<?php

include("../conexion.php");
ob_start();
$texto = $_POST['texto'];

$search_in_sql = "";
if (!empty($texto)) {
    $search_in_sql = " WHERE (area like '%" . $texto . "%' )";
}

$sql = "SELECT COUNT(idArea) FROM tblArea $search_in_sql";


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
