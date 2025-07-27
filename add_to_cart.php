<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Geçersiz istek']);
    exit;
}

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

if ($product_id <= 0 || $quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Geçersiz parametreler']);
    exit;
}

// Ürünün varlığını kontrol et
$stmt = $db->prepare("SELECT id, name, stock_quantity FROM products WHERE id = ? AND is_active = 1");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Ürün bulunamadı']);
    exit;
}

if ($product['stock_quantity'] < $quantity) {
    echo json_encode(['success' => false, 'message' => 'Yeterli stok yok']);
    exit;
}

// Sepete ekle
addToCart($product_id, $quantity);

echo json_encode(['success' => true, 'message' => 'Ürün sepete eklendi']);
?>

