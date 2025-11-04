<?php
include "conn.php";
session_start();

if (isset($_SESSION['email'])) {
    header("Location: ../administrador.php");
    exit();
}
if (isset($_POST['btnlogin'])) {
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['pass'] ?? '';

    $login = $conn->prepare("SELECT * FROM user WHERE email = :email LIMIT 1");
    $login->bindParam(':email', $email);
    $login->execute();
    $result = $login->fetch(PDO::FETCH_ASSOC);

    if (is_array($result)) {
        if ($result && password_verify($pass, $result['pass'])) {
            $_SESSION['email'] = $result['email'];
            $_SESSION['id'] = $result['id'];
            header("Location: ../administrador.php");
            exit();
        } else {
            $msg = array("contraseña incorrectos", "danger");
        }
    } else {
            $msg = array("Correo invalido", "danger");
        }
}
?>