<?php

/**
 * Endpoint: GET /api/v1/activos/obtener.php?id={id}
 * Descripción: Obtiene los datos de un ACTIVO a través de su ID, incluyendo estado de inventario.
 */

// 1. Incluir dependencias
require_once "../../../conexion.php";
require_once "../../nucleo/Respuesta.php";
require_once "../../nucleo/Autenticacion.php";

use Api\Nucleo\Respuesta;
use Api\Nucleo\Autenticacion;

// 0. Validar Acceso y Roles (Middleware)
Autenticacion::validarAcceso([1, 2]);

// 2. Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    Respuesta::json(null, 405, "Método no permitido. Use GET.");
}

// 3. Obtener ID
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$id) {
    Respuesta::json(null, 400, "El parámetro 'id' es requerido.");
}

// 4. Consultar BD
$sql = "SELECT * FROM tblProducto WHERE idProducto = ?";
$parametros = array($id);
$consulta = sqlsrv_query($con, $sql, $parametros);

if ($consulta === false) {
    Respuesta::json(null, 500, "Error en la base de datos.");
}

// 5. Resultado
if (sqlsrv_has_rows($consulta)) {
    $activo = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC);
    
    // Formatear fechas
    foreach ($activo as $llave => $valor) {
        if ($valor instanceof DateTime) {
            $activo[$llave] = $valor->format('Y-m-d H:i:s');
        }
    }
    
    Respuesta::json($activo);
} else {
    Respuesta::json(null, 404, "Activo no encontrado.");
}
