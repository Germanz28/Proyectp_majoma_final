<?php
require_once 'conn.php';

if (isset($_POST['btn-reg'])) {
    $insert = $conn->prepare("INSERT INTO product ( nam, descrip, cant, price, img) VALUES(?,?,?,?,?)");
    $insert->bindParam(1, $_POST['nam']);
    $insert->bindParam(2, $_POST['descrip']);
    $insert->bindParam(3, $_POST['cant']);
    $insert->bindParam(4, $_POST['price']);
    // Imagen
    $name = $_FILES['img']['name'];
    $file = $_FILES['img']['tmp_name'];
    $folder = "productos/";
    $way = $folder . $name;

    // Verificar si la carpeta existe, si no, crearla
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true); // true permite crear subdirectorios de forma recursiva
    }
    move_uploaded_file($file, $way);
    $insert->bindParam(5, $way);

    if ($insert->execute()) {
        $msg = array("Datos Registrados", "success");
    } else {
        $msg = array("Datos no registrados", "danger");
    }
}
?>
<!DOCTYPE html>
<html lang="es-CO" data-bs-theme="dark" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Majoma 11</title>
    <!--Logo Favicon-->
    <link rel="shortcut icon" href="../assets/img/logofav.jpeg" type="image/x-icon">

    <!--SEO Tags-->
    <meta name="author" content="Majoma">
    <meta name="description" content="Aplicativo web Bootstrap">
    <meta name="keywords" content="SENA, sena, Sena, Aplicativo, APLICATIVO, aplicativo">

    <!--Optimization Tags-->
    <meta name="theme-color" content="#000000">
    <meta name="MobileOptimized" content="width">
    <meta name="HandlhledFriendly" content="true">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-traslucent">

    <!--Bootstrap 5.3 Styles and complements-->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/me.styles.css">
    <!--styles Icons Bootstrap-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-login pt-3 mt-5">
    <main class="form-regp mx-auto">
        <div class="card" style="border-radius: 1.5rem; background-color:#ffffffc0;">
            <div class="card-body">
                <!--Alerts-->
                <?php if (isset($msg)) { ?>
                    <div class="alert alert-<?php echo $msg[1]; ?> alert-dismissible">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <strong>Success!</strong> <?php echo $msg[0]; ?>
                    </div>
                <?php } ?>
                <!--Alerts-->
                <span class="float-end fw-bold">
                    <a href="administrador.php"><i class="bi bi-x-lg" style="font-size:20px;"></i></a>
                </span>
                <div class="text-center py-4">
                    <h1 class="display-6">Registro de Productos</h1>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="input-group">
                        <div class="input-field mb-3 mt-3">
                            <span class="input-group-text rounded-pill border-0" style="background-color:#ffffff00;"><i class="fa-solid fa-circle-user fa-2xl" style="color: #000000;">:</i></span>
                            <input type="text" class="form-control rounded-pill" id="nam" placeholder="Ingrese el nombre"
                                name="nam" required>
                        </div>
                        <div class="input-field mb-3 mt-3">
                            <span class="input-group-text rounded-pill border-0" style="background-color: #ffffff00;"><i class="fa-regular fa-file-word fa-2xl" style="color: #000000;">:</i></span>
                            <input type="text" class="form-control rounded-pill" id="descrip" placeholder="Ingrese una descripción"
                                name="descrip" required>
                        </div>
                        <div class="input-field mb-3 mt-3">
                            <span class="input-group-text rounded-pill border-0" style="background-color: #ffffff00;"><i class="fa-solid fa-lines-leaning fa-2xl" style="color: #000000;">:</i></span>
                            <input type="text" class="form-control rounded-pill" id="cant" placeholder="Ingrese una cantidad"
                                name="cant" required>
                        </div>
                        <div class="input-field mb-3 mt-3">
                            <span class="input-group-text rounded-pill border-0" style="background-color: #ffffff00;"><i class="fa-solid fa-envelope fa-2xl" style="color: #000000;">:</i></span>
                            <input type="text" class="form-control rounded-pill" id="price" placeholder="Ingrese su precio" name="price"
                                required>
                        </div>
                        <div class="input-field mb-3 mt-3">
                            <span class="input-group-text rounded-pill border-0" style="background-color: #ffffff00;"><i class="fa fa-file-import" style="font-size: 35px;">:</i></span>

                            <div class="input-field">
                                <input class="form-control password-input-wrapper rounded-pill" type="file" name="img" id="img" placeholder="Ingrese una imagen" required>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary rounded-pill " type="submit" name="btn-reg" >Registrar</button>
                        </div>
                        <style>
                            .password-input-group-text {
                                position: absolute;
                                left: 260px;
                            }

                            .card-body h1::after {
                                content: '';
                                width: 350px;
                                height: 2px;
                                border-radius: 5px;
                                background-color: rgb(88, 86, 86);
                                position: absolute;
                                bottom: 435px;
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
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <!--Script visualización password-->
    <script src="../assets/js/password.viewer.js"></script>
</body>

</html>