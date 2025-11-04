<?php
include 'conn.php';

// Acciones en historial: restaurar o eliminar definitivamente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['btnRestore'])) {
        $aid = intval($_POST['archive_id']);
        // obtener archivo
        $s = $conn->prepare("SELECT * FROM citas_archive WHERE id = ?");
        $s->execute([$aid]);
        $r = $s->fetch(PDO::FETCH_ASSOC);
        if ($r) {
            // insertar de nuevo en citas (mantener valores, status queda 'activa' al restaurar)
            $ins = $conn->prepare("INSERT INTO citas (nombre, telefono, fecha, hora, servicio, notas, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $ins->execute([$r['nombre'], $r['telefono'], $r['fecha'], $r['hora'], $r['servicio'], $r['notas'], 'activa']);
            // eliminar del archivo
            $d = $conn->prepare("DELETE FROM citas_archive WHERE id = ?");
            $d->execute([$aid]);
        }
        header('Location: citas_history.php'); exit;
    }

    if (isset($_POST['btnDeleteArchive'])) {
        $aid = intval($_POST['archive_id']);
        $d = $conn->prepare("DELETE FROM citas_archive WHERE id = ?");
        $d->execute([$aid]);
        header('Location: citas_history.php'); exit;
    }
}

// obtener historial
$stmt = $conn->prepare("SELECT * FROM citas_archive ORDER BY archived_at DESC");
$stmt->execute();
$archivos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Historial de Citas Finalizadas</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/tecnologia.css">
  <link rel="stylesheet" href="../assets/css/citas.css">
</head>
<body>
  <div class="container mt-4">
    <div class="navbar-custom mb-4" style="border-radius: 0.75rem;">
      <a class="navbar-brand" href="../index.html">
        <img src="/proyecto_majoma/assets/img/logo.png" alt="logo"> Historial Citas
      </a>
      <nav class="navbar-nav" role="navigation">
        <a class="nav-link " href="admcita.php" style="color: gray;">Volver al panel</a>
      </nav>
    </div>

    <h4 class="mb-3">Historial — Citas finalizadas</h4>

    <div class="card">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Teléfono</th>
                <th>Fecha / Hora</th>
                <th>Servicio</th>
                <th>Notas</th>
                <th>Archivado</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>
            <?php if (empty($archivos)): ?>
              <tr><td colspan="8" class="text-center small-muted py-4">No hay citas archivadas.</td></tr>
            <?php else: foreach ($archivos as $a): ?>
              <tr>
                <td><?php echo htmlspecialchars($a['id']); ?></td>
                <td><?php echo htmlspecialchars($a['nombre']); ?></td>
                <td><?php echo htmlspecialchars($a['telefono']); ?></td>
                <td><?php echo htmlspecialchars($a['fecha']); ?> <span class="small-muted">/</span> <?php echo htmlspecialchars($a['hora']); ?></td>
                <td><?php echo htmlspecialchars($a['servicio']); ?></td>
                <td class="small-muted"><?php echo htmlspecialchars($a['notas']); ?></td>
                <td class="small-muted"><?php echo htmlspecialchars($a['archived_at']); ?></td>
                <td class="text-end">
                  <form method="post" class="d-inline" onsubmit="return confirm('Restaurar esta cita?');" style="display:inline-block">
                    <input type="hidden" name="archive_id" value="<?php echo $a['id']; ?>">
                    <button type="submit" name="btnRestore" class="btn btn-sm btn-outline-primary" title="Restaurar"><i class="fa fa-undo"></i></button>
                  </form>
                  <form method="post" class="d-inline" onsubmit="return confirm('Eliminar definitivamente este registro?');" style="display:inline-block">
                    <input type="hidden" name="archive_id" value="<?php echo $a['id']; ?>">
                    <button type="submit" name="btnDeleteArchive" class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="fa fa-trash"></i></button>
                  </form>
                </td>
              </tr>
            <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

  <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>