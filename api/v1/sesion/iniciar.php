<?php

/**
 * Endpoint: POST /api/v1/sesion/iniciar.php
 * Descripción: Inicia sesión y devuelve un token JWT.
 * Referencia lógica: login/login.php
 */

require_once "../../../conexion.php";
require_once "../../nucleo/Respuesta.php";
require_once "../../nucleo/Autenticacion.php";

use Api\Nucleo\Respuesta;
use Api\Nucleo\Autenticacion;

date_default_timezone_set('America/La_Paz');

// 1. Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Respuesta::json(null, 405, "Método no permitido. Use POST.");
}

// 2. Control de bloqueos (Misma lógica que login.php)
if (isset($_SESSION['bloqueado']) && $_SESSION['bloqueado'] == true) {
    $ahora = time();
    $tiempoBloqueo = $_SESSION['tiempoBloqueo'];
    
    if ($ahora < $tiempoBloqueo) {
        $tiempoBloqueoFormato = date('H:i', $tiempoBloqueo);
        Respuesta::json(null, 403, "Dispositivo bloqueado. Intente después de las $tiempoBloqueoFormato.");
    } else {
        unset($_SESSION['bloqueado'], $_SESSION['tiempoBloqueo'], $_SESSION['intentos']);
    }
}

// 3. Obtener credenciales
// Para APIs es mejor usar JSON en el body, pero para ser coherentes con login.php aceptaremos $_POST
$usuario = $_POST['usuario'] ?? $_POST['name'] ?? null;
$password = $_POST['password'] ?? $_POST['pwd'] ?? null;

if (!$usuario || !$password) {
    Respuesta::json(null, 400, "Usuario y contraseña son requeridos.");
}

// 4. Validar credenciales (SQL de login.php)
$sql = "SELECT * FROM tblUsuario 
        WHERE usuario COLLATE SQL_Latin1_General_CP1_CS_AS = ? 
        AND password COLLATE SQL_Latin1_General_CP1_CS_AS = ?";

$parametros = array($usuario, $password);
$consulta = sqlsrv_query($con, $sql, $parametros);

if ($consulta === false) {
    Respuesta::json(null, 500, "Error en la base de datos.");
}

// 5. Manejo de resultados e intentos
if (sqlsrv_has_rows($consulta) === false) {
    // Incrementar intentos
    $_SESSION['intentos'] = ($_SESSION['intentos'] ?? 0) + 1;
    
    if ($_SESSION['intentos'] >= 3) {
        $_SESSION['bloqueado'] = true;
        $_SESSION['tiempoBloqueo'] = time() + 300; // 5 minutos
        $tiempoBloqueoFormato = date('H:i', $_SESSION['tiempoBloqueo']);
        Respuesta::json(null, 403, "Demasiados intentos. Bloqueado hasta las $tiempoBloqueoFormato.");
    }
    
    $restantes = 3 - $_SESSION['intentos'];
    Respuesta::json(null, 401, "Credenciales incorrectas. Intentos restantes: $restantes");
} else {
    // Éxito
    $datos_usuario = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC);
    
    // Limpiar rastro de intentos
    unset($_SESSION['intentos'], $_SESSION['bloqueado'], $_SESSION['tiempoBloqueo']);
    
    // Generar Token JWT (Sin tocar la BD)
    $token = Autenticacion::generarToken($datos_usuario);
    
    // Devolver datos mínimos y el token
    $respuesta = [
        'token' => $token,
        'usuario' => [
            'idUsuario' => $datos_usuario['idUsuario'],
            'nombre'    => $datos_usuario['nombre'],
            'idRol'     => $datos_usuario['idRol'],
            'idArea'    => $datos_usuario['idArea']
        ]
    ];
    
    Respuesta::json($respuesta, 200);
}
