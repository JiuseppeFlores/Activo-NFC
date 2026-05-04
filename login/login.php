<?php
include('../conexion.php');
date_default_timezone_set('America/La_Paz');
// session_start();
$response = array('status' => 2, 'message' => '');
if (isset($_SESSION['bloqueado']) && $_SESSION['bloqueado'] == true) {
    $ahora = time();
    $tiempoBloqueo = $_SESSION['tiempoBloqueo'];
    if ($ahora < $tiempoBloqueo) {
        // echo $ahora . '<br>';
        // echo $tiempoBloqueo . '<br>';
        // echo 3;
        $tiempoBloqueoFormato = date('H:i', $tiempoBloqueo);
        $response['status'] = 3;
        $response['message'] = "Tu dispositivo fue bloqueado.<br>Vuelve a intentarlo a partir de las $tiempoBloqueoFormato.";
        echo json_encode($response, true);
        exit();
    } else {
        unset($_SESSION['bloqueado']);
        unset($_SESSION['tiempoBloqueo']);
        unset($_SESSION['intentos']);
    }
}
$username = "";
$password = "";
if (isset($_POST['name'])) {
    $username = $_POST['name'];
}
if (isset($_POST['pwd'])) {
    $password = $_POST['pwd'];
}

$array = array($username, $password);

$consulta = "SELECT * FROM tblUsuario
WHERE 
  usuario COLLATE SQL_Latin1_General_CP1_CS_AS = ?
  AND password COLLATE SQL_Latin1_General_CP1_CS_AS = ?";
$ejecutar = sqlsrv_query($con, $consulta, $array);
$row_count = sqlsrv_has_rows($ejecutar);
if ($row_count === false) {
    if (!isset($_SESSION['intentos'])) {
        $_SESSION['intentos'] = 1;
    } else {
        $_SESSION['intentos']++;
    }
    if ($_SESSION['intentos'] >= 3) {
        $_SESSION['bloqueado'] = true;
        $_SESSION['tiempoBloqueo'] = time() + 300;
        // echo 3;
        $tiempoBloqueo = $_SESSION['tiempoBloqueo'];
        $tiempoBloqueoFormato = date('H:i', $tiempoBloqueo);
        $response['status'] = 3;
        $response['message'] = "Tu dispositivo fue bloqueado.<br>Vuelve a intentarlo a partir de las $tiempoBloqueoFormato.";
        echo json_encode($response, true);
        exit();
    }
    // echo 2;
    $intentosRestantes = 3 - $_SESSION['intentos'];
    $response['status'] = 2;
    $response['message'] = "Usuario o Constraseña incorrectos.<br>Intentos restantes: $intentosRestantes";
    echo json_encode($response, true);
} else {
    unset($_SESSION['intentos']);
    unset($_SESSION['bloqueado']);
    unset($_SESSION['tiempoBloqueo']);
    $row = sqlsrv_fetch_array($ejecutar);
    $_SESSION['idUsuario'] = $row['idUsuario'];
    $_SESSION['nombre'] = $row['nombre'];
    $_SESSION['idArea'] = $row['idArea'];
    $_SESSION['idRol'] = $row['idRol'];
    // echo 1;
    $response['status'] = 1;
    $response['message'] = '¡Bienvenido!';
    $response['idUsuario'] = $row['idUsuario'];
    echo json_encode($response, true);
}
