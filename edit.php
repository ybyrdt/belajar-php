<?php
require_once 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $product = $stmt->fetch();

    if (!$product) {
        header('Location: index.php');
        exit;
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4" style="max-width: 500px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Edit Produk</h4>
            <a href="index.php" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="process_edit.php">
                    <input type="hidden" name="id" value="<?= $product['id'] ?>">

                    <div class="mb-3">
                        <label for="inputProductName" class="form-label">Product Name</label>
                        <input
                            type="text"
                            class="form-control"
                            id="inputProductName"
                            name="product_name"
                            value="<?= htmlspecialchars($product['name']) ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="inputProductPrice" class="form-label">Product Price</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input
                                type="number"
                                class="form-control"
                                id="inputProductPrice"
                                name="product_price"
                                value="<?= $product['price'] ?>"
                                min="0"
                                required>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
                        <a href="index.php" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
