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
    echo "<div class='empty py-4'><div class='empty-icon'><i class='ti ti-alert-circle icon-lg text-secondary'></i></div><p class='empty-title'>¡Lista de Productos vacía!</p></div>";
} else {
    // Verificar que el cliente utiliza un dispositivo móvil
    $agente = $_SERVER['HTTP_USER_AGENT'];
    $esDispositivoMovil = preg_match('/android|blackberry|iemobile|opera mini/i', $agente);
    
    $resultado = '<div class="table-responsive">
    <table class="table table-vcenter card-table table-hover text-center">
    <thead>
    <tr>
    <th>Información</th>
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

        $otro = "
        <details class='card border-0 shadow-none mb-0'>
        <summary class='card-header py-2 px-3'>" . $row['producto'] . "</summary>
        <div class='card-body p-3'>
        <div class='text-center mb-3'>
        <img class='avatar avatar-xl' src='" . $url . "' alt='" . $row['producto'] . "'>
        </div>
        <div class='table-responsive'>
        <table class='table table-sm table-vcenter mb-0'>
        <tr>
        <td >Activo</td>
        <td >" . ($row['bienDetalle'] ?? "Sin definir") . "</td>
        </tr>
        <tr>
        <td >Descripción</td>
        <td >" . $row["producto"] . "</td>
        </tr>
        <tr>
        <td >Código</td>
        <td >" . $row["codigoBarras"] . "</td>
        </tr>
        <tr>
        <td >Fecha de Ingreso</td>
        <td >" . $fechaIngreso . "</td>
        </tr>
        <tr>
        <td>Estado</td>
        <td><span class='badge " . $valoracionClass . "'>" . $row['valoracion'] . "</span></td>
        </tr>
        <tr>
        <td>Disponibilidad</td>
        <td><span class='badge " . $estadoClass . "'>" . $row['estado'] . "</span></td>
        </tr>
        <tr>
        <td>Observación</td>
        <td>" . $row['observacion'] . "</td>
        </tr>
        </table>
        </div>
        </div>
        </details>";

        $resultado .= '<tr>
        <td>' . $otro . '</td>
        <td>' . ($row['bienDetalle'] ?? "Sin definir") . '</td>
        <td>' . $row['producto'] . '</td>
        <td>' . $row['codigoBarras'] . '</td>
        <td>' . $fechaIngreso . '</td>
        <td><span class="badge ' . $valoracionClass . '">' . $row['valoracion'] . '</span></td>
        <td><span class="badge ' . $estadoClass . '">' . $row['estado'] . '</span></td>
        <td>
        <button class="btn btn-outline-primary btn-icon" title="Editar" onclick="edit_producto(\'' . $row['idProducto'] . '\')" ' . $hide . '> <i class="ti ti-pencil icon"></i></button>
        <button class="btn btn-outline-info btn-icon" title="Cambiar disponibilidad" onclick="cambiarEstado(\'' . $row['idProducto'] . '\', \'' . $row['estado'] . '\')"><i class="ti ti-lock icon"></i></button>
        '.($esDispositivoMovil ? '' : '<button class="btn btn-outline-warning btn-icon" title="Reporte PDF" onclick="generarReporteBien(\'' . $row['idProducto'] . '\', \'' . $row['estado'] . '\')"> <i class="ti ti-file-pdf icon"></i></button>').'
        '.($esDispositivoMovil ? '<button class="btn btn-outline-success btn-icon" type="button" onclick="AndroidRegisterNFCCode.postMessage(\'' . $row['idProducto'] . '\')" title="Asignar código NFC" id="btn-registrar-codigo" data-id="' . $row['idProducto'] . '"> <i class="ti ti-cpu icon"></i></button>' : '').'
        </td>
        </tr>';
    }

    $resultado .= "
    </tbody>
    </table>
    </div>";

    echo $resultado;
}
