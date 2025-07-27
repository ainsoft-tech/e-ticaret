<?php
require_once '../includes/db.php';
require_once 'includes/admin_auth.php';

$total_products = $db->query('SELECT COUNT(*) FROM products')->fetchColumn();
$total_orders = $db->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$total_revenue = $db->query('SELECT SUM(total_price) FROM orders WHERE status = "completed"')->fetchColumn();

$recent_orders = $db->query('SELECT * FROM orders ORDER BY order_date DESC LIMIT 5')->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="container mt-5">
    <h2>Yönetici Paneli</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Toplam Ürün</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $total_products; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Toplam Sipariş</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $total_orders; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Toplam Gelir</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo number_format($total_revenue, 2); ?> TL</h5>
                </div>
            </div>
        </div>
    </div>

    <h3>Son 5 Sipariş</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sipariş ID</th>
                <th>Kullanıcı ID</th>
                <th>Toplam Fiyat</th>
                <th>Durum</th>
                <th>Tarih</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recent_orders as $order): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo $order['user_id']; ?></td>
                    <td><?php echo number_format($order['total_price'], 2); ?> TL</td>
                    <td><?php echo $order['status']; ?></td>
                    <td><?php echo $order['order_date']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>

