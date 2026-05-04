<?php
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    include("../conexion.php");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    header("Expires: 0");
    $respuesta = array('status' => 0, 'message' => 'No tiene acceso a esta parte del sistema.');
    $ci = $_GET['ci'] ?? '';
    $sql = "SELECT ta.idAsignacion, tp.producto, tp.codigoBarras FROM tblAsignacion ta LEFT JOIN tblUsuario tu ON tu.idUsuario = ta.idUsuario LEFT JOIN tblProducto tp ON tp.idProducto = ta.idProducto WHERE ta.estado = 'ASIGNADO' AND tu.ci = '$ci' ORDER BY tp.producto ASC;";
    $query = sqlsrv_query($con, $sql);
    if ($query) {
        if (sqlsrv_has_rows($query) === false) {
            $respuesta['status'] = 0;
            $respuesta['message'] = 'No se encontraron resultados.';
            echo json_encode($respuesta);
            exit();
        }
        // $row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
        while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            $listaBien[] = $row;
        }
        $respuesta['status'] = 1;
        $respuesta['data'] = $listaBien;
        $respuesta['message'] = 'Producto encontrado.';
        echo json_encode($respuesta);
        exit();
    } else {
        $respuesta['status'] = 0;
        $respuesta['message'] = 'Error al ejecutar la consulta: ' . print_r(sqlsrv_errors(), true);
        echo json_encode($respuesta);
        exit();
    }
    echo json_encode($listaBien);
} else {
    echo "No tiene acceso a esta parte del sistema.";
}
