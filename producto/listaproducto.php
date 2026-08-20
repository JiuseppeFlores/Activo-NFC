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
    echo "<div style='text-align:center'><h2>Lista de Producto vacia!</h2></div>";
} else {
    // Verificar que el cliente utiliza un dispositivo móvil
    $agente = $_SERVER['HTTP_USER_AGENT'];
    $esDispositivoMovil = preg_match('/android|blackberry|iemobile|opera mini/i', $agente);
    
    $resultado = '<div class="table-responsive">
    <table style="text-align:center" class="table table-hover">
    <tr>
    <th>Información</th>
    <th>Activo</th>
    <th>Descripción</th>
    <th>Código</th>
    <th>Fecha de Ingreso</th>
    <th>Estado</th>
    <th>Disponibilidad</th>
    <th>Opciones</th>
    </tr>';

    $t = time();
    while ($row = sqlsrv_fetch_array($query)) {
        $id = $row['idProducto'];
        $fechaIngreso = $row['fechaIngreso']->format('d-m-Y');
        $url = "../Images/producto/" . $id . ".png";
        if (!file_exists($url)) {
            $url = "../Images/empty.jpg";
        }
        $url .= "?r=" . $t;

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
        <td>" . $row['valoracion'] . "</td>
        </tr>
        <tr>
        <td>Disponibilidad</td>
        <td>" . $row['estado'] . "</td>
        </tr>
        <tr>
        <td>Observación</td>
        <td>" . $row['observacion'] . "</td>
        </tr>
        </table>
        </table>
        </div>
        </div>
        </details>";

        $resultado .= '<tr style="cursor:pointer">
        <td>' . $otro . '</td>
        <td>' . ($row['bienDetalle'] ?? "Sin definir") . '</td>
        <td>' . $row['producto'] . '</td>
        <td>' . $row['codigoBarras'] . '</td>
        <td>' . $fechaIngreso . '</td>
        <td>' . $row['valoracion'] . '</td>
        <td>' . $row['estado'] . '</td>
        <td>';
        // <button class="btn btn-danger" data-toggle="modal" data-target="#modal_eliminar_producto" data-id="' . $row['idProducto'] . '" ' . $hide . '> <i class="fas fa-trash"></i></button>
        $resultado .= '<button class="btn btn-primary" onclick="edit_producto(\'' . $row['idProducto'] . '\')" ' . $hide . '> <i class="fas fa-edit"></i></button>
        <button class="btn btn-info" title="Cambiar disponibilidad" onclick="cambiarEstado(\'' . $row['idProducto'] . '\', \'' . $row['estado'] . '\')"><i class="fas fa-lock"></i></button>
        '.($esDispositivoMovil ? '' : '<button class="btn btn-warning" onclick="generarReporteBien(\'' . $row['idProducto'] . '\', \'' . $row['estado'] . '\')"> <i class="fas fa-file-pdf"></i></button>').'
        '.($esDispositivoMovil ? '<button class="btn btn-success" type="button" onclick="AndroidRegisterNFCCode.postMessage(\'' . $row['idProducto'] . '\')" title="Asignar código NFC" id="btn-registrar-codigo" data-id="' . $row['idProducto'] . '"> <i class="fas fa-microchip"></i></button>' : '').'
        </td>
        </tr>';
    }

    $resultado .= "
    </table>
    </div>";

    echo $resultado;
}
