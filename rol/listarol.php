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
    echo "<div class='empty py-4'><div class='empty-icon'><i class='ti ti-shield-lock icon-lg text-secondary'></i></div><p class='empty-title'>No hay roles registrados</p><p class='empty-subtitle text-secondary'>No se encontraron resultados para esta búsqueda.</p></div>";
} else {

    $resultado = '
    <div class="table-responsive">
    <table class="table table-vcenter card-table table-hover text-center">
    <thead>
    <tr>
    <th class="w-1">ID</th>
    <th>Rol</th>
    <th class="w-1">Opciones</th>
    </tr>
    </thead>
    <tbody>';

    $t = time();
    while ($row = sqlsrv_fetch_array($query)) {

        $id = $row['idRol'];
        $resultado .= ' <tr>
                                             <td><span class="text-secondary fw-medium">#' . $id . '</span></td>
                                             <td>' . $row['rol'] . '</td>
                                             <td>
                                                 <div class="dropdown">
                                                     <button type="button" class="btn btn-ghost-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Acciones">
                                                         <i class="ti ti-settings-2 me-2"></i>Acciones
                                                     </button>
                                                     <div class="dropdown-menu dropdown-menu-end">
                                                         <a class="dropdown-item" href="#" onclick="edit_rol(\'' . $row['idRol'] . '\'); return false;"><i class="ti ti-pencil me-2"></i>Editar</a>
                                                     </div>
                                                 </div>
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
