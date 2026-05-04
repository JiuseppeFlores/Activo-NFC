<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include("../conexion.php");
    $respuesta = array();
    // para el total de bienes
    $sql = "SELECT COUNT(DISTINCT tblProducto.idProducto) as total FROM tblProducto;";
    $query = sqlsrv_query($con, $sql);
    $row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $respuesta['totalBienes'] = $row['total'];
    // para el total de bienes asignados
    $sql = "SELECT COUNT(DISTINCT tblAsignacion.idProducto) as total FROM tblAsignacion WHERE estado = 'ASIGNADO';";
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
    $sql = "SELECT ta.area, COUNT(*) as total FROM tblArea ta LEFT JOIN tblUsuario tu ON ta.idArea = tu.idArea LEFT JOIN tblAsignacion tas ON tu.idUsuario = tas.idUsuario WHERE tas.idProducto IS NOT NULL GROUP BY ta.area;";
    $query = sqlsrv_query($con, $sql);
    while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $listaAreaAsignaciones[] = $row;
    }
    $respuesta['listaAreaAsignaciones'] = $listaAreaAsignaciones;
    // para el tiempo restante de vida util
    $listaTiempoRestante = array();
    $listaBienesDepreciados = array();
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
    $respuesta['listaTiempoRestante'] = $listaTiempoRestante;
    $respuesta['listaBienesDepreciados'] = $listaBienesDepreciados;
    echo json_encode($respuesta);
} else {
    echo "No tiene acceso a esta página";
}