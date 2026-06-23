<?php
include 'config.php';

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Daftar Produk</h4>
            <a href="add.php" class="btn btn-primary">+ Tambah Produk</a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php
                $msg = $_GET['success'];
                if ($msg === 'deleted') echo 'Produk berhasil dihapus.';
                elseif ($msg === 'updated') echo 'Produk berhasil diperbarui.';
                elseif ($msg === 'added') echo 'Produk berhasil ditambahkan.';
                elseif ($msg === 'stok_updated') echo 'Stok berhasil diperbarui.';
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error']) && $_GET['error'] === 'stok_kurang'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Stok tidak mencukupi untuk dikurangi!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <table class="table table-light table-hover align-middle">
            <thead>
                <tr>
                    <th class="table-primary">#</th>
                    <th class="table-primary">SKU</th>
                    <th class="table-primary">Product Name</th>
                    <th class="table-primary">Price</th>
                    <th class="table-primary text-center">Stock</th>
                    <th class="table-primary text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $i => $product): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($product['sku']) ?></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td>Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                        <td class="text-center">
                            <span class="badge bg-<?= $product['stock'] > 0 ? 'success' : 'secondary' ?> fs-6">
                                <?= $product['stock'] ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="update_stok.php?id=<?= $product['id'] ?>&aksi=tambah" class="btn btn-success btn-sm me-1">
                                + Stok
                            </a>
                            <a href="update_stok.php?id=<?= $product['id'] ?>&aksi=kurangi" class="btn btn-warning btn-sm me-1">
                                - Stok
                            </a>
                            <a href="edit.php?id=<?= $product['id'] ?>" class="btn btn-info btn-sm me-1">
                                ✏️ Edit
                            </a>
                            <button
                                class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal"
                                data-id="<?= $product['id'] ?>"
                                data-name="<?= htmlspecialchars($product['name']) ?>">
                                🗑️ Delete
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Konfirmasi Delete -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus produk <strong id="deleteProductName"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a id="deleteConfirmBtn" href="#" class="btn btn-danger">Ya, Hapus</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            document.getElementById('deleteProductName').textContent = name;
            document.getElementById('deleteConfirmBtn').href = 'process_delete.php?id=' + id;
        });
    </script>
</body>

</html>