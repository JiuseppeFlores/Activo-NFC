<?php

/**
 * Endpoint: GET /api/v1/asignaciones/buscar_por_usuario.php?ci={ci}
 * Descripción: Obtiene la lista de activos asignados a un usuario a través de su CI.
 * Referencia lógica: usuario/get_asignaciones.php
 */

require_once "../../../conexion.php";
require_once "../../nucleo/Respuesta.php";
require_once "../../nucleo/Autenticacion.php";

use Api\Nucleo\Respuesta;
use Api\Nucleo\Autenticacion;

// 1. Validar Acceso (Cualquier rol puede acceder)
Autenticacion::validarAcceso();

// 2. Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    Respuesta::json(null, 405, "Método no permitido. Use GET.");
}

// 3. Obtener parámetro CI
$ci = $_GET['ci'] ?? null;

if (!$ci) {
    Respuesta::json(null, 400, "El parámetro 'ci' es requerido.");
}

// 4. Consultar BD
$sql = "SELECT 
            ta.idAsignacion, 
            tp.idProducto,
            tp.producto, 
            tp.codigoBarras,
            tp.uidTag,
            tp.estado AS estadoActivo,
            tp.valoracion,
            ta.fechaInicial,
            ta.fechaFinal
        FROM tblAsignacion ta 
        INNER JOIN tblUsuario tu ON tu.idUsuario = ta.idUsuario 
        INNER JOIN tblProducto tp ON tp.idProducto = ta.idProducto 
        WHERE ta.estado = 'ASIGNADO' 
          AND tu.ci = ? 
        ORDER BY tp.producto ASC";

$parametros = array($ci);
$consulta = sqlsrv_query($con, $sql, $parametros);

if ($consulta === false) {
    Respuesta::json(null, 500, "Error en la base de datos.");
}

// 5. Procesar resultados
$asignaciones = [];
while ($fila = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
    // Formatear fechas
    foreach ($fila as $llave => $valor) {
        if ($valor instanceof DateTime) {
            $fila[$llave] = $valor->format('Y-m-d H:i:s');
        }
    }
    $asignaciones[] = $fila;
}

if (empty($asignaciones)) {
    Respuesta::json([], 200, "No se encontraron activos asignados para el CI: $ci");
} else {
    Respuesta::json($asignaciones, 200);
}
