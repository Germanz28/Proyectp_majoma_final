<?php
include "conn.php";
if (session_status() === PHP_SESSION_NONE) session_start();

// Crear / actualizar / cancelar / eliminar (opcional para clientes)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['btnCreate'])) {
        $nombre = trim($_POST['nombre'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $fecha = $_POST['fecha'] ?? null;
        $hora = $_POST['hora'] ?? null;
        $servicio = $_POST['servicio'] ?? '';
        $notas = $_POST['notas'] ?? '';
        $stmt = $conn->prepare("INSERT INTO citas (nombre, telefono, fecha, hora, servicio, notas, status) VALUES (?, ?, ?, ?, ?, ?, 'activa')");
        $stmt->execute([$nombre, $telefono, $fecha, $hora, $servicio, $notas]);
        header('Location: citas.php');
        exit;
    }

    if (isset($_POST['btnUpdate'])) {
        $id = intval($_POST['id']);
        $stmt = $conn->prepare("UPDATE citas SET nombre = ?, telefono = ?, fecha = ?, hora = ?, servicio = ?, notas = ? WHERE id = ?");
        $stmt->execute([$_POST['nombre'] ?? '', $_POST['telefono'] ?? '', $_POST['fecha'] ?? null, $_POST['hora'] ?? null, $_POST['servicio'] ?? '', $_POST['notas'] ?? '', $id]);
        header('Location: citas.php');
        exit;
    }

    if (isset($_POST['btnCancel'])) {
        $id = intval($_POST['id']);
        $stmt = $conn->prepare("UPDATE citas SET status = 'cancelada' WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: citas.php');
        exit;
    }

    if (isset($_POST['btnDelete'])) {
        $id = intval($_POST['id']);
        $stmt = $conn->prepare("DELETE FROM citas WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: citas.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Citas - Majoma</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/tecnologia.css">
  <link rel="stylesheet" href="../assets/css/citas.css">
</head>
<body>
  

  <div class="container mt-4">
    <div class="d-flex align-items-center mb-3">
      <h4 class="me-auto">Citas</h4>
      <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">Nueva cita</button>
    </div>

    <div class="card">
      <div class="card-body p-3">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Teléfono</th>
                <th>Fecha / Hora</th>
                <th>Servicio</th>
                <th>Notas</th>
                <th>Status</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody id="citasBody">
              <!-- filas renderizadas por JS -->
              <tr><td colspan="8" class="text-center small-muted py-4">Cargando...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Crear -->
  <div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <form method="post" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Nueva cita</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <label class="form-label">Nombre</label><input name="nombre" class="form-control" required>
          <label class="form-label mt-2">Teléfono</label><input name="telefono" class="form-control">
          <div class="row g-2 mt-2">
            <div class="col-6"><label class="form-label">Fecha</label><input type="date" name="fecha" class="form-control" required></div>
            <div class="col-6"><label class="form-label">Hora</label><input type="time" name="hora" class="form-control" required></div>
          </div>
          <label class="form-label mt-2">Servicio</label><input name="servicio" class="form-control">
          <label class="form-label mt-2">Notas</label><textarea name="notas" class="form-control" rows="2"></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" name="btnCreate" class="btn btn-primary">Crear</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Editar (rellenado por JS) -->
  <div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <form method="post" id="formEdit" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar cita</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit-id">
          <label class="form-label">Nombre</label><input name="nombre" id="edit-nombre" class="form-control" required>
          <label class="form-label mt-2">Teléfono</label><input name="telefono" id="edit-telefono" class="form-control">
          <div class="row g-2 mt-2">
            <div class="col-6"><label class="form-label">Fecha</label><input type="date" name="fecha" id="edit-fecha" class="form-control" required></div>
            <div class="col-6"><label class="form-label">Hora</label><input type="time" name="hora" id="edit-hora" class="form-control" required></div>
          </div>
          <label class="form-label mt-2">Servicio</label><input name="servicio" id="edit-servicio" class="form-control">
          <label class="form-label mt-2">Notas</label><textarea name="notas" id="edit-notas" class="form-control" rows="2"></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" name="btnUpdate" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>

  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script>
    const esc = s => String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');

    function renderRows(citas) {
      const tbody = document.getElementById('citasBody');
      if (!citas || citas.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center small-muted py-4">No hay citas registradas.</td></tr>';
        return;
      }
      tbody.innerHTML = citas.map(c => {
        const statusHtml = (c.status === 'cancelada') ? '<span class="status-cancelada">Cancelada</span>' : '<span class="status-activa">Activa</span>';
        return `<tr>
          <td>${esc(c.id)}</td>
          <td>${esc(c.nombre)}</td>
          <td>${esc(c.telefono)}</td>
          <td>${esc(c.fecha)} <span class="small-muted">/</span> ${esc(c.hora)}</td>
          <td>${esc(c.servicio)}</td>
          <td class="small-muted">${esc(c.notas)}</td>
          <td>${statusHtml}</td>
          <td class="text-end">
            <button class="btn btn-sm btn-outline-secondary btn-edit"
              data-id="${esc(c.id)}" data-nombre="${esc(c.nombre)}" data-telefono="${esc(c.telefono)}"
              data-fecha="${esc(c.fecha)}" data-hora="${esc(c.hora)}" data-servicio="${esc(c.servicio)}" data-notas="${esc(c.notas)}">
              <i class="fa fa-edit"></i>
            </button>
            ${c.status !== 'cancelada' ? `<form method="post" class="d-inline" onsubmit="return confirm('¿Cancelar cita #${esc(c.id)}?');"><input type="hidden" name="id" value="${esc(c.id)}"><button type="submit" name="btnCancel" class="btn btn-sm btn-outline-warning"><i class="fa fa-ban"></i></button></form>` : ''}
            <form method="post" class="d-inline" onsubmit="return confirm('¿Eliminar cita #${esc(c.id)}?');"><input type="hidden" name="id" value="${esc(c.id)}"><button type="submit" name="btnDelete" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button></form>
          </td>
        </tr>`; }).join('');
      attachEditHandlers();
    }

    function attachEditHandlers() {
      document.querySelectorAll('.btn-edit').forEach(btn=>{
        btn.onclick = function(){
          document.getElementById('edit-id').value = this.dataset.id || '';
          document.getElementById('edit-nombre').value = this.dataset.nombre || '';
          document.getElementById('edit-telefono').value = this.dataset.telefono || '';
          document.getElementById('edit-fecha').value = this.dataset.fecha || '';
          document.getElementById('edit-hora').value = this.dataset.hora || '';
          document.getElementById('edit-servicio').value = this.dataset.servicio || '';
          document.getElementById('edit-notas').value = this.dataset.notas || '';
          new bootstrap.Modal(document.getElementById('modalEdit')).show();
        };
      });
    }

    function fetchAndRender() {
      fetch('citas-data.php').then(r=>r.json()).then(renderRows).catch(()=>{});
    }

    fetchAndRender();
    setInterval(fetchAndRender, 5000);

    // búsqueda local simple (si tienes input adaptarlo)
  </script>
</body>
</html>