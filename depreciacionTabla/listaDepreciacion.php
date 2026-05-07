<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    include("../conexion.php");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    header("Expires: 0");
    $idRol = $_SESSION['idRol'];
    $hide = '';
    if ($idRol != 1) {
      $hide = 'hidden';
    }
    $start_from = $_POST['start'];
    $search_in_sql = "";
    if (isset($_POST['texto']) && !empty($_POST['texto'])) {
        $texto = $_POST['texto'];
        $search_in_sql .= " WHERE (bien like '%" . $texto . "%' ) ";
    }
    $listaDepreciacion = array();
    $sql = "SELECT * FROM tblDepreciacion $search_in_sql ORDER BY idDepreciacion ASC offset $start_from ROWS FETCH NEXT 10 ROWS ONLY;";
    $query = sqlsrv_query($con, $sql);
    if ($query) {
        while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            $listaDepreciacion[$row['idDepreciacion']] = $row;
        }
        $sqlDetalle = "SELECT * FROM tblDepreciacionDetalle ORDER BY bienDetalle ASC;";
        $queryDetalle = sqlsrv_query($con, $sqlDetalle);
        if ($queryDetalle) {
            while($rowDetalle = sqlsrv_fetch_array($queryDetalle, SQLSRV_FETCH_ASSOC)) {
                if (isset($listaDepreciacion[$rowDetalle['idDepreciacion']])) {
                    $listaDepreciacion[$rowDetalle['idDepreciacion']]['detalle'][] = $rowDetalle;
                }
            }
        }
    }
    // $count_row = sqlsrv_has_rows($query);
    if (count($listaDepreciacion) === 0) {
        echo "<div style='text-align:center'><h2>¡Lista de Depreciación vacía!</h2></div>";
    } else {
        $resultado = '
        <div class="table-responsive">
        <table style="text-align:center" class="table table-hover">
        <tr>
        <th>
        Información
        </th>
        <th>
        Activo
        </th>
        <th>
        Vida útil (años)
        </th>
        <th>
        Coeficiente (%)
        </th>
        <th>
        Estado
        </th>
        <th>
        Opciones
        </th>
        </tr>';
        $t = time();
        // print_r($listaDepreciacion);
        foreach ($listaDepreciacion as $row) {
            $id = $row['idDepreciacion'];
            $estado = $row['estado'];
            if ($estado == 1) {
                $row['estado'] = "Activo";
            } else {
                $row['estado'] = "Inactivo";
            }
            $expand = 'expand';
            $sector = 'sector' . $id;
            $url = "";
            $otro = "
            <div id='sector" . $id . "' class='email' onclick='this.classList.add(\"$expand\")'>
            <div class='from'>
            <div class='from-contents'>
            <div class='avatar me' style='background-image: url($url)'></div>
            <div class='name'>" . $row['bien'] . "
            </div>
            </div>
            </div>
            <div class='to'>
            <div class='to-contents'>
            <div class='top'>
            <div class='avatar-large me' style='background-image: url()'>            
            </div>
            <div class='name-large'>" . $row['bien'] . "
            </div>
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
            <td >" . $row["bien"] . "</td>
            </tr>
            <tr>
            <td >Vida útil</td>
            <td >" . $row["vidaUtil"] . "</td>
            </tr>
            <tr>
            <td >Coeficiente</td>
            <td >" . $row["coeficiente"] . "</td>
            </tr>
            </table>
            </div>
            </div>
            </div>
            </div>
            </div>
            </div>";

            $resultado .= '<tr style="cursor:pointer" onclick="listarDetalleDepreciacion(' . $id . ')">
            <td>' . $otro . '</td>
            <td>' . $row['bien'] . '</td>
            <td>' . $row['vidaUtil'] . '</td>
            <td>' . $row['coeficiente'] * 100 . '</td>';
            if ($row['estado'] == 'Activo') {
                $resultado .= '<td><label class="bg-success text-white p-1 rounded">' . $row['estado'] . '</label></td>';
            } else {
                $resultado .= '<td> ' . $row['estado'] . '</td>';
            }
            $resultado .= '
            <td>
            <button class="btn btn-primary" onclick="edit_depreciacion(\'' . $row['idDepreciacion'] . '\')" ' . $hide . '> <i class="fas fa-edit"></i></button>
            </td>
            </tr>';

            $resultado .= '
            <tr id="details-' . $id . '" style="display:none">
            <td></td>
            <td colspan="2">
            <div class="card" style="max-height: 150px; overflow-y: auto;">
            <div class="card-body">
            <table border="0" cellpadding="0" cellspacing="0" class="table">';
            if (isset($row['detalle'])) {
                foreach ($row['detalle'] as $detalle) {
                    $resultado .= '<tr>
                    <td style="text-align:left; padding-top:0.25rem !important; padding-bottom:0.25rem !important; padding-left:1.25rem !important; padding-right:0.5rem !important;" colspan="6">' . $detalle['bienDetalle'] . '</td>
                    </tr>';
                }
            } else {
                $resultado .= '<tr>
                <td colspan="6">No hay detalles</td>
                </tr>';
            }
            $resultado .= '</table>
            </div>
            </div>
            </td>
            <td colspan="3"></td>
            </tr>';
        }
        $resultado .= "</table></div>";
        echo $resultado;
    }
} else {
    echo "No tiene acceso a esta parte del sistema.";
}
