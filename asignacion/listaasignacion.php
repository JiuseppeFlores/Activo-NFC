<?php

include("../conexion.php");
date_default_timezone_set('America/La_Paz');
$idRol = $_SESSION['idRol'];
$idUsuario = $_SESSION['idUsuario'];
$hide = '';
if ($idRol != 1) {
    $hide = 'hidden';
}
$fechaActualFormato = formato_fechas_server(date('Y-m-d H:i:s'), 'd/m/Y H:i');
$fechaActualUnix = strtotime($fechaActualFormato);
$start_from = $_POST['start'];
$search_in_sql = "";
if (isset($_POST['texto']) && !empty($_POST['texto'])) {
    $texto = $_POST['texto'];
    $search_in_sql .= " WHERE (tu.nombre like '%" . $texto . "%'  OR tu.apellidoPaterno like '%" . $texto . "%'  OR tu.apellidoMaterno like '%" . $texto . "%'  OR tp.producto like '%" . $texto . "%'  OR tp.codigoBarras like '%" . $texto . "%'  OR ta.fechaInicial like '%" . $texto . "%'  OR ta.fechaFinal like '%" . $texto . "%' ) ";
}

if (isset($_POST['area']) && !empty($_POST['area'])) {
    $area = $_POST['area'];
    $search_in_sql = $search_in_sql == "" ? " WHERE tu.idArea = " . $area : $search_in_sql . " AND tu.idArea = " . $area;
}

// if(strlen(trim($search_in_sql)) == 0){
//     $search_in_sql .= " WHERE ".tblAsignacion.idUsuario = tblUsuario.idUsuario AND tblAsignacion.idProducto = tblProducto.idProducto
// }else{
//     $search_in_sql .= " AND ".tblAsignacion.idUsuario = tblUsuario.idUsuario AND tblAsignacion.idProducto = tblProducto.idProducto
// }

// Verificar que el cliente utiliza un dispositivo móvil
$agente = $_SERVER['HTTP_USER_AGENT'];
$esDispositivoMovil = preg_match('/android|blackberry|iemobile|opera mini/i', $agente);
$mostrarOpciones = !($esDispositivoMovil && ($idRol == 3 || $idRol == 2));

