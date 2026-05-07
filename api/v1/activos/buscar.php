<?php

/**
 * Endpoint: GET /api/v1/activos/buscar.php?codigo={codigo}&tipo={barras|nfc}
 * Descripción: Busca un activo para inventario por su código de barras o UID NFC.
 * Referencia lógica: producto/get_bien.php
 */

require_once "../../../conexion.php";
require_once "../../nucleo/Respuesta.php";
require_once "../../nucleo/Autenticacion.php";

use Api\Nucleo\Respuesta;
use Api\Nucleo\Autenticacion;

// 1. Validar Acceso y Roles (Middleware)
Autenticacion::validarAcceso([1, 2]);

// 2. Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    Respuesta::json(null, 405, "Método no permitido. Use GET.");
}

// 3. Obtener parámetros
$codigo = $_GET['codigo'] ?? $_GET['codigoBarras'] ?? $_GET['uidTag'] ?? null;
$tipo = $_GET['tipo'] ?? null; // 'barras' o 'nfc'

if (!$codigo) {
    Respuesta::json(null, 400, "El parámetro 'codigo' es requerido.");
}

// 4. Construir consulta (Basada en get_bien.php)
$sql = "SELECT 
            tp.idProducto, 
            tp.producto, 
            tp.codigoBarras, 
            tp.uidTag,
            tp.estado AS estadoActivo,
            tp.valoracion,
            ISNULL(tas.idAsignacion, 0) AS idAsignacion, 
            tas.fechaInicial, 
            tas.fechaFinal, 
            tu.nombre, 
            tu.apellidoPaterno, 
            tu.apellidoMaterno, 
            tu.ci, 
            tu.idRol, 
            tu.idArea 
        FROM tblProducto tp 
        LEFT JOIN tblAsignacion tas ON tp.idProducto = tas.idProducto AND tas.estado = 'ASIGNADO'
        LEFT JOIN tblUsuario tu ON tas.idUsuario = tu.idUsuario 
        WHERE ";

if ($tipo === 'nfc' || isset($_GET['uidTag'])) {
    $sql .= "tp.uidTag = ?";
} else {
    $sql .= "tp.codigoBarras = ?";
}

$parametros = array($codigo);
$consulta = sqlsrv_query($con, $sql, $parametros);

if ($consulta === false) {
    Respuesta::json(null, 500, "Error en la base de datos.");
}

// 5. Procesar resultado
if (sqlsrv_has_rows($consulta)) {
    $activo = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC);
    
    // VALIDACIÓN: El activo debe estar asignado para poder inventariarlo
    if (intval($activo['idAsignacion']) === 0) {
        Respuesta::json(null, 403, "El activo se encuentra registrado pero no tiene una asignación vigente.");
    }
    
    // Formatear fechas
    foreach ($activo as $llave => $valor) {
        if ($valor instanceof DateTime) {
            $activo[$llave] = $valor->format('Y-m-d H:i:s');
        }
    }
    
    Respuesta::json($activo);
} else {
    Respuesta::json(null, 404, "No se encontró ningún activo con el código proporcionado.");
}
