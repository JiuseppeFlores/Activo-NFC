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

$sql = " SELECT ta.*, tu.nombre, tu.apellidoPaterno, tu.apellidoMaterno, tp.producto, tp.codigoBarras, CASE WHEN ta.fechaFinal < GETDATE() THEN 'VENCIDO' ELSE 'VIGENTE' END AS estadoAsignacion FROM tblAsignacion ta LEFT JOIN tblUsuario tu ON tu.idUsuario = ta.idUsuario LEFT JOIN tblProducto tp ON tp.idProducto = ta.idProducto $search_in_sql ORDER BY ta.idAsignacion DESC offset $start_from ROWS FETCH NEXT 10 ROWS ONLY;";
// echo $sql;
$query = sqlsrv_query($con, $sql);
$count_row = sqlsrv_has_rows($query);
if ($count_row === false) {
    echo "<div style='text-align:center'><h2>¡Lista de Asignacion vacía!</h2></div>";
} else {
    $resultado = '<div class="table-responsive">
    <table style="text-align:center" class="table table-hover table-align-center">
    <tr>
    <th>
    <div class="checkbox-container">
    <input type="checkbox" id="selectAll" class="checkbox-lg" onclick="toggleAllCheckboxes(this)">
    </div></th>
    <th>Información</th>
    <th>Activo</th>
    <th>Código</th>
    <th>Usuario</th>
    <th>Fecha Inicial</th>
    <th>Fecha Final</th>
    <th>Estado</th>
    '.($esDispositivoMovil && $idRol == 3 ? '' : '<th>Opciones</th>').'
    </tr>';

    $t = time();
    if ($idRol == 3) {
        while ($row = sqlsrv_fetch_array($query)) {
            $idUsuarioAsignacion = $row['idUsuario'];
            if ($idUsuario === $idUsuarioAsignacion) {
                $fechaFinalFormato = formato_fechas_server($row['fechaFinal'], 'd/m/Y H:i');
                if ($row['estadoAsignacion'] == 'VENCIDO' && $row['estado'] == 'ASIGNADO') {
                    $claseEstado = "alerta-tabla";
                    $estadoAsignacion = 'VENCIDO';
                } else {
                    $claseEstado = "";
                    $estadoAsignacion = 'VIGENTE';
                }
                $estado = $row['estado'];
                if ($estado == "DEVUELTO") {
                    $estado = "<span class='badge badge-warning'>DEVUELTO</span>";
                } else {
                    $estado = "<span class='badge badge-success'>ASIGNADO</span>";
                }
                $nombreUsuario = $row['nombre'] . " " . $row['apellidoPaterno'] . " " . $row['apellidoMaterno'];
                $id = $row['idAsignacion'];
                $expand = "expand";
                $sector = "sector" . $id;
                $url = "";
                $otro = "
            <div id='sector" . $id . "' class='email' onclick='this.classList.add(\"$expand\")'>
            <div class='from'>
            <div class='from-contents'>
            <div class='avatar me' style='background-image: url($url)'></div>
            <div class='name'>" . $row['producto'] . " - " . $row['codigoBarras'] . "</div>
            </div>
            </div>
            <div class='to'>
            <div class='to-contents'>
            <div class='top'>
            <div class='avatar-large me' style='background-image: url()'></div>
            <div class='name-large'>" . $row['producto'] . "</div>
            <div class='x-touch' onclick='document.getElementById(\"$sector\").classList.remove(\"$expand\");event.stopPropagation();'>
            <div class='x'>
            <div class='line1'></div>
            <div class='line2'></div>
            </div>
            </div>
            </div>
            <div class='bottom'>
            <div class='row2'>
            <div class='table-responsive'>
            <table style='margin:5px auto; width: 85%; border-collapse: separate;border:hidden;' class='table tdstyle' border='1' >
            <tr>
            <td >Activo</td>
            <td >" . $row["producto"] . "</td>
            </tr>
            <tr>
            <td >Cód. Barras</td>
            <td >" . $row["codigoBarras"] . "</td>
            </tr>
            <tr>
            <td >Usuario</td>
            <td >" . $nombreUsuario . "</td>
            </tr>
            <tr>
            <td >Fecha Inicial</td>
            <td >" . formato_fechas_server($row["fechaInicial"], 'd/m/Y H:i') . "</td>
            </tr>
            <tr>
            <td >Fecha Final</td>
            <td >" . $fechaFinalFormato . "</td>
            </tr>
            <tr>
            <td >Estado</td>
            <td >" . $estado . "</td>
            </tr>
            </table>
            </div>
            </div>
            </div>
            </div>
            </div>
            </div>
            ";


                $resultado .= '<tr style="cursor:pointer" class="' . $claseEstado . '">
            <td>
                <div class="checkbox-container">
                    <input type="checkbox" class="selectItem checkbox-lg" value="' . $id . '" onclick="updateSelectedCount()">
                </div>
            </td>
            <td>' . $otro . '</td>
            <td>' . $row['producto'] . '</td>
            <td>' . $row['codigoBarras'] . '</td>
            <td>' . $nombreUsuario . '</td>
            <td>' . formato_fechas_server($row["fechaInicial"], 'd/m/Y H:i') . '</td>
            <td>' . $fechaFinalFormato . '</td>
            <td>' . $estado . '</td>
            <td>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal_eliminar_asignacion" data-id="' . $id . '" ' . $hide . '>
                    <i class="fas fa-trash"></i>
                </button>
                <button type="button" class="btn btn-primary" onclick="edit_asignacion(' . $id . ', `' . $estadoAsignacion . '`)" ' . $hide . '>
                    <i class="fas fa-edit"></i>
                </button>';

                $resultado .= '
            </td>
            </tr>';
            }
        }
    } else {
        while ($row = sqlsrv_fetch_array($query)) {
            $fechaFinalFormato = formato_fechas_server($row['fechaFinal'], 'd/m/Y H:i');
            if ($row['estadoAsignacion'] == 'VENCIDO' && $row['estado'] == 'ASIGNADO') {
                $claseEstado = "alerta-tabla";
                $estadoAsignacion = 'VENCIDO';
            } else {
                $claseEstado = "";
                $estadoAsignacion = 'VIGENTE';
            }
            $estado = $row['estado'];
            if ($estado == "DEVUELTO") {
                $estado = "<span class='badge badge-warning'>DEVUELTO</span>";
            } else {
                $estado = "<span class='badge badge-success'>ASIGNADO</span>";
            }
            $nombreUsuario = $row['nombre'] . " " . $row['apellidoPaterno'] . " " . $row['apellidoMaterno'];
            $id = $row['idAsignacion'];
            $expand = "expand";
            $sector = "sector" . $id;
            $url = "";
            $otro = "
            <div id='sector" . $id . "' class='email' onclick='this.classList.add(\"$expand\")'>
            <div class='from'>
            <div class='from-contents'>
            <div class='avatar me' style='background-image: url($url)'></div>
            <div class='name'>" . $row['producto'] . " - " . $row['codigoBarras'] . "</div>
            </div>
            </div>
            <div class='to'>
            <div class='to-contents'>
            <div class='top'>
            <div class='avatar-large me' style='background-image: url()'></div>
            <div class='name-large'>" . $row['producto'] . "</div>
            <div class='x-touch' onclick='document.getElementById(\"$sector\").classList.remove(\"$expand\");event.stopPropagation();'>
            <div class='x'>
            <div class='line1'></div>
            <div class='line2'></div>
            </div>
            </div>
            </div>
            <div class='bottom'>
            <div class='row2'>
            <div class='table-responsive'>
            <table style='margin:5px auto; width: 85%; border-collapse: separate;border:hidden;' class='table tdstyle' border='1' >
            <tr>
            <td >Activo</td>
            <td >" . $row["producto"] . "</td>
            </tr>
            <tr>
            <td >Cód. Barras</td>
            <td >" . $row["codigoBarras"] . "</td>
            </tr>
            <tr>
            <td >Usuario</td>
            <td >" . $nombreUsuario . "</td>
            </tr>
            <tr>
            <td >Fecha Inicial</td>
            <td >" . formato_fechas_server($row["fechaInicial"], 'd/m/Y H:i') . "</td>
            </tr>
            <tr>
            <td >Fecha Final</td>
            <td >" . $fechaFinalFormato . "</td>
            </tr>
            <tr>
            <td >Estado</td>
            <td >" . $estado . "</td>
            </tr>
            </table>
            </div>
            </div>
            </div>
            </div>
            </div>
            </div>
            ";


            $resultado .= '<tr style="cursor:pointer" class="' . $claseEstado . '">
            <td>
                <div class="checkbox-container">
                    <input type="checkbox" class="selectItem checkbox-lg" value="' . $id . '" onclick="updateSelectedCount()">
                </div>
            </td>
            <td>' . $otro . '</td>
            <td>' . $row['producto'] . '</td>
            <td>' . $row['codigoBarras'] . '</td>
            <td>' . $nombreUsuario . '</td>
            <td>' . formato_fechas_server($row["fechaInicial"], 'd/m/Y H:i') . '</td>
            <td>' . $fechaFinalFormato . '</td>
            <td>' . $estado . '</td>
            <td>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal_eliminar_asignacion" data-id="' . $id . '" ' . $hide . '>
                    <i class="fas fa-trash"></i>
                </button>
                <button type="button" class="btn btn-primary" onclick="edit_asignacion(' . $id . ', `' . $estadoAsignacion . '`)" ' . $hide . '>
                    <i class="fas fa-edit"></i>
                </button>';

            $resultado .= '
            </td>
            </tr>';
        }
    }

    $resultado .= "
    </table>
    </div>
    ";

    echo $resultado;
}
