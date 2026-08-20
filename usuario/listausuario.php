<?php
include("../conexion.php");
$idRol = $_SESSION['idRol'];
$idUsuario = $_SESSION['idUsuario'];
$hide = '';
if ($idRol != 1) {
    $hide = 'hidden';
}
$start_from = $_POST['start'];
$search_in_sql = "";
if (isset($_POST['texto']) && !empty($_POST['texto'])) {
    $texto = $_POST['texto'];
    $search_in_sql .= " WHERE (usuario like '%" . $texto . "%'  OR nombre like '%" . $texto . "%'  OR apellidoPaterno like '%" . $texto . "%'  OR apellidoMaterno like '%" . $texto . "%'  OR ci like '%" . $texto . "%'  OR rol like '%" . $texto . "%'  OR area like '%" . $texto . "%' ) ";
}

// if(strlen(trim($search_in_sql)) == 0){
//     $search_in_sql .= " WHERE ".tblUsuario.idRol = tblRol.idRol AND tblUsuario.idArea = tblArea.idArea
// }else{
//     $search_in_sql .= " AND ".tblUsuario.idRol = tblRol.idRol AND tblUsuario.idArea = tblArea.idArea
// }

$sql = " SELECT * FROM tblUsuario tu LEFT JOIN tblArea ta ON tu.idArea = ta.idArea LEFT JOIN tblRol tr ON tu.idRol = tr.idRol $search_in_sql ORDER BY idUsuario DESC offset $start_from ROWS FETCH NEXT 10 ROWS ONLY;";
$query = sqlsrv_query($con, $sql);
$count_row = sqlsrv_has_rows($query);
if ($count_row === false) {
    $accionVacia = $idRol == 1 ? "<div class='empty-action'><a href='#' class='btn btn-primary' onclick='add_usuario(); return false;'><i class='ti ti-plus me-2'></i>Añadir usuario</a></div>" : '';
    echo "<div class='empty py-4'><div class='empty-icon'><i class='ti ti-users icon-lg text-secondary'></i></div><p class='empty-title'>No hay usuarios registrados</p><p class='empty-subtitle text-secondary'>No se encontraron resultados para esta búsqueda.</p>$accionVacia</div>";
} else {
    // Verificar que el cliente utiliza un dispositivo móvil
    $agente = $_SERVER['HTTP_USER_AGENT'];
    $esDispositivoMovil = preg_match('/android|blackberry|iemobile|opera mini/i', $agente);
    $mostrarOpciones = !($esDispositivoMovil && ($idRol == 3 || $idRol == 2));

    $resultado = '
    <div class="table-responsive">
    <table class="table table-vcenter card-table table-hover text-center">
    <thead>
    <tr>
    <th class="w-1">Imagen</th>
    <th>Usuario</th>
    <th>Nombre</th>
    <th>CI</th>
    <th>Rol</th>
    <th>Cargo</th>
    <th>Área</th>'.(
        ($esDispositivoMovil && ($idRol == 3 || $idRol == 2)) ? '' : '<th class="w-1">Opciones</th>'
    ).'
    </tr>
    </thead>
    <tbody>';

    $t = time();
    if ($idRol == 3) {
        while ($row = sqlsrv_fetch_array($query)) {

            $id = $row['idUsuario'];
            if ($id == $idUsuario) {


                $url = "../Images/usuario/" . $id . ".png";
                if (!file_exists($url)) {
                    $url = "../Images/empty.jpg";
                }
                $url .= "?r=" . $t;

                $resultado .= '
            <tr>
            <td><img class="avatar avatar-sm rounded-circle" src="' . $url . '" alt="' . $row['usuario'] . '"></td>
            <td>' . $row['usuario'] . '</td>
            <td>' . $row['nombre'] . ' ' . $row['apellidoPaterno'] . ' ' . $row['apellidoMaterno'] . '</td>
            <td>' . $row['ci'] . '</td>
            <td>' . $row['rol'] . '</td>
            <td>' . $row['cargo'] . '</td>
            <td>' . $row['area'] . '</td>
            '.($mostrarOpciones ? '<td>
            <div class="dropdown">
            <button type="button" class="btn btn-ghost-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Acciones">
            <i class="ti ti-settings-2 me-2"></i>Acciones
            </button>
            <div class="dropdown-menu dropdown-menu-end">
            <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#modal_eliminar_usuario" data-bs-toggle="modal" data-bs-target="#modal_eliminar_usuario" data-id="' . $row['idUsuario'] . '" ' . $hide . '><i class="ti ti-trash me-2"></i>Eliminar</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" onclick="edit_usuario(\'' . $row['idUsuario'] . '\'); return false;" ' . $hide . '><i class="ti ti-pencil me-2"></i>Editar</a>
            '.($esDispositivoMovil ? '': '<a class="dropdown-item" href="#" onclick="asignaciones_usuario(\'' . $row['idUsuario'] . '\'); return false;"><i class="ti ti-file-text me-2"></i>Reporte Asignaciones</a>').'
            </div>
            </div>
            </td>' : '').'
            </tr>';
            }
        }
    } else {
        while ($row = sqlsrv_fetch_array($query)) {

            $id = $row['idUsuario'];
            $sector = "sector" . $id;

            $url = "../Images/usuario/" . $id . ".png";
            if (!file_exists($url)) {
                $url = "../Images/empty.jpg";
            }
            $url .= "?r=" . $t;

            $resultado .= '
            <tr>
            <td><img class="avatar avatar-sm rounded-circle" src="' . $url . '" alt="' . $row['usuario'] . '"></td>
            <td>' . $row['usuario'] . '</td>
            <td>' . $row['nombre'] . ' ' . $row['apellidoPaterno'] . ' ' . $row['apellidoMaterno'] . '</td>
            <td>' . $row['ci'] . '</td>
            <td>' . $row['rol'] . '</td>
            <td>' . $row['cargo'] . '</td>
            <td>' . $row['area'] . '</td>
            '.($mostrarOpciones ? '<td>
            <div class="dropdown">
            <button type="button" class="btn btn-ghost-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Acciones">
            <i class="ti ti-settings-2 me-2"></i>Acciones
            </button>
            <div class="dropdown-menu dropdown-menu-end">
            <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#modal_eliminar_usuario" data-bs-toggle="modal" data-bs-target="#modal_eliminar_usuario" data-id="' . $row['idUsuario'] . '" ' . $hide . '><i class="ti ti-trash me-2"></i>Eliminar</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" onclick="edit_usuario(\'' . $row['idUsuario'] . '\'); return false;" ' . $hide . '><i class="ti ti-pencil me-2"></i>Editar</a>
            '.($esDispositivoMovil ? '' : '<a class="dropdown-item" href="#" onclick="asignaciones_usuario(\'' . $row['idUsuario'] . '\'); return false;"><i class="ti ti-file-text me-2"></i>Reporte Asignaciones</a>').'
            </div>
            </div>
            </td>' : '').'
            </tr>';
        }
    }

    $resultado .= "
    </tbody>
    </table>
    </div>";
    echo $resultado;
}
