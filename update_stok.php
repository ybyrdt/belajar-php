<?php
include 'config.php';

$pdo = new PDO($dsn, $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id   = $_GET['id'];
$aksi = $_GET['aksi'];

// Ambil stock sekarang
$stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();
$stok_sekarang = $product['stock'];

// Logika tambah / kurangi
if ($aksi === 'tambah') {
    $stok_baru = $stok_sekarang + 1;
} elseif ($aksi === 'kurangi') {
    if ($stok_sekarang <= 0) {
        header("Location: index.php?error=stok_kurang");
        exit;
    }
    $stok_baru = $stok_sekarang - 1;
}

// Simpan ke database
$update = $pdo->prepare("UPDATE products SET stock = ? WHERE id = ?");
$update->execute([$stok_baru, $id]);

header("Location: index.php?success=stok_updated");
exit;
