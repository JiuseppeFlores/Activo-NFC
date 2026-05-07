<?php

/**
 * Endpoint: POST /api/v1/activos/asignar_nfc.php
 * Descripción: Actualiza el campo uidTag de un activo a través de su ID.
 */

require_once "../../../conexion.php";
require_once "../../nucleo/Respuesta.php";
require_once "../../nucleo/Autenticacion.php";

use Api\Nucleo\Respuesta;
use Api\Nucleo\Autenticacion;

// 1. Validar Acceso y Roles (Middleware)
// Solo roles 1 (Admin) y 2 (Operador)
$usuario_token = Autenticacion::validarAcceso([1, 2]);

// 2. Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Respuesta::json(null, 405, "Método no permitido. Use POST.");
}

// 3. Obtener datos
// Aceptamos tanto POST normal como JSON
$datos_post = json_decode(file_get_contents("php://input"), true) ?? $_POST;

$idActivo = $datos_post['idActivo'] ?? $datos_post['id'] ?? null;
$uidTag = $datos_post['uidTag'] ?? null;

if (!$idActivo || !$uidTag) {
    Respuesta::json(null, 400, "Los parámetros 'idActivo' y 'uidTag' son requeridos.");
}

// 4. Validar unicidad del código NFC (Evitar duplicados en otros activos)
$sql_unicidad = "SELECT idProducto, producto FROM tblProducto WHERE uidTag = ? AND idProducto != ?";
$params_unicidad = array($uidTag, $idActivo);
$query_unicidad = sqlsrv_query($con, $sql_unicidad, $params_unicidad);

if ($query_unicidad && sqlsrv_has_rows($query_unicidad)) {
    $otro_activo = sqlsrv_fetch_array($query_unicidad, SQLSRV_FETCH_ASSOC);
    $nombre_otro = $otro_activo['producto'];
    Respuesta::json(null, 409, "El código NFC ya está asignado a otro activo");
}

// 5. Actualizar en la base de datos (tblProducto)
$sql = "UPDATE tblProducto SET uidTag = ? WHERE idProducto = ?";
$parametros = array($uidTag, $idActivo);
$consulta = sqlsrv_query($con, $sql, $parametros);

if ($consulta === false) {
    Respuesta::json(null, 500, "Error al actualizar el activo en la base de datos.");
}

// 5. Verificar si se actualizó algo
$filas_afectadas = sqlsrv_rows_affected($consulta);

if ($filas_afectadas > 0) {
    Respuesta::json([
        "idActivo" => $idActivo,
        "uidTag" => $uidTag,
        "mensaje" => "Código NFC asignado correctamente."
    ], 200);
} else {
    // Si filas_afectadas es 0, puede ser que el ID no exista o que el valor sea el mismo
    // Primero verificamos si el activo existe
    $sql_check = "SELECT idProducto FROM tblProducto WHERE idProducto = ?";
    $query_check = sqlsrv_query($con, $sql_check, array($idActivo));
    
    if ($query_check && sqlsrv_has_rows($query_check)) {
        Respuesta::json(null, 200, "El activo ya tiene asignado ese código NFC o no hubo cambios.");
    } else {
        Respuesta::json(null, 404, "No se encontró ningún activo con el ID proporcionado ($idActivo).");
    }
}
