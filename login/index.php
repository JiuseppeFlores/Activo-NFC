<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Activo NFC</title>
    <link rel="stylesheet" href="../js_lib/plugins/tabler/css/tabler.min.css">
    <link rel="stylesheet" href="../js_lib/plugins/tabler/icons/css/tabler-icons.min.css">
</head>

<body class="d-flex flex-column bg-body-tertiary min-vh-100 justify-content-center">
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <div class="avatar avatar-xl bg-primary-subtle text-primary rounded-circle mb-2">
                    <i class="ti ti-nfc fs-1"></i>
                </div>
                <h1 class="h2 m-0 fw-bold">Activo NFC</h1>
                <div class="text-secondary small">Sistema de Control e Inventario de Activos</div>
            </div>
            
            <form class="card card-md login" autocomplete="off">
                <div class="card-body">
                    <h2 class="h2 text-center mb-4">Iniciar Sesión</h2>
                    
                    <div class="mb-3">
                        <label class="form-label required">Usuario</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <i class="ti ti-user"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Ingrese su usuario" id="user_name" required autocomplete="off">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label required">Contraseña</label>
                        <div class="input-group input-group-flat">
                            <span class="input-group-text pe-0">
                                <i class="ti ti-lock"></i>
                            </span>
                            <input type="password" class="form-control ps-2" placeholder="Ingrese su contraseña" id="password" required autocomplete="off">
                            <span class="input-group-text pe-3">
                                <button type="button" class="btn-link text-secondary password-toggle p-0 border-0 bg-transparent" title="Mostrar/Ocultar contraseña">
                                    <i class="ti ti-eye"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-footer">
                        <button type="submit" id="boton-estilo" class="btn btn-primary w-100 submit">
                            <i class="ti ti-login me-2"></i> Ingresar
                        </button>
                    </div>
                    
                    <div class="mensajeLogin mt-2" id="mensaje"></div>
                </div>
            </form>
            
            <div class="text-center text-secondary mt-3 small">
                &copy; <?php echo date('Y'); ?> Activo NFC &bull; Todos los derechos reservados.
            </div>
        </div>
    </div>

    <script src="../js/jquery-3.3.1.js"></script>
    <script src="../js_lib/plugins/tabler/js/tabler.min.js"></script>
    <script src="../js/login.js"></script>
</body>
</html>
                