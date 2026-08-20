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
        echo "<div class='empty py-4'><div class='empty-icon'><i class='ti ti-chart-line icon-lg text-secondary'></i></div><p class='empty-title'>No hay parámetros de depreciación</p><p class='empty-subtitle text-secondary'>No se encontraron resultados para esta búsqueda.</p></div>";
    } else {
        $resultado = '
        <div class="table-responsive">
        <table class="table table-vcenter card-table table-hover text-center">
        <thead>
        <tr>
        <th class="w-1">ID</th>
        <th>Activo</th>
        <th>Vida útil (años)</th>
        <th>Coeficiente (%)</th>
        <th>Estado</th>
        <th class="w-1">Opciones</th>
        </tr>
        </thead>
        <tbody>';
        $t = time();
        foreach ($listaDepreciacion as $row) {
            $id = $row['idDepreciacion'];
            $estado = $row['estado'];
            if ($estado == 1) {
                $row['estado'] = "Activo";
            } else {
                $row['estado'] = "Inactivo";
            }
            $resultado .= '<tr onclick="listarDetalleDepreciacion(' . $id . ')">
            <td><span class="text-secondary fw-medium">#' . $id . '</span></td>
            <td>' . $row['bien'] . '</td>
            <td>' . $row['vidaUtil'] . '</td>
            <td>' . $row['coeficiente'] * 100 . '</td>';
            if ($row['estado'] == 'Activo') {
                $resultado .= '<td><span class="badge bg-success-subtle text-success">' . $row['estado'] . '</span></td>';
            } else {
                $resultado .= '<td><span class="badge bg-secondary-subtle text-secondary">' . $row['estado'] . '</span></td>';
            }
            $resultado .= '
            <td>
            <div class="dropdown">
            <button type="button" class="btn btn-ghost-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Acciones">
            <i class="ti ti-settings-2 me-2"></i>Acciones
            </button>
            <div class="dropdown-menu dropdown-menu-end">
            <a class="dropdown-item" href="#" onclick="edit_depreciacion(\'' . $row['idDepreciacion'] . '\'); return false;" ' . $hide . '><i class="ti ti-pencil me-2"></i>Editar</a>
            </div>
            </div>
            </td>
            </tr>';

            $resultado .= '
            <tr id="details-' . $id . '" style="display:none">
            <td></td>
            <td colspan="2">
            <div class="card" style="max-height: 150px; overflow-y: auto;">
            <div class="card-body p-2">
            <table border="0" cellpadding="0" cellspacing="0" class="table table-sm mb-0">';
            if (isset($row['detalle'])) {
                foreach ($row['detalle'] as $detalle) {
                    $resultado .= '<tr>
                    <td class="text-start py-1 px-3" colspan="6">' . $detalle['bienDetalle'] . '</td>
                    </tr>';
                }
            } else {
                $resultado .= '<tr>
                <td colspan="6" class="text-secondary">No hay detalles</td>
                </tr>';
            }
            $resultado .= '</table>
            </div>
            </div>
            </td>
            <td colspan="3"></td>
            </tr>';
        }
        $resultado .= "</tbody></table></div>";
        echo $resultado;
    }
} else {
    echo "No tiene acceso a esta parte del sistema.";
}
