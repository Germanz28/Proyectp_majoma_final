<?php
session_start();
// Simulación de productos en el carrito (puedes reemplazar por tu lógica de base de datos)
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Eliminar producto del carrito
if (isset($_GET['remove'])) {
    $removeId = $_GET['remove'];
    unset($cart[$removeId]);
    $_SESSION['cart'] = $cart;
    header('Location: car.php');
    exit();
}

// Calcular total
$total = 0;
foreach ($cart as $item) {
    $total += $item['precio'] * $item['cantidad'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Carrito de Compras</h2>
        <?php if (empty($cart)): ?>
            <div class="alert alert-info text-center">Tu carrito está vacío.</div>
        <?php else: ?>
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $id => $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                            <td>$<?php echo number_format($item['precio'], 2); ?></td>
                            <td><?php echo $item['cantidad']; ?></td>
                            <td>$<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></td>
                            <td>
                                <a href="car.php?remove=<?php echo $id; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="text-end">
                <h4>Total: $<?php echo number_format($total, 2); ?></h4>
                <a href="#" class="btn btn-success">Finalizar compra</a>
            </div>
        <?php endif; ?>
        <a href="../index.html" class="btn btn-secondary mt-3">Seguir comprando</a>
    </div>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
