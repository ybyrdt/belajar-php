<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id           = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $productName  = $_POST['product_name'] ?? '';
    $productPrice = $_POST['product_price'] ?? '';

    if ($id <= 0 || empty($productName) || empty($productPrice)) {
        echo "<script>alert('Data tidak lengkap atau ID tidak valid.'); window.location.href='index.php';</script>";
        exit;
    }

    try {
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("UPDATE products SET name = :name, price = :price WHERE id = :id");
        $stmt->execute([
            ':name'  => $productName,
            ':price' => $productPrice,
            ':id'    => $id,
        ]);

        header('Location: index.php?success=updated');
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.location.href='index.php';</script>";
    }
} else {
    header('Location: index.php');
    exit;
}
