<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    include("../conexion.php");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    header("Expires: 0");
    $start_from = $_POST['start'];
    $search_in_sql = "";
    if (isset($_POST['texto']) && !empty($_POST['texto'])) {
        $texto = $_POST['texto'];
        $search_in_sql .= " WHERE (area like '%" . $texto . "%' ) ";
    }

    // if(strlen(trim($search_in_sql)) == 0){
    //     $search_in_sql .= " WHERE ".
    // }else{
    //     $search_in_sql .= " AND ".
    // }

    $sql = " SELECT * FROM tblArea $search_in_sql ORDER BY idArea DESC offset $start_from ROWS FETCH NEXT 10 ROWS ONLY;";
    $query = sqlsrv_query($con, $sql);
    $count_row = sqlsrv_has_rows($query);
    if ($count_row === false) {
        echo "<div class='empty py-4'><div class='empty-icon'><i class='ti ti-building-community icon-lg text-secondary'></i></div><p class='empty-title'>No hay áreas registradas</p><p class='empty-subtitle text-secondary'>No se encontraron resultados para esta búsqueda.</p><div class='empty-action'><a href='#' class='btn btn-primary' onclick='add_area(); return false;'><i class='ti ti-plus me-2'></i>Añadir área</a></div></div>";
    } else {

        $resultado = '
        <div class="table-responsive">
        <table class="table table-vcenter card-table table-hover text-center">
        <thead>
        <tr>
        <th class="w-1">ID</th>
        <th>Área</th>
        <th class="w-1">Opciones</th>
        </tr>
        </thead>
        <tbody>';

        while ($row = sqlsrv_fetch_array($query)) {
            $resultado .= '<tr>
            <td><span class="text-secondary fw-medium">#' . $row['idArea'] . '</span></td>
            <td>' . $row['area'] . '</td>
            <td>
            <div class="dropdown">
            <button type="button" class="btn btn-ghost-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Acciones">
            <i class="ti ti-settings-2 me-2"></i>Acciones
            </button>
            <div class="dropdown-menu dropdown-menu-end">
            <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#modal_eliminar_area" data-bs-toggle="modal" data-bs-target="#modal_eliminar_area" data-id="' . $row['idArea'] . '"><i class="ti ti-trash me-2"></i>Eliminar</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" onclick="edit_area(\'' . $row['idArea'] . '\'); return false;"><i class="ti ti-pencil me-2"></i>Editar</a>
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
} else {
    echo "No tiene acceso a esta parte del sistema.";
}
