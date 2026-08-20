<?php

include("../conexion.php");
$idRol = $_SESSION['idRol'];
$hide = '';
if ($idRol != 1) {
  $hide = 'hidden';
}
$start_from = $_POST['start'];
$search_in_sql = "";
if (isset($_POST['texto']) && !empty($_POST['texto'])) {
    $texto = $_POST['texto'];
    $search_in_sql .= " WHERE (tp.producto like '%" . $texto . "%'  OR tp.codigoBarras like '%" . $texto . "%' ) ";
}

// if(strlen(trim($search_in_sql)) == 0){
//     $search_in_sql .= " WHERE ".
// }else{
//     $search_in_sql .= " AND ".
// }

$sql = " SELECT * FROM tblProducto tp LEFT JOIN tblDepreciacionDetalle tdd ON tp.idDepreciacionDetalle = tdd.idDepreciacionDetalle $search_in_sql ORDER BY tp.idProducto DESC offset $start_from ROWS FETCH NEXT 10 ROWS ONLY;";
$query = sqlsrv_query($con, $sql);
$count_row = sqlsrv_has_rows($query);
if ($count_row === false) {
    $accionVacia = $idRol == 1 ? "<div class='empty-action'><a href='#' class='btn btn-primary' onclick='add_producto(); return false;'><i class='ti ti-plus me-2'></i>Añadir activo</a></div>" : '';
    echo "<div class='empty py-4'><div class='empty-icon'><i class='ti ti-device-desktop icon-lg text-secondary'></i></div><p class='empty-title'>No hay activos registrados</p><p class='empty-subtitle text-secondary'>No se encontraron resultados para esta búsqueda.</p>$accionVacia</div>";
} else {
    // Verificar que el cliente utiliza un dispositivo móvil
    $agente = $_SERVER['HTTP_USER_AGENT'];
    $esDispositivoMovil = preg_match('/android|blackberry|iemobile|opera mini/i', $agente);
    
    $resultado = '<div class="table-responsive">
    <table class="table table-vcenter card-table table-hover text-center">
    <thead>
    <tr>
    <th class="w-1">Imagen</th>
    <th>Activo</th>
    <th>Descripción</th>
    <th>Código</th>
    <th>Fecha de Ingreso</th>
    <th>Estado</th>
    <th>Disponibilidad</th>
    <th class="w-1">Opciones</th>
    </tr>
    </thead>
    <tbody>';

    $t = time();
    while ($row = sqlsrv_fetch_array($query)) {
        $id = $row['idProducto'];
        $fechaIngreso = $row['fechaIngreso']->format('d-m-Y');
        $url = "../Images/producto/" . $id . ".png";
        if (!file_exists($url)) {
            $url = "../Images/empty.jpg";
        }
        $url .= "?r=" . $t;

        $valoracionClass = 'bg-secondary-subtle text-secondary';
        if ($row['valoracion'] === 'BUENO') $valoracionClass = 'bg-success-subtle text-success';
        else if ($row['valoracion'] === 'REGULAR') $valoracionClass = 'bg-warning-subtle text-warning';
        else if ($row['valoracion'] === 'MALO') $valoracionClass = 'bg-danger-subtle text-danger';

        $estadoClass = $row['estado'] === 'ACTIVO' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary';

        $resultado .= '<tr>
        <td><img class="avatar avatar-sm rounded" src="' . $url . '" alt="' . $row['producto'] . '"></td>
        <td>' . ($row['bienDetalle'] ?? "Sin definir") . '</td>
        <td>' . $row['producto'] . '</td>
        <td>' . $row['codigoBarras'] . '</td>
        <td>' . $fechaIngreso . '</td>
        <td><span class="badge ' . $valoracionClass . '">' . $row['valoracion'] . '</span></td>
        <td><span class="badge ' . $estadoClass . '">' . $row['estado'] . '</span></td>
        <td>
        <div class="dropdown">
        <button type="button" class="btn btn-ghost-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Acciones">
        <i class="ti ti-settings-2 me-2"></i>Acciones
        </button>
        <div class="dropdown-menu dropdown-menu-end">
        <a class="dropdown-item" href="#" onclick="edit_producto(\'' . $row['idProducto'] . '\'); return false;" ' . $hide . '><i class="ti ti-pencil me-2"></i>Editar</a>
        <a class="dropdown-item" href="#" onclick="cambiarEstado(\'' . $row['idProducto'] . '\', \'' . $row['estado'] . '\'); return false;"><i class="ti ti-lock me-2"></i>Cambiar disponibilidad</a>
        '.($esDispositivoMovil ? '' : '<a class="dropdown-item" href="#" onclick="generarReporteBien(\'' . $row['idProducto'] . '\', \'' . $row['estado'] . '\'); return false;"><i class="ti ti-file-pdf me-2"></i>Reporte PDF</a>').'
        '.($esDispositivoMovil ? '<a class="dropdown-item" href="#" onclick="AndroidRegisterNFCCode.postMessage(\'' . $row['idProducto'] . '\'); return false;" id="btn-registrar-codigo" data-id="' . $row['idProducto'] . '"><i class="ti ti-cpu me-2"></i>Asignar código NFC</a>' : '').'
        </div>
        </div>
        </td>
        </tr>';
    }

    $resultado .= "
    </tbody>
    </table>
    </div>";

    echo $resultado;
}
