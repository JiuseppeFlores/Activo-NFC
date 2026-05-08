<?php

namespace Api\Nucleo;

/**
 * Clase para estandarizar las respuestas JSON de la API.
 */
class Respuesta {
    /**
     * Envía una respuesta JSON y termina la ejecución del script.
     *
     * @param mixed $datos Los datos a enviar (array u objeto).
     * @param int $estado El código de estado HTTP (default 200).
     * @param string|null $error Mensaje de error si la petición falló.
     * @return void
     */
    public static function json($datos, $estado = 200, $error = null) {
        // Establecer encabezados para JSON
        header('Content-Type: application/json; charset=utf-8');
        
        // Establecer código de estado HTTP
        http_response_code($estado);
        
        // Estructura estandarizada
        $respuesta = [
            'estado'     => ($estado >= 200 && $estado < 300),
            'datos'     => $datos,
            'error'     => $error,
            'codigo'    => $estado,
            'fecha_hora' => date('c')
        ];
        
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}
