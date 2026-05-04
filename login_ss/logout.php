<?php
session_start();
unset($_SESSION['nombre']);
unset($_SESSION['id']);
header('Location: index.php');
?>
