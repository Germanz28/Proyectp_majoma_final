<?php
include "conn.php";
session_start();

if (isset($_SESSION['email'])) {
    header("Location: administrador.php");
    exit();
}
if (isset($_POST['btn-login'])) {
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['pass'] ?? '';

    $login = $conn->prepare("SELECT * FROM user WHERE email = :email LIMIT 1");
    $login->bindParam(':email', $email);
    $login->execute();
    $result = $login->fetch(PDO::FETCH_ASSOC);

    if (is_array($result)) {
        if (password_verify($pass, $result['pass'])) {
            $_SESSION['email'] = $result['email'];
            $_SESSION['id'] = $result['id'];
            $_SESSION['rol'] = $result['rol'];
            $_SESSION['fname'] = $result['fname']; // Guardar el nombre en la sesión
            // Check user role and set session variable accordingly
             if ($result['rol'] == '0') {
            // user role
                header("Location: indeus.php");
            } else {
                // admin role
                header("Location: administrador.php");
            }
            //if ($result['rol'] == 'admin') {
            //$_SESSION['rol'] = $result['rol'];
            // } else {
            //$_SESSION['rol'] = 'user';
            //  }
           // header("Location: index.html");
            //exit();
        } else {
            $msg = array("contraseña incorrectos", "danger");
        }
    } else {
        $msg = array("Correo invalido", "danger");
    }
}
?>
<!DOCTYPE html>
<html lang="es-CO" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Majoma 11</title>
    <!--Logo Favicon-->
    <link rel="shortcut icon" href="../assets/img/logo.png" type="image/x-icon">

    <!--SEO Tags-->
    <meta name="author" content="Majoma">
    <meta name="description" content="Aplicativo web Bootstrap">
    <meta name="keywords" content="PROYECTO, proyecto, Proyecto, Majoma, MAJOMA, majoma">

    <!--Optimization Tags-->
    <meta name="theme-color" content="#000000">
    <meta name="MobileOptimized" content="width">
    <meta name="HandlhledFriendly" content="true">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-traslucent">

    <!--Bootstrap 5.3 Styles and complements-->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/me.styles.css">
    <link rel="stylesheet" href="../assets/css/me.form.css">
    <!--styles Icons Bootstrap-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-login">
    <main class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="card" style="border-radius: 1.5rem; background-color:#ffffffc0;">
            <div class="card-body mx-auto" style="max-width: 400px;">
                <br>
                <!--Alerts-->
                <?php if (isset($msg)) { ?>
                    <div class="alert alert-<?php echo $msg[1]; ?> alert-dismissible">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <strong>Success!</strong> <?php echo $msg[0]; ?>
                    </div>
                <?php } ?>
                <!--Alerts-->
                <span class="float-end fw-bold">
                    <a href="../index.html" aria-label="Atras"><i class="bi bi-x-lg" style="font-size:20px;"></i></a>
                </span>
                <div class="text-center py-2">
                    <h1 class="display-6">Inicio de Sesión</h1>
                </div>
                <form action="" method="post" enctype="application/x-www-form-urlencoded">
                    <div class="input-group">
                        <div class="input-field mb-3 mt-3">
                            <span for="email" class="input-group-text rounded-pill border-0" style="background-color: #ffffff00;"><i class="fa-solid fa-user-tie fa-2xl" style="color: #000000;">:</i></span>
                            <input type="email" class="form-control rounded-pill" id="email" placeholder="Ingrese correo" name="email" required>
                        </div>
                        <div class="input-field mt-3 mb-3">
                            <span for="pass" class="input-group-text rounded-pill border-0" style="background-color: #ffffff00;"><i class="fa-solid fa-lock fa-2xl" style="color: #080808;">:</i></span>

                            <div class="input-group">
                                <input type="password" class="form-control password-input-wrapper rounded-pill" id="pass" name="pass" placeholder="Ingrese contraseña" name="pass" required>
                                <span class="password-input-group-text border-0" onclick="pass_show_hide();">
                                    <i class="bi bi-eye d-none" id="showeye" style="font-size:25px"></i>
                                    <i class="bi bi-eye-slash" id="hideeye" style="font-size:25px"></i>
                                </span>
                            </div>
                        </div>
                        <style>
                            .password-input-group-text {
                                position: absolute;
                                left: 260px;
                            }

                            .card-body h1::after {
                                content: '';
                                width: 320px;
                                height: 2px;
                                border-radius: 5px;
                                background-color: rgb(88, 86, 86);
                                position: absolute;
                                bottom: 330px;
                                left: 10%;
                            }


                            /* Mantener el input-field como flex para alinear la llave y el nuevo wrapper */
                            .input-field {
                                position: relative;
                                /* Puede ser necesario si hay otros elementos que se posicionan */
                                display: flex;
                                align-items: center;
                                width: 100%;
                                /* Asegura que ocupe el ancho completo */
                            }

                            /* Estilo para el icono de la llave */
                            .input-field .input-group-text {
                                margin-right: 5px;
                                /* Espacio entre el icono de la llave y el campo de contraseña */
                                /* Asegúrate de que el background-color:#ffffff00; no esté causando problemas visuales */
                            }

                            /* Nuevo contenedor para el input y el ojo */
                            .password-input-wrapper {
                                position: relative;
                                /* Este es el contenedor para el posicionamiento absoluto del ojo */
                                flex-grow: 1;
                                /* Permite que ocupe el espacio restante en el flexbox */
                                display: flex;
                                /* Para que el input ocupe el 100% del wrapper */
                                align-items: center;
                            }

                            /* Ajusta el padding del input para dejar espacio al ojo */
                            .password-input-wrapper input[type="password"] {
                                padding-right: 40px;
                                /* Deja espacio para el icono del ojo */
                                width: 100%;
                                /* Asegura que el input ocupe todo el ancho del wrapper */
                            }

                            /* Posiciona el icono del ojo */
                        </style>
                        <div class="form-check mb-3 clearfix">
                            <label class="form-check-label w-100 d-flex justify-content-start" style="margin-top: -2px; margin-bottom: -300px; margin-left: 20px;">
                                <input class="form-check-input me-2" type="checkbox" name="remember">Recuerda
                            </label>
                        </div>
                        <div class="col-10 mb-0 d-flex justify-content-left">
                            <div class="form-check mb-0 clearfix w-100 d-flex justify-content-start" style="margin-top: -18px; margin-left: -19px;">
                                <label class="form-check-label">
                                    <button type="button" class="btn btn-link nav-link" style="color: black;" title="inicio_sesion"
                                        onclick="location.href='reg_user.php'">Registrate aqui</button>
                                </label>
                            </div>
                        </div>
                        <div class="col-10 d-flex justify-content-left">
                            <div class="form-check mb-0 clearfix w-100 d-flex justify-content-start" style="margin-top: -8px; margin-left: -20px;">
                                <label class="form-check-label">
                                    <button type="button" class="btn btn-link nav-link" style="color: black;" title="inicio_sesion"
                                        onclick="location.href='forgotpass.php'">¿Olvidaste la contraseña?</button>
                                </label>
                            </div>
                        </div>
                        <div class="col-12 ">
                            <div class="d-grid">
                                <button class="btn btn-primary rounded-pill" type="submit" name="btn-login">Iniciar sesión</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <p class="text-muted mb-0">© 2023 Majoma. Todos los derechos reservados.</p>
            </div>
        </div>
    </main>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <!--Script visualización password-->
    <script src="../assets/js/password.viewer.js"></script>
</body>

</html>