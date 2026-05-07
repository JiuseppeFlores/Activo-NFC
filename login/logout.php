<?php
session_start();
unset($_SESSION['idUsuario']);
unset($_SESSION['nombre']);
unset($_SESSION['idArea']);
unset($_SESSION['idRol']);
session_unset();
session_destroy();
header('Location: index.php');
exit();
