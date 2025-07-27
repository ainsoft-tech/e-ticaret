<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

$cart_items = getCartItems($db);
$cart_total = getCartTotal($db);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepetim - Lezzet Dünyası</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-utensils"></i> Lezzet Dünyası
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Ana Sayfa</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link position-relative active" href="cart.php">
                            <i class="fas fa-shopping-cart"></i> Sepet
                            <span class="badge badge-cart bg-danger rounded-pill" style="display: none;">0</span>
                        </a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">
                                <i class="fas fa-user"></i> Profil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="fas fa-sign-out-alt"></i> Çıkış
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">
                                <i class="fas fa-sign-in-alt"></i> Giriş
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">
                                <i class="fas fa-user-plus"></i> Kayıt
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h2><i class="fas fa-shopping-cart"></i> Sepetim</h2>
        
        <?php if (empty($cart_items)): ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                <h4>Sepetiniz boş</h4>
                <p>Henüz sepetinize ürün eklemediniz.</p>
                <a href="index.php" class="btn btn-primary">
                    <i class="fas fa-utensils"></i> Alışverişe Başla
                </a>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <?php foreach ($cart_items as $item): ?>
                                <div class="cart-item">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            <img src="assets/uploads/<?php echo $item['image']; ?>" 
                                                 class="img-fluid rounded" alt="<?php echo $item['name']; ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <h6><?php echo $item['name']; ?></h6>
                                            <p class="text-muted small"><?php echo substr($item['description'], 0, 80); ?>...</p>
                                        </div>
                                        <div class="col-md-2">
                                            <p class="price"><?php echo formatPrice($item['price']); ?></p>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <button class="btn btn-outline-secondary btn-sm" type="button" 
                                                        onclick="updateQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity'] - 1; ?>)">-</button>
                                                <input type="text" class="form-control text-center quantity-input" 
                                                       value="<?php echo $item['quantity']; ?>" readonly>
                                                <button class="btn btn-outline-secondary btn-sm" type="button" 
                                                        onclick="updateQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity'] + 1; ?>)">+</button>
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <p class="price"><?php echo formatPrice($item['subtotal']); ?></p>
                                            <button class="btn btn-danger btn-sm" 
                                                    onclick="removeFromCart(<?php echo $item['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="cart-summary">
                        <h5>Sipariş Özeti</h5>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span>Ara Toplam:</span>
                            <span><?php echo formatPrice($cart_total); ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Teslimat:</span>
                            <span>Ücretsiz</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Toplam:</strong>
                            <strong class="price"><?php echo formatPrice($cart_total); ?></strong>
                        </div>
                        
                        <div class="d-grid gap-2 mt-3">
                            <?php if (isLoggedIn()): ?>
                                <a href="checkout.php" class="btn btn-primary btn-lg">
                                    <i class="fas fa-credit-card"></i> Siparişi Tamamla
                                </a>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt"></i> Giriş Yapın
                                </a>
                                <p class="text-muted small text-center">Sipariş vermek için giriş yapmanız gerekiyor</p>
                            <?php endif; ?>
                            
                            <a href="index.php" class="btn btn-outline-primary">
                                <i class="fas fa-plus"></i> Alışverişe Devam
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Lezzet Dünyası</h5>
                    <p>En taze malzemelerle hazırlanan nefis yemekleri kapınıza kadar getiriyoruz.</p>
                </div>
                <div class="col-md-4">
                    <h5>İletişim</h5>
                    <p><i class="fas fa-phone"></i> +90 555 123 45 67</p>
                    <p><i class="fas fa-envelope"></i> info@lezzetdunyasi.com</p>
                    <p><i class="fas fa-map-marker-alt"></i> İstanbul, Türkiye</p>
                </div>
                <div class="col-md-4">
                    <h5>Çalışma Saatleri</h5>
                    <p>Pazartesi - Pazar: 09:00 - 23:00</p>
                    <p>Teslimat: 11:00 - 22:00</p>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; 2024 Lezzet Dünyası. Tüm hakları saklıdır.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>

