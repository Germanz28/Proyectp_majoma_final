<?php
include 'conn.php';
session_start();

// Verificar al usuario para iniciar sesión

if (isset($_SESSION['email'])) {
  $login = $conn->prepare("SELECT * FROM user WHERE email = ?");
  $login->bindParam(1, $_SESSION['email']);
  $login->execute();
  $result = $login->fetch(PDO::FETCH_ASSOC);

  if (is_array($result)) {

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Document</title>
      <!--tags SEO-->
      <meta name="author" content="Majoma">
      <meta name="description" content="Aplicativo web Bootstrap">
      <meta name="keywords" content="SENA, sena, Sena, Aplicativo, aplicativo, APLICATIVO">
      <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
      <style>
        /* Estilo de landing page */
        :root {
          --level-h: 60vh;
          --pad: 3rem;
          --muted: #667085;
          --accent: #16213e;
          --accent-2: #1e262c;
          --card-bg: #fff;
        }

        body {
          font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial;
          margin: 0;
          color: #0f172a;
          background: #f8fafc;
        }

        .level {
          min-height: var(--level-h);
          display: flex;
          align-items: center;
          padding: calc(var(--pad)) 1rem;
        }

        /* Alternate backgrounds for visual separation */
        #level-1 {
          background: linear-gradient(90deg, rgba(22, 33, 62, 0.06) 0%, rgba(30, 38, 44, 0.03) 100%);
        }

        #level-2 {
          background: linear-gradient(90deg, rgba(30, 38, 44, 0.02) 0%, rgba(22, 33, 62, 0.01) 100%);
        }

        #level-3 {
          background: linear-gradient(90deg, rgba(22, 33, 62, 0.03) 0%, rgba(30, 38, 44, 0.02) 100%);
        }

        .col-half {
          padding: 2rem;
          display: flex;
          flex-direction: column;
          justify-content: center;
        }

        .content {
          background: linear-gradient(180deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.85));
          border-radius: 12px;
          box-shadow: 0 8px 30px rgba(15, 23, 42, 0.06);
          padding: 1.5rem;
          max-width: 720px;
        }

        .kicker {
          color: var(--accent-2);
          font-weight: 700;
          letter-spacing: .06em;
          font-size: .9rem;
          margin-bottom: .5rem;
        }

        h1 {
          font-size: 2rem;
          margin: 0 0 .5rem;
          color: #07103a;
        }

        p.lead {
          color: var(--muted);
          margin-bottom: 1rem;
        }

        .cta {
          margin-top: .5rem;
        }

        /* Mirror example: image / illustration box */
        .ill {
          width: 100%;
          height: 260px;
          background: linear-gradient(135deg, var(--accent) 0%, var(--accent-2) 100%);
          border-radius: 12px;
          display: flex;
          align-items: center;
          justify-content: center;
          color: #fff;
          font-weight: 700;
          font-size: 1.2rem;
        }

        /* hace que se alterne el texto de la imagen / ilustración */
        .level.reverse .col-half:first-child {
          order: 2;
        }

        /* swap columns visually */

        @media (max-width:767px) {
          :root {
            --level-h: auto;
            --pad: 1.25rem;
          }

          .col-half {
            padding: 1rem;
          }

          .ill {
            height: 180px;
            font-size: 1rem;
          }

          .content {
            padding: 1rem;
          }

          h1 {
            font-size: 1.5rem;
          }
        }

        /* Fin estilo de landing page */

        /* Reduce el ancho del offcanvas */
        .offcanvas-custom-width {
          width: 300px !important;
          max-width: 80vw;
          background-color: rgb(255, 246, 246);
        }

        /* Reduce el alto del carousel */
        #demo.carousel {
          max-height: 700px;
          overflow: hidden;
        }

        #demo .carousel-item img {
          height: 620px;
          object-fit: cover;
          width: 100%;
        }


        .parent {
          display: grid;
          grid-template-columns: repeat(6, 1fr);
          grid-template-rows: repeat(5, 1fr);
          gap: 0px;
          background-color: rgb(255, 255, 255);
        }

        .div1 {
          grid-column: span 2 / span 2;
          grid-row: span 2 / span 2;
        }

        .div2 {
          grid-column: span 2 / span 2;
          grid-row: span 2 / span 2;
          grid-column-start: 3;
        }

        .div3 {
          grid-column: span 2 / span 2;
          grid-row: span 2 / span 2;
          grid-column-start: 5;
        }

        .div6 {
          grid-column: span 6 / span 6;
          grid-row-start: 3;
        }

        .div12 {
          grid-column: span 6 / span 6;
          grid-row: span 2 / span 2;
          grid-row-start: 4;
        }

        /* Alineación específica para que el carousel del nivel 2 quede al mismo nivel que el cuadro de texto */
        #level-2 .row.align-items-center {
          align-items: stretch;
        }

        /* las columnas tendrán misma altura */
        #level-2 .col-half {
          display: flex;
          flex-direction: column;
          justify-content: center;
        }

        /* centrado vertical del contenido */
        #level-2 .col-half .content {
          margin: auto 0;
        }

        /* mantener el contenido centrado dentro de su columna */

        /* Ajustes para que el carousel ocupe la altura de la columna */
        #level2Carousel.ill {
          height: 100%;
          min-height: 260px;
          display: flex;
          align-items: center;
        }

        #level2Carousel.ill .carousel-inner {
          height: 100%;
          width: 100%;
        }

        #level2Carousel.ill .carousel-item {
          height: 100%;
          display: flex;
          align-items: center;
        }

        #level2Carousel.ill .carousel-item img {
          height: 100%;
          width: 100%;
          object-fit: cover;
          border-radius: 12px;
        }

        @media (max-width:767px) {

          /* en móvil mantener comportamiento actual */
          #level-2 .row.align-items-center {
            align-items: center;
          }

          #level2Carousel.ill {
            min-height: 180px;
          }
        }
      </style>
    </head>

    <body>
      <header>
        <!--Menu offcanvas-->
        <div class="offcanvas offcanvas-start offcanvas-lg offcanvas-custom-width" id="offcanvas" tabindex="-1"
          aria-labelledby="offcanvasLabel">
          <div class="offcanvas-header heading p-5" style="background: linear-gradient(90deg, #1a1a2e 60%, #16213e 100%);">
            <h5 id="offcanvasLabel" class="offcanvas-title"
              style="color:#f8f8f8; font-family:'Segoe UI', Verdana, Geneva, Tahoma, sans-serif; letter-spacing:3px; font-size:40px;">
              Menú Principal
            </h5>

            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
              aria-label="Cerrar"></button>
          </div>

          <div class="offcanvas-body d-flex flex-column p-0"
            style="background: linear-gradient(90deg, #f8f8f8 80%, #e0e0e0 100%); min-height:400px;">
            <main class="flex-grow-1 p-3">
              <div class="d-grid gap-3">
                <button class="btn btn-primary w-100" type="button" onclick="location.href='accesorios.php'">
                  <i class="bi bi-box-seam me-2"></i> Accesorios
                </button>

                <button class="btn btn-success w-100" type="button" onclick="location.href='tecnologia.php'">
                  <i class="bi bi-megaphone me-2"></i> Celulares y Tecnología
                </button>

                <button class="btn btn-danger w-100" type="button" onclick="location.href='citas.php'">
                  <i class="bi bi-telephone me-2"></i> Citas
                </button>
              </div>
            </main>

            <footer class="p-3 text-center" style="background: linear-gradient(90deg, #1a1a2e 60%, #16213e 100%);">
              <div class="mb-3 p-2 rounded" style="background:#e7eaf6;color:#222;font-size:1rem;">
                <i class="bi bi-info-circle me-2"></i> ¡Bienvenido! Explora nuestros productos y publicaciones.
              </div>
            </footer>
          </div>
        </div>
        <!--Menu offcanvas-->
        <div id="navaus"></div> <!-- Aquí se cargará el navbar -->
        <script>
          fetch('navaus.php')
            .then(response => response.text())
            .then(data => {
              document.getElementById('navaus').innerHTML = data;
            });
        </script>
      </header>
      <main class="pt-5 mt-5">
        <!-- Carousel -->
        <div id="demo" class="carousel slide" data-bs-ride="carousel">
          <!-- Indicators/dots -->
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active" name="0"
              title="button1"></button>
            <button type="button" data-bs-target="#demo" data-bs-slide-to="1" name="1" title="button2"></button>
            <button type="button" data-bs-target="#demo" data-bs-slide-to="2" name="2" title="button3"></button>
          </div>
          <!-- The slideshow/carousel -->
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="/proyecto_majoma/assets/img/img10.jpg" alt="..." class="d-block w-100">
            </div>
            <div class="carousel-item">
              <img src="/proyecto_majoma/assets/img/img9.jpg" alt="..." class="d-block w-100">
            </div>
            <div class="carousel-item">
              <img src="/proyecto_majoma/assets/img/img11.jpg" alt="..." class="d-block w-100">
            </div>
          </div>
          <!-- Left and right controls/icons -->
          <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev" title="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next" title="next">
            <span class="carousel-control-next-icon"></span>
          </button>
        </div>
        </div>
      </main>
      <footer>
        <div class="container-fluid p-5 bg-dark text-white text-center">
          <h1 style="color: #e0e0e0;">Acerca de nosotros</h1>
          <p>Resize this responsive page to see the effect!</p>
        </div>
        <div>
          <!-- Level 1: texto a la izquierda, ilustración a la derecha -->
          <section id="level-1" class="level">
            <div class="container">
              <div class="row align-items-center gx-4">
                <div class="col-12 col-md-6 col-half">
                  <div class="content" role="region" aria-labelledby="l1-title">
                    <div class="kicker">Nivel 1</div>
                    <h1 id="l1-title">Título principal del nivel 1</h1>
                    <p class="lead">Aquí va un texto explicativo. En este lado puedes colocar información, beneficios o un
                      resumen breve. Manténlo claro y directo.</p>
                    <div class="cta">
                      <a class="btn btn-primary me-2" href="#">Acción principal</a>
                      <a class="btn btn-outline-secondary" href="#">Más información</a>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-6 col-half">
                  <div class="ill" aria-hidden="true">Ilustración / Imagen — Derecha</div>
                </div>
              </div>
            </div>
          </section>

          <!-- Level 2: ilustración a la izquierda, texto a la derecha (opuesto) -->

          <section id="level-2" class="level reverse">
            <div class="container">
              <div class="row gx-4 align-items-stretch">
                <!-- Carousel: mitad izquierda -->
                <div class="col-12 col-md-6 col-half">
                  <div id="level2Carousel" class="carousel slide ill p-0" data-bs-ride="carousel"
                    aria-label="Carousel nivel 2">
                    <div class="carousel-inner">
                      <div class="carousel-item active">
                        <img src="/proyecto_majoma/../assets/imglandpage/phpmyadmin1.png" class="d-block w-100" alt="...">
                      </div>
                      <div class="carousel-item">
                        <img src="/proyecto_majoma/../assets/imglandpage/phpmyadmin2.png" class="d-block w-100" alt="...">
                      </div>
                      <div class="carousel-item">
                        <img src="/proyecto_majoma/../assets/imglandpage/phpmyadmin3.png" class="d-block w-100" alt="...">
                      </div>
                      <div class="carousel-item">
                        <img src="/proyecto_majoma/../assets/img/phpmyadmin4.png" class="d-block w-100" alt="...">
                      </div>
                    </div>

                    <!-- Controles -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#level2Carousel" data-bs-slide="prev"
                      title="prev">
                      <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#level2Carousel" data-bs-slide="next"
                      title="next">
                      <span class="carousel-control-next-icon"></span>
                    </button>

                    <!-- Indicadores -->
                    <div class="carousel-indicators">
                      <button type="button" data-bs-target="#level2Carousel" data-bs-slide-to="0" class="active" name="0"
                        title="button1"></button>
                      <button type="button" data-bs-target="#level2Carousel" data-bs-slide-to="1" name="1"
                        title="button2"></button>
                      <button type="button" data-bs-target="#level2Carousel" data-bs-slide-to="2" name="2"
                        title="button3"></button>
                      <button type="button" data-bs-target="#level2Carousel" data-bs-slide-to="3" name="3"
                        title="button4"></button>
                    </div>
                  </div>
                </div>

                <!-- Contenido: mitad derecha -->
                <div class="col-12 col-md-6 col-half">
                  <div class="content" role="region" aria-labelledby="l2-title">
                    <div class="kicker">Nivel 2</div>
                    <h1 id="l2-title">Título del nivel 2</h1>
                    <p class="lead">Texto del lado opuesto para contrastar con la ilustración. Ideal para características,
                      testimonios o casos de uso.</p>
                    <div class="cta">
                      <a class="btn btn-primary me-2" href="#">Comenzar</a>
                      <a class="btn btn-outline-secondary" href="#">Ver demo</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- Level 3: texto a la izquierda, ilustración a la derecha -->
          <section id="level-3" class="level">
            <div class="container">
              <div class="row align-items-center gx-4">
                <div class="col-12 col-md-6 col-half">
                  <div class="content" role="region" aria-labelledby="l3-title">
                    <div class="kicker">Nivel 3</div>
                    <h1 id="l3-title">Título del nivel 3</h1>
                    <p class="lead">Último nivel para cerrar con una llamada a la acción o resumen final. Puedes colocar
                      formularios cortos o enlaces de contacto.</p>
                    <div class="cta">
                      <a class="btn btn-success me-2" href="#">Contactar</a>
                      <a class="btn btn-outline-secondary" href="#">Política</a>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-6 col-half">
                  <div class="ill" aria-hidden="true">Ilustración / Imagen — Derecha</div>
                </div>
              </div>
            </div>
          </section>
          <div class="div6 col-md-12">
            <div style="display: flex; justify-content: center; align-items: center; min-height: 10px; margin: 0 auto;">
              <i class="bi bi-facebook" style="font-size: 45px; margin: 0 18px;"></i>
              <i class="bi bi-twitter-x" style="font-size: 45px; margin: 0 18px;"></i>
              <i class="bi bi-instagram" style="font-size: 45px; margin: 0 18px;"></i>
              <i class="bi bi-whatsapp" style="font-size: 45px; margin: 0 18px;"></i>
            </div>
          </div>

          <div class="div12 col-md-12 row justify-content-center align-items-center text-center g-4">
            <p>&copy; 2025 Tu empresa. Todos los derechos estan reservados.</p>
            <p>
              <a href="#">Términos y Condiciones</a>
              <a href="#">Política de Privacidad</a>
              <a href="#">Contacto</a>
            </p>
          </div>

        </div>
        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <script src="https://animatedicons.co/scripts/embed-animated-icons.js"></script>
      </footer>
  <?php
  }
} else {
  // Si el usuario no está logueado, redirigir a la página de inicio de sesión
  header("Location: ./login.php");
}
  ?>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script src="https://animatedicons.co/scripts/embed-animated-icons.js"></script>
    </body>

    </html>