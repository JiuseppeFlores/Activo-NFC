<?php

namespace Api\Nucleo;

use Api\Nucleo\Respuesta;

/**
 * Clase Autenticacion
 * Maneja la generación y validación de tokens JWT simples.
 */
class Autenticacion {
    // Clave secreta para firmar los tokens. 
    // En un entorno real, esto debería estar en un archivo de configuración no accesible.
    private static $clave_secreta = "ActivosNFC_Secret_Key_2024_Safe_!!!";

    /**
     * Genera un token JWT para un usuario.
     */
    public static function generarToken($datos_usuario) {
        $cabecera = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        
        $tiempo_actual = time();
        $carga_util = json_encode([
            'sub'  => $datos_usuario['idUsuario'],
            'nom'  => $datos_usuario['nombre'],
            'rol'  => $datos_usuario['idRol'],
            'iat'  => $tiempo_actual,
            'exp'  => $tiempo_actual + (60 * 60 * 24) // Expira en 24 horas
        ]);

        $base64_cabecera = self::base64UrlEncode($cabecera);
        $base64_carga = self::base64UrlEncode($carga_util);

        $firma = hash_hmac('sha256', $base64_cabecera . "." . $base64_carga, self::$clave_secreta, true);
        $base64_firma = self::base64UrlEncode($firma);

        return $base64_cabecera . "." . $base64_carga . "." . $base64_firma;
    }

    /**
     * Valida un token JWT.
     */
    public static function validarToken($token) {
        $partes = explode('.', $token);
        if (count($partes) !== 3) return false;

        list($base64_cabecera, $base64_carga, $base64_firma) = $partes;

        $firma_esperada = hash_hmac('sha256', $base64_cabecera . "." . $base64_carga, self::$clave_secreta, true);
        $base64_firma_esperada = self::base64UrlEncode($firma_esperada);

        if ($base64_firma !== $base64_firma_esperada) return false;

        $carga = json_decode(self::base64UrlDecode($base64_carga), true);
        
        // Verificar expiración
        if (isset($carga['exp']) && $carga['exp'] < time()) return false;

        return $carga;
    }

    /**
     * Valida el acceso mediante el token enviado en los encabezados.
     * Si el token es inválido, no existe o no tiene el rol necesario, termina la ejecución.
     * 
     * @param array $roles_permitidos Lista opcional de IDs de roles que tienen acceso.
     */
    public static function validarAcceso($roles_permitidos = []) {
        $cabecera = null;
        
        // Buscar el token en los encabezados
        if (isset($_SERVER['Authorization'])) {
            $cabecera = $_SERVER['Authorization'];
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $cabecera = $_SERVER['HTTP_AUTHORIZATION'];
        } else {
            $headers = apache_request_headers();
            if (isset($headers['Authorization'])) {
                $cabecera = $headers['Authorization'];
            }
        }

        if (!$cabecera) {
            Respuesta::json(null, 401, "Token de acceso no proporcionado.");
        }

        $token = str_replace('Bearer ', '', $cabecera);
        $datos = self::validarToken($token);

        if (!$datos) {
            Respuesta::json(null, 401, "Token inválido o expirado.");
        }

        // Validación de Roles (Middleware de Autorización)
        if (!empty($roles_permitidos)) {
            if (!isset($datos['rol']) || !in_array($datos['rol'], $roles_permitidos)) {
                Respuesta::json(null, 403, "No tiene permisos suficientes para realizar esta acción.");
            }
        }

        return $datos; // Devuelve los datos del usuario decodificados
    }

    private static function base64UrlEncode($datos) {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($datos));
    }

    private static function base64UrlDecode($datos) {
        $relleno = strlen($datos) % 4;
        if ($relleno) $datos .= str_repeat('=', 4 - $relleno);
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $datos));
    }
}