$sql = " SELECT ta.*, tu.nombre, tu.apellidoPaterno, tu.apellidoMaterno, tp.producto, tp.codigoBarras, CASE WHEN ta.fechaFinal < GETDATE() THEN 'VENCIDO' ELSE 'VIGENTE' END AS estadoAsignacion FROM tblAsignacion ta LEFT JOIN tblUsuario tu ON tu.idUsuario = ta.idUsuario LEFT JOIN tblProducto tp ON tp.idProducto = ta.idProducto $search_in_sql ORDER BY ta.idAsignacion DESC offset $start_from ROWS FETCH NEXT 10 ROWS ONLY;";
// echo $sql;
$query = sqlsrv_query($con, $sql);
$count_row = sqlsrv_has_rows($query);
if ($count_row === false) {
    $accionVacia = $idRol == 1 ? "<div class='empty-action'><a href='#' class='btn btn-primary' onclick='add_asignacion(); return false;'><i class='ti ti-plus me-2'></i>Añadir asignación</a></div>" : '';
    echo "<div class='empty py-4'><div class='empty-icon'><i class='ti ti-arrows-exchange icon-lg text-secondary'></i></div><p class='empty-title'>No hay asignaciones registradas</p><p class='empty-subtitle text-secondary'>No se encontraron resultados para esta búsqueda.</p>$accionVacia</div>";
} else {
    $resultado = '<div class="table-responsive">
    <table class="table table-vcenter card-table table-hover text-center">
    <thead>
    <tr>
    <th class="w-1">
    <div class="checkbox-container">
    <input type="checkbox" id="selectAll" class="form-check-input" onclick="toggleAllCheckboxes(this)">
    </div></th>
    <th class="w-1">ID</th>
    <th>Activo</th>
    <th>Código</th>
    <th>Usuario</th>
    <th>Fecha Inicial</th>
    <th>Fecha Final</th>
    <th>Estado</th>
    '.(($esDispositivoMovil && ($idRol == 3 || $idRol == 2)) ? '' : '<th class="w-1">Opciones</th>').'
    </tr>
    </thead>
    <tbody>';

    $t = time();
    if ($idRol == 3) {
        while ($row = sqlsrv_fetch_array($query)) {
            if ($idUsuario === $idUsuarioAsignacion) {
                $fechaFinalFormato = formato_fechas_server($row['fechaFinal'], 'd/m/Y H:i');
                if ($row['estadoAsignacion'] == 'VENCIDO' && $row['estado'] == 'ASIGNADO') {
                    $claseEstado = "table-danger";
                    $estadoAsignacion = 'VENCIDO';
                } else {
                    $claseEstado = "";
                    $estadoAsignacion = 'VIGENTE';
                }
                $estado = $row['estado'];
                if ($estado == "DEVUELTO") {
                    $estado = "<span class='badge bg-warning-subtle text-warning'>DEVUELTO</span>";
                } else {
                    $estado = "<span class='badge bg-success-subtle text-success'>ASIGNADO</span>";
                }
                $nombreUsuario = $row['nombre'] . " " . $row['apellidoPaterno'] . " " . $row['apellidoMaterno'];
                $id = $row['idAsignacion'];
                $url = "";
                    $resultado .= '<tr class="' . $claseEstado . '">
            <td>
                <div class="checkbox-container">
                    <input type="checkbox" class="selectItem form-check-input" value="' . $id . '" onclick="updateSelectedCount()">
                </div>
            </td>
            <td><span class="text-secondary fw-medium">#' . $id . '</span></td>
            <td>' . $row['producto'] . '</td>
            <td>' . $row['codigoBarras'] . '</td>
            <td>' . $nombreUsuario . '</td>
            <td>' . formato_fechas_server($row["fechaInicial"], 'd/m/Y H:i') . '</td>
            <td>' . $fechaFinalFormato . '</td>
            <td>' . $estado . '</td>
            '.($mostrarOpciones ? '<td>
                <div class="dropdown">
                <button type="button" class="btn btn-ghost-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Acciones">
                <i class="ti ti-settings-2 me-2"></i>Acciones
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#modal_eliminar_asignacion" data-bs-toggle="modal" data-bs-target="#modal_eliminar_asignacion" data-id="' . $id . '" ' . $hide . '><i class="ti ti-trash me-2"></i>Eliminar</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" onclick="edit_asignacion(' . $id . ', `' . $estadoAsignacion . '`); return false;" ' . $hide . '><i class="ti ti-pencil me-2"></i>Editar</a>
                </div>
                </div>
                </td>' : '') . '
            </tr>';
            }
        }
    } else {
        while ($row = sqlsrv_fetch_array($query)) {
            $fechaFinalFormato = formato_fechas_server($row['fechaFinal'], 'd/m/Y H:i');
            if ($row['estadoAsignacion'] == 'VENCIDO' && $row['estado'] == 'ASIGNADO') {
                $claseEstado = "table-danger";
                $estadoAsignacion = 'VENCIDO';
            } else {
                $claseEstado = "";
                $estadoAsignacion = 'VIGENTE';
            }
            $estado = $row['estado'];
            if ($estado == "DEVUELTO") {
                $estado = "<span class='badge bg-warning-subtle text-warning'>DEVUELTO</span>";
            } else {
                $estado = "<span class='badge bg-success-subtle text-success'>ASIGNADO</span>";
            }
            $nombreUsuario = $row['nombre'] . " " . $row['apellidoPaterno'] . " " . $row['apellidoMaterno'];
            $id = $row['idAsignacion'];
            $url = "";
            $resultado .= '<tr class="' . $claseEstado . '">
            <td>
                <div class="checkbox-container">
                    <input type="checkbox" class="selectItem form-check-input" value="' . $id . '" onclick="updateSelectedCount()">
                </div>
            </td>
            <td><span class="text-secondary fw-medium">#' . $id . '</span></td>
            <td>' . $row['producto'] . '</td>
            <td>' . $row['codigoBarras'] . '</td>
            <td>' . $nombreUsuario . '</td>
            <td>' . formato_fechas_server($row["fechaInicial"], 'd/m/Y H:i') . '</td>
            <td>' . $fechaFinalFormato . '</td>
            <td>' . $estado . '</td>
            '.($mostrarOpciones ? '<td>
                <div class="dropdown">
                <button type="button" class="btn btn-ghost-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Acciones">
                <i class="ti ti-settings-2 me-2"></i>Acciones
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#modal_eliminar_asignacion" data-bs-toggle="modal" data-bs-target="#modal_eliminar_asignacion" data-id="' . $id . '" ' . $hide . '><i class="ti ti-trash me-2"></i>Eliminar</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" onclick="edit_asignacion(' . $id . ', `' . $estadoAsignacion . '`); return false;" ' . $hide . '><i class="ti ti-pencil me-2"></i>Editar</a>
                </div>
                </div>
                </td>' : '') . '
            </tr>';
        }
    }

    $resultado .= "
    </tbody>
    </table>
    </div>
    ";

    echo $resultado;
}
