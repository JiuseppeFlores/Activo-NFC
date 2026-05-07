<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include("../conexion.php");
    $idRol = $_SESSION['idRol'];
    if ($idRol == 3) {
        $idUsuario = $_SESSION['idUsuario'];
    }
    $whereUsuario = '';
    if (isset($idUsuario)) {
        $whereUsuario = "AND ta.idUsuario = $idUsuario";
    }

    $respuesta = array();
    // para el total de bienes
    $sql = "SELECT COUNT(DISTINCT tblProducto.idProducto) as total FROM tblProducto;";
    $query = sqlsrv_query($con, $sql);
    $row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $respuesta['totalBienes'] = $row['total'];
    // para el total de bienes asignados
    $sql = "SELECT COUNT(DISTINCT ta.idProducto) as total FROM tblAsignacion ta WHERE ta.estado = 'ASIGNADO' $whereUsuario;";
    $query = sqlsrv_query($con, $sql);
    $row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $respuesta['totalBienesAsignados'] = $row['total'];
    // para el total de bienes no asignados
    $sql = "SELECT COUNT(DISTINCT tblProducto.idProducto) as total FROM tblProducto LEFT JOIN tblAsignacion ON tblProducto.idProducto = tblAsignacion.idProducto WHERE tblAsignacion.idProducto IS NULL OR tblAsignacion.estado = 'DEVUELTO';";
    $query = sqlsrv_query($con, $sql);
    $row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $respuesta['totalBienesNoAsignados'] = $row['total'];
    // paar la distribucion por area
    $listaAreaAsignaciones = array();
    $sql = "SELECT tar.area, COUNT(*) as total FROM tblArea tar LEFT JOIN tblUsuario tu ON tar.idArea = tu.idArea LEFT JOIN tblAsignacion ta ON tu.idUsuario = ta.idUsuario WHERE ta.idProducto IS NOT NULL $whereUsuario GROUP BY tar.area;";
    $query = sqlsrv_query($con, $sql);
    while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $listaAreaAsignaciones[] = $row;
    }
    $respuesta['listaAreaAsignaciones'] = $listaAreaAsignaciones;
    // para el tiempo restante de vida util
    $listaTiempoRestante = array();
    $listaBienesDepreciados = array();
    if ($idRol != 3) {
        $sql = "SELECT tp.idProducto, tp.producto, YEAR(tp.fechaIngreso) gestion, tp.costoAdquisicion, td.coeficiente, td.vidaUtil, (td.vidaUtil - DATEDIFF(YEAR, tp.fechaIngreso, GETDATE())) as tiempoRestante FROM tblProducto tp LEFT JOIN tblDepreciacion td ON td.idDepreciacion = tp.idDepreciacion WHERE tp.idProducto IS NOT NULL AND tp.idDepreciacion IS NOT NULL ORDER BY tiempoRestante ASC;";
        $query = sqlsrv_query($con, $sql);
        while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            if ($row['tiempoRestante'] < 1) {
                $listaBienesDepreciados[] = $row;
            }
            $row['tiempoRestante'] = $row['tiempoRestante'] < 1 ? 0 : $row['tiempoRestante'];
            $porcentajeUtilRestante = 100 - ($row['tiempoRestante'] / $row['vidaUtil'] * 100);
            $row['porcentajeUtilRestante'] = $porcentajeUtilRestante;
            $listaTiempoRestante[] = $row;
        }
    } else {
        $sql = "SELECT tp.idProducto, tp.producto, YEAR(tp.fechaIngreso) gestion, tp.costoAdquisicion, td.coeficiente, td.vidaUtil, (td.vidaUtil - DATEDIFF(YEAR, tp.fechaIngreso, GETDATE())) as tiempoRestante FROM tblAsignacion ta LEFT JOIN tblProducto tp ON ta.idProducto = tp.idProducto LEFT JOIN tblDepreciacion td ON td.idDepreciacion = tp.idDepreciacion WHERE tp.idProducto IS NOT NULL AND tp.idDepreciacion IS NOT NULL $whereUsuario ORDER BY tiempoRestante ASC;";
        $query = sqlsrv_query($con, $sql);
        while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            if ($row['tiempoRestante'] < 1) {
                $listaBienesDepreciados[] = $row;
            }
            $row['tiempoRestante'] = $row['tiempoRestante'] < 1 ? 0 : $row['tiempoRestante'];
            $porcentajeUtilRestante = 100 - ($row['tiempoRestante'] / $row['vidaUtil'] * 100);
            $row['porcentajeUtilRestante'] = $porcentajeUtilRestante;
            $listaTiempoRestante[] = $row;
        }
    }
    $respuesta['listaTiempoRestante'] = $listaTiempoRestante;
    $respuesta['listaBienesDepreciados'] = $listaBienesDepreciados;
    echo json_encode($respuesta);
} else {
    echo "No tiene acceso a esta página";
}