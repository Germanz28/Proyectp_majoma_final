<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<nav class="navbar navbar-expand-sm navbar-dark fixed-top" style="background: linear-gradient(90deg, #1a1a2e 60%, #16213e 100%);">
  <div class="container-fluid">
    <img class="navbar-brand" src="assets/img/logo.png" style="width: 90px;">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar" title="menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mynavbar">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <button class="btn btn-link nav-link" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvas">
            <animated-icons src="https://animatedicons.co/get-icon?name=Menu&style=minimalistic&token=c9a15816-8582-4762-810c-23adfb75a127" trigger="click"
              attributes='{"variationThumbColour":"#1e262cff","variationName":"Gray Tone","variationNumber":3,"numberOfGroups":1,"strokeWidth":1.72,"backgroundIsGroup":true,"defaultColours":{"group-1":"#abababff","background":"#494949FF"}}'
              height="50px" width="50">
            </animated-icons>
          </button>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" style="margin-top: 14px;">
            <i class="bi bi-plus-lg" style="color: #b5b1b1ff; font-size: 20px;"></i> Iniciar</a>
          <ul class="dropdown-menu" style="background-color: #1e1f34ff;">
            <li class="nav-item">
              <button type="button" class="btn btn-link nav-link" title="inicio_sesion"
                onclick="location.href='app/login.php'">Inicio de sesion</button>
            </li>
            <li class="nav-item">
              <button type="button" class="btn btn-link nav-link" title="inicio_sesion"
                onclick="location.href='app/reg_user.php'">Registro</button>
            </li>
          </ul>
        </li>
      </ul>
      <form class="d-flex flex-grow-1">
        <input class="form-control mx-4" type="text" placeholder="Search">
        <div class="btn-group">
          <button class="btn btn-outline-info" type="button">Search</button>
          <button type="button" class="btn btn-link nav-link btn btn-outline-warning" title="carrito" style="background-color: rgba(255, 255, 255, 0);" onclick="location.href='app/car.php'"><i class="bi bi-cart4" style="color: #f8df24ff; font-size: 25px;"></i></button>
        </div>
      </form>
    </div>
  </div>
</nav>

