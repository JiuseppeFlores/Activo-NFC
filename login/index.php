<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../fontawesome/css/fontawesome.min.css">
    <link href="../fontawesome/css/all.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body class="bodyLogin">
    <form class="login">
        <div class="loginContainer">
            <fieldset class="fieldset">
                <h3 class="legend">Iniciar Sesión</h3>
                <div class="input">
                    <input type="text" placeholder="Usuario" id="user_name" required />
                    <span><i class="fa fa-user" style="color:rgb(81, 122, 184);"></i></span>
                </div>
                <div class="input">
                    <input type="password" placeholder="Password" id="password" required />
                    <span><i class="fa fa-lock" style="color:rgb(81, 122, 184);"></i></span>
                    <button type="button" class="password-toggle">
                        <i class="fa fa-eye" style="color:rgb(81, 122, 184);"></i>
                    </button>
                </div>
                <button type="submit" id="boton-estilo" class="submit"><i class="fa fa-arrow-right"></i></button>
            </fieldset>
        </div>
        <div class="mensajeLogin" id="mensaje"></div>
    </form>
</body>
<script defer src="../fontawesome/js/all.js"></script>
<script src="../fontawesome/js/fontawesome.js"></script>
<script type="text/javascript" charset="utf8" src="../js/jquery-3.3.1.js"></script>
<script src="../js/login.js"></script>
<script src="../js/bootstrap.min.js"></script>
</html>
                