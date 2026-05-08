<?php

/**
 * Endpoint: POST /api/v1/inventario/registrar.php
 * Descripción: Registra el inventario de un activo asignado.
 * Restricciones: 
 *   - Solo roles 1 y 2.
 *   - Un solo inventario por activo.
 * Referencia lógica: inventario/create.php
 */

require_once "../../../conexion.php";
require_once "../../nucleo/Respuesta.php";
require_once "../../nucleo/Autenticacion.php";

use Api\Nucleo\Respuesta;
use Api\Nucleo\Autenticacion;

// 1. Validar Acceso y Roles (Middleware)
Autenticacion::validarAcceso([1, 2]);

// 2. Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Respuesta::json(null, 405, "Método no permitido. Use POST.");
}

// 3. Obtener y validar parámetros
// Soporta tanto JSON como POST tradicional
$datosInput = json_decode(file_get_contents("php://input"), true) ?? $_POST;

$idActivo = $datosInput['idActivo'] ?? null;
$idUsuarioCreador = $datosInput['idUsuario'] ?? $datosInput['idUsuarioCreador'] ?? null;
$observacion = $datosInput['observacion'] ?? '';

if (!$idActivo || !$idUsuarioCreador) {
    Respuesta::json(null, 400, "Los parámetros 'idActivo' e 'idUsuario' son requeridos.");
}

// 4. Buscar la asignación activa del producto
// Un producto solo puede inventariarse si está actualmente asignado
$sqlAsignacion = "SELECT idAsignacion FROM tblAsignacion WHERE idProducto = ? AND estado = 'ASIGNADO'";
$paramsAsignacion = array($idActivo);
$queryAsignacion = sqlsrv_query($con, $sqlAsignacion, $paramsAsignacion);

if ($queryAsignacion === false) {
    Respuesta::json(null, 500, "Error al consultar la asignación.");
}

if (!sqlsrv_has_rows($queryAsignacion)) {
    Respuesta::json(null, 404, "No se encontró una asignación activa para este activo. No se puede inventariar.");
}

$asignacion = sqlsrv_fetch_array($queryAsignacion, SQLSRV_FETCH_ASSOC);
$idAsignacion = $asignacion['idAsignacion'];

// 5. VALIDACIÓN DE UNICIDAD: El producto no puede ser inventariado por segunda ocasión
// Verificamos si ya existe algún registro de inventario vinculado a este producto
$sqlExiste = "SELECT COUNT(*) as total FROM tblInventario i 
              JOIN tblAsignacion a ON i.idAsignacion = a.idAsignacion 
              WHERE a.idProducto = ?";
$queryExiste = sqlsrv_query($con, $sqlExiste, array($idActivo));
$resultadoExiste = sqlsrv_fetch_array($queryExiste, SQLSRV_FETCH_ASSOC);

if ($resultadoExiste['total'] > 0) {
    Respuesta::json(null, 409, "El activo ya ha sido inventariado previamente y no permite duplicados.");
}

// 6. Realizar el registro del inventario
$fecha = date('Y-m-d H:i:s');
$sqlInsert = "INSERT INTO tblInventario (idAsignacion, observacion, idUsuarioCreador, fecha) VALUES (?, ?, ?, ?)";
$paramsInsert = array($idAsignacion, $observacion, $idUsuarioCreador, $fecha);

$queryInsert = sqlsrv_query($con, $sqlInsert, $paramsInsert);

if ($queryInsert) {
    Respuesta::json([
        'idAsignacion' => $idAsignacion,
        'fecha' => $fecha,
        'estado' => 'REGISTRADO'
    ], 201, "Inventario registrado exitosamente.");
} else {
    // Capturar errores específicos de SQL Server para depuración (opcional)
    $errores = print_r(sqlsrv_errors(), true);
    Respuesta::json(null, 500, "Error al registrar el inventario en la base de datos.");
}
