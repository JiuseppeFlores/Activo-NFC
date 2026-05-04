<?php
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    include("../conexion.php");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    header("Expires: 0");
    $respuesta = array('status' => 0, 'message' => 'No tiene acceso a esta parte del sistema.');

    if(isset($_GET['codigoBarras'])){
        $codigoBarras = $_GET['codigoBarras'] ?? '';
        $sql = "SELECT tp.idProducto, tp.producto, tp.codigoBarras, ISNULL(tas.idAsignacion, 0) AS idAsignacion, tas.fechaInicial, tas.fechaFinal, tu.nombre, tu.apellidoPaterno, tu.apellidoMaterno, tu.ci, tu.idRol, tu.idArea FROM tblProducto tp LEFT JOIN tblAsignacion tas ON tp.idProducto = tas.idProducto LEFT JOIN tblUsuario tu ON tas.idUsuario = tu.idUsuario WHERE tas.estado = 'ASIGNADO' AND tp.codigoBarras = '$codigoBarras';";
        $query = sqlsrv_query($con, $sql);
    }else if(isset($_GET['uidTag'])){
        $uidTag = $_GET['uidTag'] ?? '';
        $sql = "SELECT tp.idProducto, tp.producto, tp.codigoBarras, 
                        ISNULL(tas.idAsignacion, 0) AS idAsignacion, 
                        tas.fechaInicial, tas.fechaFinal, tu.nombre, 
                        tu.apellidoPaterno, tu.apellidoMaterno, 
                        tu.ci, tu.idRol, tu.idArea 
                FROM tblProducto tp 
                LEFT JOIN tblAsignacion tas ON tp.idProducto = tas.idProducto 
                LEFT JOIN tblUsuario tu ON tas.idUsuario = tu.idUsuario 
                WHERE tas.estado = 'ASIGNADO' 
                    AND tp.uidTag = '$uidTag';";
        $query = sqlsrv_query($con, $sql);
    }else{
        $respuesta['status'] = 0;
        $respuesta['message'] = 'Debe especificar el tipo de busqueda para el producto';
        echo json_encode($respuesta);
        exit();
    }

    if ($query) {
        if (sqlsrv_has_rows($query) === false) {
            $respuesta['status'] = 0;
            $respuesta['message'] = 'No se encontraron resultados.';
            echo json_encode($respuesta);
            exit();
        }
        $row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
        $respuesta['status'] = 1;
        $respuesta['data'] = $row;
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
