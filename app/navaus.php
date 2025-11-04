<?php
include __DIR__ . '/conn.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$user = null;
if (!empty($_SESSION['email'])) {
    try {
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $_SESSION['email']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    } catch (Exception $e) {
        // silent fallback
        $user = null;
    }
}
?>
<nav class="navbar navbar-expand-sm navbar-dark fixed-top" style="background: linear-gradient(90deg, #1a1a2e 60%, #16213e 100%);">
  <div class="container-fluid">
    <img class="navbar-brand" src="/proyecto_majoma/assets/img/logo.png" style="width: 90px;">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar" title="menu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mynavbar">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <button class="btn btn-link nav-link" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas">
            <animated-icons
              src="https://animatedicons.co/get-icon?name=Menu&style=minimalistic&token=c9a15816-8582-4762-810c-23adfb75a127"
              trigger="click"
              attributes='{"variationThumbColour":"#1e262cff","variationName":"Gray Tone","variationNumber":3,"numberOfGroups":1,"strokeWidth":1.72,"backgroundIsGroup":true,"defaultColours":{"group-1":"#abababff","background":"#494949FF"}}'
              height="100px" width="50">
            </animated-icons>
          </button>
        </li>

        <?php if ($user): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" style="gap:0.5rem; line-height:6;">
              <i class="fa-regular fa-circle-user" style="color:#1cbc1a; font-size:32px; display:inline-block; vertical-align:middle;"></i>
              <span class="nav-user-name" style="color:#b5b1b1ff; display:inline-block; transform:translateY(6px);">
                <?php echo htmlspecialchars($user['fname'] ?? 'Usuario'); ?>
              </span>
            </a>
            <ul class="dropdown-menu" style="background-color:#1e1f34ff;">
              <li><button type="button" class="btn btn-link nav-link" onclick="location.href='profile.php'">Perfil</button></li>
              <li><button type="button" class="btn btn-link nav-link" onclick="location.href='logout.php'">Cerrar sesión</button></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" style="margin-top:14px;">
              <i class="bi bi-plus-lg" style="color:#b5b1b1ff; font-size:20px;"></i> Iniciar
            </a>
            <ul class="dropdown-menu" style="background-color:#1e1f34ff;">
              <li><button type="button" class="btn btn-link nav-link" onclick="location.href='app/login.php'">Inicio de sesión</button></li>
              <li><button type="button" class="btn btn-link nav-link" onclick="location.href='app/reg_user.php'">Registro</button></li>
            </ul>
          </li>
        <?php endif; ?>
      </ul>

      <form class="d-flex flex-grow-1" role="search" action="app/search.php" method="get">
        <input class="form-control mx-4" name="q" type="text" placeholder="Search" aria-label="Search">
        <div class="btn-group">
          <button class="btn btn-outline-info" type="submit">Search</button>
          <button type="button" class="btn btn-link nav-link btn btn-outline-warning" title="carrito" onclick="location.href='app/../car.php'">
            <i class="bi bi-cart4" style="color:#f8df24ff; font-size:25px;"></i>
          </button>
        </div>
      </form>
    </div>
  </div>
</nav>