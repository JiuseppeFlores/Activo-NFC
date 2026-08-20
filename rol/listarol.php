<?php

include("../conexion.php");

$start_from = $_POST['start'];
$search_in_sql = "";
if (isset($_POST['texto']) && !empty($_POST['texto'])) {
    $texto = $_POST['texto'];
    $search_in_sql .= " WHERE (rol like '%" . $texto . "%' ) ";
}

// if(strlen(trim($search_in_sql)) == 0){
//     $search_in_sql .= " WHERE ".
// }else{
//     $search_in_sql .= " AND ".
// }

$sql = " SELECT * FROM tblRol $search_in_sql ORDER BY idRol ASC offset $start_from ROWS FETCH NEXT 10 ROWS ONLY;";
$query = sqlsrv_query($con, $sql);
$count_row = sqlsrv_has_rows($query);
if ($count_row === false) {
    echo "<div class='empty py-4'><div class='empty-icon'><i class='ti ti-alert-circle icon-lg text-secondary'></i></div><p class='empty-title'>¡Lista de Rol vacía!</p></div>";
} else {

    $resultado = '
    <div class="table-responsive">
    <table class="table table-vcenter card-table table-hover text-center">
    <thead>
    <tr>
    <th>Información</th>
    <th>Rol</th>
    <th class="w-1">Opciones</th>
    </tr>
    </thead>
    <tbody>';

    $t = time();
    while ($row = sqlsrv_fetch_array($query)) {

        $id = $row['idRol'];
        $otro = "
                                    <details class='card border-0 shadow-none mb-0'>
                                        <summary class='card-header py-2 px-3'>" . $row['rol'] . "</summary>
                                        <div class='card-body p-3'>
                                            <div class='table-responsive'>
                                                <table class='table table-sm table-vcenter mb-0'>
                                                    <tr>
                                                        <td>Rol</td>
                                                        <td>" . $row["rol"] . "</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </details>";


        $resultado .= ' <tr>
                                             <td>
                                                 ' . $otro . '
                                             </td>
                                             <td>' . $row['rol'] . '</td>
                                             <td>
                                                 <button class="btn btn-outline-primary btn-icon" title="Editar" onclick="edit_rol(\'' . $row['idRol'] . '\')"> <i class="ti ti-pencil icon"></i></button>
                                             </td>
                                       </tr>
                            ';
    }

    $resultado .= "
                                </tbody>
                                </table>
                            </div>
                            
                    ";

    echo $resultado;
}
