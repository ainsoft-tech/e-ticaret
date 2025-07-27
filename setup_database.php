<?php
require_once 'includes/db.php';

try {
    // Users tablosu
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        address TEXT,
        is_admin BOOLEAN DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Products tablosu
    $db->exec("CREATE TABLE IF NOT EXISTS products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        category VARCHAR(50) NOT NULL,
        image VARCHAR(255),
        ingredients TEXT,
        stock_quantity INTEGER DEFAULT 0,
        is_active BOOLEAN DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Orders tablosu
    $db->exec("CREATE TABLE IF NOT EXISTS orders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        total_price DECIMAL(10,2) NOT NULL,
        status VARCHAR(20) DEFAULT 'pending',
        delivery_address TEXT NOT NULL,
        phone VARCHAR(20) NOT NULL,
        notes TEXT,
        order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )");

    // Order_items tablosu
    $db->exec("CREATE TABLE IF NOT EXISTS order_items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        order_id INTEGER NOT NULL,
        product_id INTEGER NOT NULL,
        quantity INTEGER NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        FOREIGN KEY (order_id) REFERENCES orders(id),
        FOREIGN KEY (product_id) REFERENCES products(id)
    )");

    // Admin kullanıcısı ekle
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT OR IGNORE INTO users (username, email, password, full_name, is_admin) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute(['admin', 'admin@lezzetdunyasi.com', $admin_password, 'Sistem Yöneticisi', 1]);

    // Örnek ürünler ekle
    $products = [
        [
            'name' => 'Margherita Pizza',
            'description' => 'Klasik İtalyan pizzası. Taze mozzarella, domates sosu ve fesleğen ile hazırlanır.',
            'price' => 45.90,
            'category' => 'pizza',
            'image' => 'pizza-margherita.jpg',
            'ingredients' => 'Pizza hamuru, domates sosu, mozzarella peyniri, taze fesleğen, zeytinyağı',
            'stock_quantity' => 50
        ],
        [
            'name' => 'Pepperoni Pizza',
            'description' => 'Baharatlı pepperoni dilimleri ile lezzetli pizza.',
            'price' => 52.90,
            'category' => 'pizza',
            'image' => 'pizza-margherita.jpg',
            'ingredients' => 'Pizza hamuru, domates sosu, mozzarella peyniri, pepperoni, oregano',
            'stock_quantity' => 45
        ],
        [
            'name' => 'Klasik Burger',
            'description' => 'Sulu dana eti, taze sebzeler ve özel soslarla hazırlanan nefis burger.',
            'price' => 38.50,
            'category' => 'burger',
            'image' => 'classic-burger.jpg',
            'ingredients' => 'Dana eti, burger ekmeği, marul, domates, soğan, turşu, özel sos',
            'stock_quantity' => 60
        ],
        [
            'name' => 'Cheeseburger',
            'description' => 'Klasik burgere erimiş cheddar peyniri eklenerek hazırlanır.',
            'price' => 42.90,
            'category' => 'burger',
            'image' => 'classic-burger.jpg',
            'ingredients' => 'Dana eti, cheddar peyniri, burger ekmeği, marul, domates, soğan, özel sos',
            'stock_quantity' => 55
        ],
        [
            'name' => 'Yunan Salatası',
            'description' => 'Taze sebzeler, zeytin, feta peyniri ve zeytinyağı ile hazırlanan sağlıklı salata.',
            'price' => 28.90,
            'category' => 'salata',
            'image' => 'greek-salad.jpg',
            'ingredients' => 'Domates, salatalık, soğan, zeytin, feta peyniri, zeytinyağı, limon',
            'stock_quantity' => 40
        ],
        [
            'name' => 'Caesar Salata',
            'description' => 'Çıtır marul yaprakları, parmesan peyniri ve özel Caesar sosu ile.',
            'price' => 32.50,
            'category' => 'salata',
            'image' => 'greek-salad.jpg',
            'ingredients' => 'Marul, parmesan peyniri, kruton, Caesar sosu, limon',
            'stock_quantity' => 35
        ],
        [
            'name' => 'Çikolatalı Kek',
            'description' => 'Yoğun çikolata tadı ile hazırlanan nefis kek dilimi.',
            'price' => 18.90,
            'category' => 'tatlı',
            'image' => 'chocolate-cake.jpg',
            'ingredients' => 'Çikolata, un, yumurta, şeker, tereyağı, kakao',
            'stock_quantity' => 25
        ],
        [
            'name' => 'Cheesecake',
            'description' => 'Kremsi dokusu ile ünlü Amerikan tarzı cheesecake.',
            'price' => 22.50,
            'category' => 'tatlı',
            'image' => 'chocolate-cake.jpg',
            'ingredients' => 'Krem peynir, yumurta, şeker, bisküvi, tereyağı, vanilya',
            'stock_quantity' => 20
        ],
        [
            'name' => 'Taze Meyve Suyu',
            'description' => 'Günlük sıkılan taze portakal suyu.',
            'price' => 12.90,
            'category' => 'içecek',
            'image' => 'fresh-juice.jpg',
            'ingredients' => 'Taze portakal',
            'stock_quantity' => 100
        ],
        [
            'name' => 'Karışık Meyve Suyu',
            'description' => 'Portakal, elma ve havuç karışımı vitamin deposu.',
            'price' => 15.50,
            'category' => 'içecek',
            'image' => 'fresh-juice.jpg',
            'ingredients' => 'Portakal, elma, havuç',
            'stock_quantity' => 80
        ],
        [
            'name' => 'Türk Kahvesi',
            'description' => 'Geleneksel yöntemle pişirilen Türk kahvesi.',
            'price' => 8.90,
            'category' => 'içecek',
            'image' => 'fresh-juice.jpg',
            'ingredients' => 'Türk kahvesi, şeker (isteğe bağlı)',
            'stock_quantity' => 200
        ],
        [
            'name' => 'Cappuccino',
            'description' => 'İtalyan usulü hazırlanan köpüklü cappuccino.',
            'price' => 14.50,
            'category' => 'içecek',
            'image' => 'fresh-juice.jpg',
            'ingredients' => 'Espresso, süt, süt köpüğü, tarçın',
            'stock_quantity' => 150
        ]
    ];

    $stmt = $db->prepare("INSERT OR IGNORE INTO products (name, description, price, category, image, ingredients, stock_quantity) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($products as $product) {
        $stmt->execute([
            $product['name'],
            $product['description'],
            $product['price'],
            $product['category'],
            $product['image'],
            $product['ingredients'],
            $product['stock_quantity']
        ]);
    }

    echo "Veritabanı başarıyla kuruldu!<br>";
    echo "Admin kullanıcısı: admin / admin123<br>";
    echo "Toplam " . count($products) . " ürün eklendi.<br>";
    echo "<a href='index.php'>Ana sayfaya git</a>";

} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>

