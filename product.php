<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id <= 0) {
    redirect('index.php');
}

// Ürünü getir
$stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    redirect('index.php');
}

// Benzer ürünleri getir
$stmt = $db->prepare("SELECT * FROM products WHERE category = ? AND id != ? LIMIT 4");
$stmt->execute([$product['category'], $product_id]);
$similar_products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?> - Lezzet Dünyası</title>
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
                        <a class="nav-link position-relative" href="cart.php">
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
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Ana Sayfa</a></li>
                <li class="breadcrumb-item"><a href="index.php?category=<?php echo $product['category']; ?>"><?php echo ucfirst($product['category']); ?></a></li>
                <li class="breadcrumb-item active"><?php echo $product['name']; ?></li>
            </ol>
        </nav>

        <!-- Ürün Detayı -->
        <div class="row">
            <div class="col-md-6">
                <img src="assets/uploads/<?php echo $product['image']; ?>" 
                     class="product-image w-100" alt="<?php echo $product['name']; ?>">
            </div>
            <div class="col-md-6">
                <h1><?php echo $product['name']; ?></h1>
                <p class="text-muted">Kategori: <?php echo ucfirst($product['category']); ?></p>
                <p class="price fs-3"><?php echo formatPrice($product['price']); ?></p>
                
                <div class="mb-4">
                    <h5>Açıklama</h5>
                    <p><?php echo nl2br($product['description']); ?></p>
                </div>
                
                <div class="mb-4">
                    <h5>Malzemeler</h5>
                    <p><?php echo $product['ingredients'] ?? 'Malzeme bilgisi mevcut değil.'; ?></p>
                </div>
                
                <div class="d-flex align-items-center mb-4">
                    <label for="quantity" class="form-label me-3">Adet:</label>
                    <input type="number" id="quantity" class="form-control quantity-input" value="1" min="1" max="10">
                </div>
                
                <div class="d-grid gap-2 d-md-flex">
                    <button onclick="addToCartWithQuantity(<?php echo $product['id']; ?>, '<?php echo $product['name']; ?>')" 
                            class="btn btn-primary btn-lg me-md-2">
                        <i class="fas fa-cart-plus"></i> Sepete Ekle
                    </button>
                    <a href="index.php" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-arrow-left"></i> Geri Dön
                    </a>
                </div>
            </div>
        </div>

        <!-- Benzer Ürünler -->
        <?php if (!empty($similar_products)): ?>
        <div class="mt-5">
            <h3>Benzer Ürünler</h3>
            <div class="row">
                <?php foreach ($similar_products as $similar): ?>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card product-card">
                            <img src="assets/uploads/<?php echo $similar['image']; ?>" 
                                 class="card-img-top" alt="<?php echo $similar['name']; ?>">
                            <div class="card-body">
                                <h6 class="card-title"><?php echo $similar['name']; ?></h6>
                                <p class="price"><?php echo formatPrice($similar['price']); ?></p>
                                <div class="d-flex justify-content-between">
                                    <a href="product.php?id=<?php echo $similar['id']; ?>" 
                                       class="btn btn-sm btn-outline-primary">Detay</a>
                                    <button onclick="addToCart(<?php echo $similar['id']; ?>, '<?php echo $similar['name']; ?>')" 
                                            class="btn btn-sm btn-primary">Sepete Ekle</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
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
    <script>
        function addToCartWithQuantity(productId, productName) {
            const quantity = document.getElementById('quantity').value;
            
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_id=' + productId + '&quantity=' + quantity
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', quantity + ' adet ' + productName + ' sepete eklendi!');
                    updateCartCount();
                } else {
                    showAlert('danger', 'Bir hata oluştu!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'Bir hata oluştu!');
            });
        }
    </script>
</body>
</html>

