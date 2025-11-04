<?php
include 'conn.php';
session_start();

// Verificar al usuario para iniciar sesión

if (isset($_SESSION['email'])) {
  $login = $conn->prepare("SELECT * FROM user WHERE email   = ?");
  $login->bindParam(1, $_SESSION['email']);
  $login->execute();
  $result = $login->fetch(PDO::FETCH_ASSOC);

  if (is_array($result)) {

    if (isset($_POST['btndelete'])) {
      $delete = $conn->prepare('DELETE FROM user WHERE id = ?');
      $delete->bindParam(1, $_POST['id']);
      $delete->execute();

      if ($delete->rowCount() > 1) {
        $delmsg = array('Usuario eliminado correctamente', 'success');
      } else {
        $delmsg = array('No se pudo eliminar el usuario', 'danger');
      }
    }

?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>administradores</title>
      <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
      <link href="../assets/datatables/datatables.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    </head>

    <body>
      <header>
        <div id="navad"></div> <!-- Aquí se cargará el navbar -->
        <script>
          fetch('navad.php')
            .then(response => response.text())
            .then(data => {
              document.getElementById('navad').innerHTML = data;
            });
        </script>
      </header>
      <main class="container pt-5">
        <nav>
          <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">User</button>
            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Productos</button>
          </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
          <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
            <div class="row pt-3">
              <div class="col-md-4">
                <button type="button" class="btn btn-info" onclick="location.href='reg_user.php'">Registrar</button>
              </div>
            </div>
            <!--Section alerts-->
            <?php if (isset($delmsg)) { ?>
              <div class="alert alert-<?php echo $delmsg[1] ?> alert-dismissible">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong>Alerta!</strong> <?php echo $delmsg[0] ?>.
              </div>
            <?php } ?>
            <!--Section alerts-->
            <h2 style="text-align: center" ;>Usuarios registrados</h2>
            <table class="table table-striped" id="table">
              <thead>
                <tr>
                  <th scope="col">Id</th>
                  <th scope="col">Nombres</th>
                  <th scope="col">Apellidos</th>
                  <th scope="col">Correo Electronico</th>
                  <th scope="col">Rol</th>
                  <th scope="col">Eliminar</th>

                </tr>
              </thead>
              <tbody>
                <?php
                $tableusers = $conn->prepare("SELECT * FROM user");
                $tableusers->execute();

                if ($tableusers->rowCount() > 0) {
                  while ($row = $tableusers->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                      <th scope="row"><?php echo $row['id'] ?></th>
                      <td><?php echo $row['fname'] ?></td>
                      <td><?php echo $row['lname'] ?></td>
                      <td><?php echo $row['email'] ?></td>
                      <td><?php if ($row['rol'] == '0') {
                            echo "Cliente";
                          } else {
                            echo "Admin";
                          }  ?></td>
                      <td><!-- Botón Eliminar -->
                        <?php if ($row['rol'] == '1') { ?>
                          <form action="" method="post" onsubmit="return confirm('¿Está seguro de eliminar el usuario con email?\n<?php echo $row['email']; ?>');">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn-outline-danger" name="btndelete">
                              <i class="fa-solid fa-trash"></i>
                            </button>
                          </form>
                        <?php
                        }  ?>

                      </td>

                    </tr>

                <?php }
                } ?>

              </tbody>
            </table>

          </div>
          <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
            <div class="tab-content" id="nav-tabContent">
              <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                <div class="row pt-3">
                  <div class="col-md-4">
                    <button type="button" class="btn btn-info" onclick="location.href='regprod.php'">Registrar</button>
                  </div>
                </div>
                <!--Section alerts-->

                <!--Section alerts-->
                <h2 style="text-align: center" ;>Productos registrados</h2>
                <table class="table table-striped" id="table">
                  <thead>
                    <tr>
                      <th scope="col">id</th>
                      <th scope="col">Nombres</th>
                      <th scope="col">Descripcion</th>
                      <th scope="col">Cantidad</th>
                      <th scope="col">Precio</th>
                      <th scope="col">Imagen</th>
                      <th scope="col">Eliminar</th>


                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $tableproduct = $conn->prepare("SELECT * FROM product");
                    $tableproduct->execute();
                    $tableproduct->setFetchMode(PDO::FETCH_ASSOC);
                    foreach ($tableproduct as $row) {
                    ?>
                      <tr>
                        <th scope="row"><?php echo $row['idproduct'] ?></th>
                        <td><?php echo $row['nam'] ?></td>
                        <td><?php echo $row['descrip'] ?></td>
                        <td><?php echo $row['cant'] ?></td>
                        <td><?php echo $row['price'] ?></td>
                        <td>
                          <img height="150px" width="150px"
                            src="<?php echo $row['img']; ?>" />
                        </td>

                        <td><!-- Botón Eliminar -->
                          <?php if ($row) { ?>
                            <form action="" method="post" onsubmit="return confirm('¿Está seguro de eliminar el producto con nombre?\n<?php echo $row['nam']; ?>');">
                              <input type="hidden" name="idproduct" value="<?php echo $row['idproduct']; ?>">
                              <button type="submit" class="btn btn-outline-danger" name="btndelete">
                                <i class="fa-solid fa-trash"></i>
                              </button>
                            </form>
                          <?php
                          }  ?>

                        </td>

                      </tr>

                  <?php }
                  } ?>

                  </tbody>
                </table>

              </div>
            </div>

          </div>
      </main>


      <script src="../assets/css/bootstrap.min.js"></script>
      <script src="../assets/datatables/datatables.min.js"></script>
      <script>
        let table = new DataTable('#table', {
          responsive: true,
          language: {
            url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
          }
        })
      </script>
    <?php

  } else {
    // Si el usuario no está logueado, redirigir a la página de inicio de sesión
    header("Location: ./login.php");
  }
    ?>
    </body>

    </html>