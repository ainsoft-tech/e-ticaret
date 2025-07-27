<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Arama ve filtreleme parametreleri
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$category = isset($_GET['category']) ? sanitize($_GET['category']) : '';
$min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 999999;

// Ürünleri getir
$products = getProducts($db, $search, $category, $min_price, $max_price);

// Kategorileri getir
$categories = $db->query("SELECT DISTINCT category FROM products ORDER BY category")->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lezzet Dünyası - Yemek Siparişi</title>
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
                        <a class="nav-link active" href="index.php">Ana Sayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#menu">Menü</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Hakkımızda</a>
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

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1>Lezzet Dünyasına Hoş Geldiniz</h1>
            <p>En taze malzemelerle hazırlanan nefis yemekleri kapınıza kadar getiriyoruz</p>
            <a href="#menu" class="btn btn-light btn-lg">
                <i class="fas fa-utensils"></i> Menüyü İncele
            </a>
        </div>
    </section>

    <div class="container my-5">
        <!-- Arama ve Filtreleme -->
        <div class="search-form">
            <form method="GET" id="searchForm">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="search" id="searchInput" 
                               placeholder="Yemek ara..." value="<?php echo $search; ?>">
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="category">
                            <option value="">Tüm Kategoriler</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['category']; ?>" 
                                        <?php echo $category === $cat['category'] ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($cat['category']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="min_price" 
                               placeholder="Min Fiyat" value="<?php echo $min_price > 0 ? $min_price : ''; ?>">
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="max_price" 
                               placeholder="Max Fiyat" value="<?php echo $max_price < 999999 ? $max_price : ''; ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Ara
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Kategori Filtreleri -->
        <div class="category-filter">
            <button class="btn btn-outline-primary category-btn <?php echo empty($category) ? 'active' : ''; ?>" 
                    data-category="all">Tümü</button>
            <?php foreach ($categories as $cat): ?>
                <button class="btn btn-outline-primary category-btn <?php echo $category === $cat['category'] ? 'active' : ''; ?>" 
                        data-category="<?php echo $cat['category']; ?>">
                    <?php echo ucfirst($cat['category']); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Ürünler -->
        <section id="menu">
            <h2 class="text-center mb-4">Menümüz</h2>
            
            <?php if (empty($products)): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Aradığınız kriterlere uygun ürün bulunamadı.
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-4 col-sm-6 mb-4">
                            <div class="card product-card">
                                <img src="assets/uploads/<?php echo $product['image']; ?>" 
                                     class="card-img-top" alt="<?php echo $product['name']; ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $product['name']; ?></h5>
                                    <p class="card-text"><?php echo substr($product['description'], 0, 100); ?>...</p>
                                    <p class="price"><?php echo formatPrice($product['price']); ?></p>
                                    <div class="d-flex justify-content-between">
                                        <a href="product.php?id=<?php echo $product['id']; ?>" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i> Detay
                                        </a>
                                        <button onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo $product['name']; ?>')" 
                                                class="btn btn-primary">
                                            <i class="fas fa-cart-plus"></i> Sepete Ekle
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
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

