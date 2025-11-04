<?php

include __DIR__ . '/conn.php';

session_start();

if (isset($_SESSION['email'])) {
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $_SESSION['email']);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (is_array($row)) {


?>
        <nav class="navbar navbar-expand-sm navbar-dark bg-dark  p-2">
            <div class="container-fluid">
                <img class="navbar-brand" src="../assets/img/logo.png" style="width: 90px;">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar" title="menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mynavbar">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <button class="btn btn-link nav-link" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvas" onclick="location.href='admcita.php'" style="font-size: 18px;">
                                Citas
                            </button>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><i class="fa-jelly fa-regular fa-circle-user fa-2xl" style="color: #1cbc1a;"></i>

                                <span class="navbar-text ms-2" style="color: #1cbc1a; font-weight: bold;">
                                    <?php echo $row['fname']; ?>
                            </a>
                            </span>
                            <ul class="dropdown-menu" style="background-color: rgb(35, 34, 40);">
                                <li class="nav-item">
                                    <button type="button" class="btn btn-link nav-link" title="inicio_sesion"
                                        onclick="location.href='logout.php'">Cerrar Sesion</button>
                                </li>
                                
                            </ul>
                        </li>
                    </ul>
                    <form class="d-flex flex-grow-1">
                        <input class="form-control mx-4" type="text" placeholder="Search">
                        <button class="btn btn-outline-info" type="button">Search</button>
                    </form>
                </div>
            </div>
        </nav>

<?php
    }
}
?>