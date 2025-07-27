<?php
require_once 'includes/auth.php';

header('Content-Type: application/json');

$count = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $count = array_sum($_SESSION['cart']);
}

echo json_encode(['count' => $count]);
?>

