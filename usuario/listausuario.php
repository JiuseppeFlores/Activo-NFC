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
    echo "<div class='empty py-4'><div class='empty-icon'><i class='ti ti-alert-circle icon-lg text-secondary'></i></div><p class='empty-title'>¡Lista de Usuario vacía!</p></div>";
} else {
    // Verificar que el cliente utiliza un dispositivo móvil
    $agente = $_SERVER['HTTP_USER_AGENT'];
    $esDispositivoMovil = preg_match('/android|blackberry|iemobile|opera mini/i', $agente);

    $resultado = '
    <div class="table-responsive">
    <table class="table table-vcenter card-table table-hover text-center">
    <thead>
    <tr>
    <th>Información</th>
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

                $otro = "
            <details class='card border-0 shadow-none mb-0'>
            <summary class='card-header py-2 px-3'>" . $row['usuario'] . "</summary>
            <div class='card-body p-3'>
            <div class='text-center mb-3'>
            <img class='avatar avatar-xl' src='" . $url . "' alt='" . $row['usuario'] . "'>
            </div>
            <div class='table-responsive'>
            <table class='table table-sm table-vcenter mb-0'>
            <tr>
            <td >Usuario</td>
            <td >" . $row["usuario"] . "</td>
            </tr>
            <tr>
            <td >Nombre</td>
            <td >" . $row["nombre"] . " " . $row['apellidoPaterno'] . " " . $row['apellidoMaterno'] . "</td>
            </tr>
            <tr>
            <td >CI</td>
            <td >" . $row["ci"] . "</td>
            </tr>
            <tr>
            <td >Correo</td>
            <td >" . $row["correo"] . "</td>
            </tr>
            <tr>
            <td >Rol</td>
            <td >" . $row["rol"] . "</td>
            </tr>
            <tr>
            <td >Cargo</td>
            <td >" . $row["cargo"] . "</td>
            </tr>
            <tr>
            <td >Area</td>
            <td >" . $row["area"] . "</td>
            </tr>
            </table>
            </div>
            </div>
            </details>";
                $resultado .= '
            <tr>
            <td>' . $otro . '</td>
            <td>' . $row['usuario'] . '</td>
            <td>' . $row['nombre'] . ' ' . $row['apellidoPaterno'] . ' ' . $row['apellidoMaterno'] . '</td>
            <td>' . $row['ci'] . '</td>
            <td>' . $row['rol'] . '</td>
            <td>' . $row['cargo'] . '</td>
            <td>' . $row['area'] . '</td>
            <td>
            <button title="Eliminar Usuario" class="btn btn-outline-danger btn-icon" data-toggle="modal" data-target="#modal_eliminar_usuario" data-bs-toggle="modal" data-bs-target="#modal_eliminar_usuario" data-id="' . $row['idUsuario'] . '" ' . $hide . '> <i class="ti ti-trash icon"></i></button>
            <button title="Editar Usuario" class="btn btn-outline-primary btn-icon" onclick="edit_usuario(\'' . $row['idUsuario'] . '\')" ' . $hide . '> <i class="ti ti-pencil icon"></i></button>
            '.($esDispositivoMovil ? '': '<button title="Reporte Asignaciones" class="btn btn-outline-warning btn-icon" onclick="asignaciones_usuario(\'' . $row['idUsuario'] . '\')"> <i class="ti ti-file-text icon"></i></button>').'
            </td>
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

            $otro = "
            <details class='card border-0 shadow-none mb-0'>
            <summary class='card-header py-2 px-3'>" . $row['usuario'] . "</summary>
            <div class='card-body p-3'>
            <div class='text-center mb-3'>
            <img class='avatar avatar-xl' src='" . $url . "' alt='" . $row['usuario'] . "'>
            </div>
            <div class='table-responsive'>
            <table class='table table-sm table-vcenter mb-0'>
            <tr>
            <td >Usuario</td>
            <td >" . $row["usuario"] . "</td>
            </tr>
            <tr>
            <td >Nombre</td>
            <td >" . $row["nombre"] . " " . $row['apellidoPaterno'] . " " . $row['apellidoMaterno'] . "</td>
            </tr>
            <tr>
            <td >CI</td>
            <td >" . $row["ci"] . "</td>
            </tr>
            <tr>
            <td >Correo</td>
            <td >" . $row["correo"] . "</td>
            </tr>
            <tr>
            <td >Rol</td>
            <td >" . $row["rol"] . "</td>
            </tr>
            <tr>
            <td >Cargo</td>
            <td >" . $row["cargo"] . "</td>
            </tr>
            <tr>
            <td >Area</td>
            <td >" . $row["area"] . "</td>
            </tr>
            </table>
            </div>
            </div>
            </details>";
            $resultado .= '
            <tr>
            <td>' . $otro . '</td>
            <td>' . $row['usuario'] . '</td>
            <td>' . $row['nombre'] . ' ' . $row['apellidoPaterno'] . ' ' . $row['apellidoMaterno'] . '</td>
            <td>' . $row['ci'] . '</td>
            <td>' . $row['rol'] . '</td>
            <td>' . $row['cargo'] . '</td>
            <td>' . $row['area'] . '</td>
            <td>
            <button title="Eliminar Usuario" class="btn btn-outline-danger btn-icon" data-toggle="modal" data-target="#modal_eliminar_usuario" data-bs-toggle="modal" data-bs-target="#modal_eliminar_usuario" data-id="' . $row['idUsuario'] . '" ' . $hide . '> <i class="ti ti-trash icon"></i></button>
            <button title="Editar Usuario" class="btn btn-outline-primary btn-icon" onclick="edit_usuario(\'' . $row['idUsuario'] . '\')" ' . $hide . '> <i class="ti ti-pencil icon"></i></button>
            '.($esDispositivoMovil ? '' : '<button title="Reporte Asignaciones" class="btn btn-outline-warning btn-icon" onclick="asignaciones_usuario(\'' . $row['idUsuario'] . '\')"> <i class="ti ti-file-text icon"></i></button>').'
            </td>
            </tr>';
        }
    }

    $resultado .= "
    </tbody>
    </table>
    </div>";
    echo $resultado;
}
