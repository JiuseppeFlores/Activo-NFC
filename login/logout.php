<?php

unset($_SESSION['idUsuario']);
unset($_SESSION['nombre']);
unset($_SESSION['idArea']);
unset($_SESSION['idRol']);
header('Location: index.php');
