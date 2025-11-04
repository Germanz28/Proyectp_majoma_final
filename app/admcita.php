<?php
include 'conn.php';

// Acciones administrativas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Actualizar cita
    if (isset($_POST['btnUpdate'])) {
        $id = intval($_POST['id']);
        $nombre = $_POST['nombre'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $hora = $_POST['hora'] ?? '';
        $servicio = $_POST['servicio'] ?? '';
        $notas = $_POST['notas'] ?? '';
        $stmt = $conn->prepare("UPDATE citas SET nombre = ?, telefono = ?, fecha = ?, hora = ?, servicio = ?, notas = ? WHERE id = ?");
        $stmt->execute([$nombre, $telefono, $fecha, $hora, $servicio, $notas, $id]);
        header('Location: admcita.php');
        exit;
    }

    // Cancelar cita
    if (isset($_POST['btnCancel'])) {
        $id = intval($_POST['id']);
        $stmt = $conn->prepare("UPDATE citas SET status = 'cancelada' WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: admcita.php');
        exit;
    }

    // Marcar como finalizada (mover a archivo)
    if (isset($_POST['btnDone'])) {
        $id = intval($_POST['id']);
        try {
            // Crear tabla de archivo si no existe
            $conn->exec("CREATE TABLE IF NOT EXISTS citas_archive (
                id INT PRIMARY KEY AUTO_INCREMENT,
                original_id INT NULL,
                nombre TEXT,
                telefono VARCHAR(255),
                fecha DATE,
                hora TIME,
                servicio TEXT,
                notas TEXT,
                status VARCHAR(50),
                archived_at DATETIME DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            $conn->beginTransaction();

            $sel = $conn->prepare("SELECT * FROM citas WHERE id = ?");
            $sel->execute([$id]);
            $row = $sel->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $ins = $conn->prepare("INSERT INTO citas_archive (original_id, nombre, telefono, fecha, hora, servicio, notas, status, archived_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                $ins->execute([$row['id'], $row['nombre'], $row['telefono'], $row['fecha'], $row['hora'], $row['servicio'], $row['notas'], 'finalizada']);
                $del = $conn->prepare("DELETE FROM citas WHERE id = ?");
                $del->execute([$id]);
            }

            $conn->commit();
        } catch (Exception $e) {
            if ($conn->inTransaction()) $conn->rollBack();
            error_log('admcita btnDone error: '.$e->getMessage());
        }
        header('Location: admcita.php'); exit;
    }

    // Eliminar cita
    if (isset($_POST['btnDelete'])) {
        $id = intval($_POST['id']);
        $stmt = $conn->prepare("DELETE FROM citas WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: admcita.php');
        exit;
    }

    // Eliminar en lote
    if (isset($_POST['bulkDelete']) && !empty($_POST['ids']) && is_array($_POST['ids'])) {
        $ids = array_map('intval', $_POST['ids']);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $conn->prepare("DELETE FROM citas WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        header('Location: admcita.php');
        exit;
    }
}

// Filtros y búsqueda (GET)
$filterStatus = $_GET['status'] ?? '';
$q = trim($_GET['q'] ?? '');

$sql = "SELECT * FROM citas";
$conds = [];
$params = [];
if ($filterStatus !== '') {
    $conds[] = "status = ?";
    $params[] = $filterStatus;
}
if ($q !== '') {
    $conds[] = "(nombre LIKE ? OR servicio LIKE ? OR telefono LIKE ?)";
    $like = "%$q%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}
if (count($conds) > 0) {
    $sql .= " WHERE " . implode(' AND ', $conds);
}
$sql .= " ORDER BY fecha ASC, hora ASC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Administrador de Citas</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/tecnologia.css">
    <link rel="stylesheet" href="../assets/css/citas.css">
    <style>
        /* Pequeños ajustes específicos de administrador */
        .filter-row {
            gap: .5rem;
            align-items: center;
            margin-bottom: 1rem;
        }

        .table-actions .btn {
            margin-left: .35rem;
        }

        .bulk-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: .75rem;
            gap: .5rem;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="navbar-custom mb-4" role="navigation" aria-label="Navegación principal" style="border-radius: 0.75rem;">
            <a class="navbar-brand" href="../app/administrador.php">
                <img src="/proyecto_majoma/assets/img/logo.png" alt="logo">
                Majoma - Admin Citas
            </a>

            <!-- Enlaces: Citas | Historial | Panel -->
            <nav class="navbar-nav d-flex align-items-center gap-2" role="navigation" style="margin-left:auto;">
                <a class="nav-link <?php if(basename($_SERVER['PHP_SELF'])==='citas_history.php') echo 'active'; ?>" href="citas_history.php" style="color: #989a9cff;">Historial</a>
            </nav>
        </div>

        <div class="d-flex align-items-center mb-3">
            <h4 class="me-auto">Panel — Citas</h4>
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">Crear cita</button>
        </div>

        <form method="get" class="row filter-row">
            <div class="col-auto">
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    <option value="activa" <?php if ($filterStatus === 'activa') echo 'selected'; ?>>Activas</option>
                    <option value="cancelada" <?php if ($filterStatus === 'cancelada') echo 'selected'; ?>>Canceladas</option>
                    <option value="finalizada" <?php if ($filterStatus === 'finalizada') echo 'selected'; ?>>Finalizadas</option>
                </select>
            </div>
            <div class="col">
                <input name="q" id="searchGlobal" value="<?php echo htmlspecialchars($q); ?>" class="form-control" placeholder="Buscar por nombre, servicio o teléfono">
            </div>
            <div class="col-auto">
                <button class="btn btn-primary" type="submit">Filtrar</button>
                <a class="btn btn-outline-secondary" href="admcita.php">Limpiar</a>
            </div>
        </form>

        <form method="post" id="bulkForm">
            <div class="card">
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width:40px;"><input type="checkbox" id="selectAll"></th>
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
                                <?php if (empty($citas)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center small-muted py-4">No hay citas.</td>
                                    </tr>
                                    <?php else: foreach ($citas as $c): ?>
                                        <tr>
                                            <td><input type="checkbox" name="ids[]" value="<?php echo $c['id']; ?>" class="rowCheckbox"></td>
                                            <td><?php echo htmlspecialchars($c['id']); ?></td>
                                            <td><?php echo htmlspecialchars($c['nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($c['telefono']); ?></td>
                                            <td><?php echo htmlspecialchars($c['fecha']); ?> <span class="small-muted">/</span> <?php echo htmlspecialchars($c['hora']); ?></td>
                                            <td><?php echo htmlspecialchars($c['servicio']); ?></td>
                                            <td class="small-muted"><?php echo htmlspecialchars($c['notas']); ?></td>
                                            <td>
                                                <?php if (($c['status'] ?? '') === 'cancelada'): ?>
                                                    <span class="status-cancelada">Cancelada</span>
                                                <?php elseif (($c['status'] ?? '') === 'finalizada'): ?>
                                                    <span class="badge bg-secondary">Finalizada</span>
                                                <?php else: ?>
                                                    <span class="status-activa">Activa</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end table-actions">
                                                <button type="button" class="btn btn-sm btn-outline-secondary btn-edit"
                                                    data-id="<?php echo $c['id']; ?>"
                                                    data-nombre="<?php echo htmlspecialchars($c['nombre'], ENT_QUOTES); ?>"
                                                    data-telefono="<?php echo htmlspecialchars($c['telefono'], ENT_QUOTES); ?>"
                                                    data-fecha="<?php echo $c['fecha']; ?>"
                                                    data-hora="<?php echo $c['hora']; ?>"
                                                    data-servicio="<?php echo htmlspecialchars($c['servicio'], ENT_QUOTES); ?>"
                                                    data-notas="<?php echo htmlspecialchars($c['notas'], ENT_QUOTES); ?>"
                                                    title="Editar"><i class="fa fa-edit"></i></button>

                                                <?php if (($c['status'] ?? '') !== 'cancelada'): ?>
                                                    <form method="post" class="d-inline" onsubmit="return confirm('¿Cancelar cita #<?php echo $c['id']; ?>?');">
                                                        <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                                        <button type="submit" name="btnCancel" class="btn btn-sm btn-outline-warning" title="Cancelar"><i class="fa fa-ban"></i></button>
                                                    </form>
                                                <?php endif; ?>

                                                <?php if (($c['status'] ?? '') !== 'finalizada'): ?>
                                                    <form method="post" class="d-inline" onsubmit="return confirm('¿Marcar como finalizada la cita #<?php echo $c['id']; ?>?');">
                                                        <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                                        <button type="submit" name="btnDone" class="btn btn-sm btn-outline-success" title="Finalizar"><i class="fa fa-check"></i></button>
                                                    </form>
                                                <?php endif; ?>

                                                <form method="post" class="d-inline" onsubmit="return confirm('¿Eliminar cita #<?php echo $c['id']; ?>?');">
                                                    <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                                    <button type="submit" name="btnDelete" class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                <?php endforeach;
                                endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="bulk-footer">
                        <div>
                            <button type="button" id="btnBulkDelete" class="btn btn-outline-danger">Eliminar seleccionadas</button>
                            <span class="small-muted ms-2">Seleccionadas: <span id="selectedCount">0</span></span>
                        </div>
                        <div>
                            <a href="admcita.php" class="btn btn-outline-secondary">Actualizar</a>
                        </div>
                    </div>

                </div>
            </div>

            <!-- input hidden to submit bulk delete -->
            <input type="hidden" name="bulkDelete" value="1">
        </form>

        <!-- Modal Crear -->
        <div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form method="post" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Crear cita</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Nombre</label>
                        <input name="nombre" class="form-control" required>
                        <label class="form-label mt-2">Teléfono</label>
                        <input name="telefono" class="form-control">
                        <div class="row g-2 mt-2">
                            <div class="col-6"><label class="form-label">Fecha</label><input type="date" name="fecha" class="form-control" required></div>
                            <div class="col-6"><label class="form-label">Hora</label><input type="time" name="hora" class="form-control" required></div>
                        </div>
                        <label class="form-label mt-2">Servicio</label>
                        <input name="servicio" class="form-control">
                        <label class="form-label mt-2">Notas</label>
                        <textarea name="notas" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" name="btnCreate" class="btn btn-primary">Crear</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Editar -->
        <div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form method="post" id="formEdit" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar cita</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-id">
                        <label class="form-label">Nombre</label>
                        <input name="nombre" id="edit-nombre" class="form-control" required>
                        <label class="form-label mt-2">Teléfono</label>
                        <input name="telefono" id="edit-telefono" class="form-control">
                        <div class="row g-2 mt-2">
                            <div class="col-6"><label class="form-label">Fecha</label><input type="date" name="fecha" id="edit-fecha" class="form-control" required></div>
                            <div class="col-6"><label class="form-label">Hora</label><input type="time" name="hora" id="edit-hora" class="form-control" required></div>
                        </div>
                        <label class="form-label mt-2">Servicio</label>
                        <input name="servicio" id="edit-servicio" class="form-control">
                        <label class="form-label mt-2">Notas</label>
                        <textarea name="notas" id="edit-notas" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" name="btnUpdate" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script>
        // Edit modal rellenar
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit-id').value = this.dataset.id || '';
                document.getElementById('edit-nombre').value = this.dataset.nombre || '';
                document.getElementById('edit-telefono').value = this.dataset.telefono || '';
                document.getElementById('edit-fecha').value = this.dataset.fecha || '';
                document.getElementById('edit-hora').value = this.dataset.hora || '';
                document.getElementById('edit-servicio').value = this.dataset.servicio || '';
                document.getElementById('edit-notas').value = this.dataset.notas || '';
                new bootstrap.Modal(document.getElementById('modalEdit')).show();
            });
        });

        // Select all / tracking
        const selectAll = document.getElementById('selectAll');
        const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
        const selectedCount = document.getElementById('selectedCount');

        function updateSelectedCount() {
            const n = document.querySelectorAll('.rowCheckbox:checked').length;
            selectedCount.textContent = n;
        }

        selectAll?.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => cb.checked = this.checked);
            updateSelectedCount();
        });

        rowCheckboxes.forEach(cb => cb.addEventListener('change', updateSelectedCount));

        // Bulk delete button
        document.getElementById('btnBulkDelete')?.addEventListener('click', function() {
            const checked = Array.from(document.querySelectorAll('.rowCheckbox:checked')).map(i => i.value);
            if (checked.length === 0) {
                alert('Selecciona al menos una cita.');
                return;
            }
            if (!confirm('¿Eliminar las citas seleccionadas?')) return;
            // crear form dinámico y enviarlo
            const form = document.getElementById('bulkForm');
            // unchecked checkboxes should remain untouched; form already has checkboxes with name ids[]
            form.submit();
        });

        // Búsqueda en vivo cliente/servicio
        document.getElementById('searchGlobal')?.addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#citasBody tr').forEach(tr => {
                const text = tr.textContent.toLowerCase();
                tr.style.display = text.includes(q) ? '' : 'none';
            });
        });
    </script>
</body>

</html>