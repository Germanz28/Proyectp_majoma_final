<?php
include 'conn.php';
$productos = [];
$query = $conn->query("SELECT * FROM product WHERE categoria = 'tecnologia'");
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $productos[] = $row;
}
// 6 productos aleatorios para el mini carrusel
$carouselStmt = $conn->query("SELECT * FROM product WHERE categoria = 'tecnologia' ORDER BY RAND() LIMIT 6");
$carouselProductos = $carouselStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos de Tecnología</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bg: #f8f9fa;
            --card-bg: #ffffff;
            --accent: #0d6efd;
            --accent-dark: #0b5ed7;
            --muted: #6c757d;
            --radius: 12px;
            --shadow-sm: 0 6px 18px rgba(16, 24, 40, 0.06);
        }
        html,body{
            height:100%;
            background:var(--bg);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            color:#222;
            margin:0;
        }
        .container{ max-width:1240px; margin:0 auto; padding:1.25rem; }

        /* Navbar limpio */
        .navbar-custom{
            display:flex; align-items:center; gap:1rem;
            background: linear-gradient(180deg, #1e262cff, #16213eff);
            border-radius: calc(var(--radius) + 4px);
            padding:.5rem 1rem; box-shadow: var(--shadow-sm); margin-bottom:1.25rem;
        }
        .navbar-brand{ display:flex; align-items:center; gap:.6rem; font-weight:700; color:var(--accent); text-decoration:none; }
        .navbar-brand img{ width:60px; height:70px; border-radius:10px; object-fit:cover; box-shadow:0 4px 14px rgba(13,110,253,0.12); }

        .navbar-nav{ margin-left:auto; display:flex; align-items:center; gap:.45rem; }
        .nav-link{ color:#2b2b2b; padding:.45rem .65rem; border-radius:10px; text-decoration:none; font-weight:600; transition: all .14s ease; }
        .nav-link:hover, .nav-link.active{
            background: rgba(13,110,253,0.07); color:var(--accent-dark); transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(13,110,253,0.06);
        }

        .nav-search{ display:flex; align-items:center; gap:.5rem; background:var(--card-bg); padding:.28rem .45rem; border-radius:10px; box-shadow: 0 4px 10px rgba(0,0,0,0.04); border:1px solid rgba(0,0,0,0.04); }
        .nav-search input{ border:0; outline:0; background:transparent; padding:.28rem .4rem; width:200px; font-size:.95rem; }

        .icon-btn{ background:transparent; border:0; padding:.45rem; border-radius:8px; color:var(--muted); transition: all .12s ease; }
        .icon-btn:hover{ background: rgba(0,0,0,0.04); color:var(--accent-dark); transform: translateY(-3px); }
        .hamburger{ display:none; background:transparent; border:0; font-size:1.25rem; color:var(--muted); }

        /* Carrusel y cards */
        #miniProductosCarousel{ margin-top:.5rem; margin-bottom:1.25rem; }
        #miniProductosCarousel .carousel-inner{ padding:.4rem; }
        #miniProductosCarousel .carousel-item{ border-radius:var(--radius); padding:.6rem; }
        #miniProductosCarousel .card{ background:var(--card-bg); border-radius: calc(var(--radius)-4px); overflow:hidden; height:100%; display:flex; flex-direction:column; box-shadow: 0 6px 18px rgba(16,24,40,0.06); transition: transform .18s ease, box-shadow .18s ease; }
        #miniProductosCarousel .card:hover{ transform: translateY(-6px); box-shadow: 0 10px 28px rgba(16,24,40,0.10); }
        .card-img-top{ height:150px; object-fit:cover; width:100%; display:block; border-radius:12px 12px 0 0; }
        .card-body{ padding:.8rem; display:flex; flex-direction:column; gap:.45rem; }
        .card-title{ font-size:1rem; font-weight:700; color:var(--accent-dark); margin:0; }
        .card-text{ color:var(--muted); font-size:.92rem; margin:0; }
        .badge{ font-weight:600; border-radius:8px; padding:.35rem .5rem; }
        .price{ font-weight:800; color:#111; }

        .main-grid .card{ border-radius:12px; overflow:hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.06); transition: transform .18s ease; }
        .main-grid .card:hover{ transform: translateY(-6px); box-shadow: 0 10px 28px rgba(16,24,40,0.08); }
        .main-grid .card-img-top{ height:180px; object-fit:cover; }

        @media (max-width:991px){ .nav-search input{ width:150px; } }
        @media (max-width:767px){ .navbar-nav .nav-link{ display:none; } .hamburger{ display:inline-flex; } .nav-search{ display:none; } .card-img-top{ height:140px; } .main-grid .card-img-top{ height:160px; } }
        .mt-auto{ margin-top:auto !important; }
        .text-muted-compact{ color:var(--muted); font-size:.9rem; }
        .nav-link:focus, .icon-btn:focus, .nav-search input:focus{ outline:3px solid rgba(13,110,253,0.12); outline-offset:2px; border-radius:8px; }
    </style>
</head>

<body>
    <header>
        <div class="container mt-4">
            <div class="navbar-custom" role="navigation" aria-label="Navegación principal">
                <a class="navbar-brand" href="tecnologia.php">
                    <img src="/proyecto_majoma/assets/img/logo.png" alt="logo">
                    Tienda Majoma
                </a>
                <div class="nav-menu" role="menu">
                    <a class="nav-link" href="accesorios.php" style="color: #989a9cff;">Accesorios</a>
                </div>
                <nav class="navbar-nav" role="navigation">
                    <div class="nav-search" role="search">
                        <i class="bi bi-search"></i>
                        <input type="search" placeholder="Buscar productos" aria-label="Buscar productos">
                    </div>
                    <button class="hamburger" aria-label="Menú"><i class="bi bi-list"></i></button>
                </nav>
            </div>
            <div class="row mb-3">
                <div class="col-6">
                    <a href="../index.html" class="btn btn-outline-dark"><i class="bi bi-arrow-left"></i> Regresar al inicio</a>
                </div>
            </div>

            <!-- Mini carrusel: 6 productos aleatorios -->
            <div class="col-12">
                <div id="miniProductosCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        $total = count($carouselProductos);
                        $porSlide = 4;
                        for ($i = 0; $i < $total; $i += $porSlide):
                            $active = ($i == 0) ? 'active' : '';
                        ?>
                            <div class="carousel-item <?php echo $active; ?>">
                                <div class="row g-4">
                                    <?php for ($j = $i; $j < $i + $porSlide && $j < $total; $j++):
                                        $prod = $carouselProductos[$j];
                                    ?>
                                        <div class="col-12 col-sm-6 col-md-3 d-flex align-items-stretch">
                                            <div class="card w-100">
                                                <img class="card-img-top" src="<?php echo $consulta['img']; ?>" alt="Imagen">
                                                <div class="card-body d-flex flex-column">
                                                    <h5 class="card-title text-primary"><?php echo htmlspecialchars($prod['nam']); ?></h5>
                                                    <p class="card-text"><?php echo htmlspecialchars($prod['descrip']); ?></p>
                                                    <div class="mt-auto">
                                                        <span class="badge bg-success mb-2">Stock: <?php echo htmlspecialchars($prod['cant']); ?></span><br>
                                                        <span class="fw-bold text-dark">Precio: $<?php echo htmlspecialchars($prod['price']); ?></span>
                                                    </div>
                                                    <a href="#" class="btn btn-outline-primary btn-ver-producto mt-3"><i class="fa fa-eye"></i> Ver este producto</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#miniProductosCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#miniProductosCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
            </div>
            <!-- Fin mini carrusel -->
        </div>
    </header>

    <main>
        <div class="container main-grid">
            <div class="row g-4">
                <?php foreach ($productos as $consulta): ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex align-items-stretch">
                        <div class="card w-100 h-100">
                                <img class="card-img-top" src="<?php echo $consulta['img']; ?>" alt="Imagen">
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title mb-1 text-secondary">ID: <?php echo htmlspecialchars($consulta['idproduct']); ?></h6>
                                <h5 class="card-title mb-1"><?php echo htmlspecialchars($consulta['nam']); ?></h5>
                                <p class="card-text mb-1"><?php echo htmlspecialchars($consulta['descrip']); ?></p>
                                <p class="card-text mb-1">Cantidad: <?php echo htmlspecialchars($consulta['cant']); ?></p>
                                <h6 class="card-title mb-1 text-success">Precio: $<?php echo htmlspecialchars($consulta['price']); ?></h6>
                                <a href="#" class="btn btn-outline-primary mt-auto">Comprar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>