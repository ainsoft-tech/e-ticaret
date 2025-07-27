<?php
require_once 'includes/functions.php';
require_once 'includes/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Geçersiz istek']);
    exit;
}

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

if ($product_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Geçersiz ürün ID']);
    exit;
}

removeFromCart($product_id);

echo json_encode(['success' => true, 'message' => 'Ürün sepetten çıkarıldı']);
?>

