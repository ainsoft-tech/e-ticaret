# Lezzet Dünyası - Yemek E-Ticaret Sitesi

Modern ve mobil uyumlu PHP + SQLite3 + Bootstrap 5 tabanlı yemek siparişi e-ticaret sitesi.

## 🚀 Özellikler

### Kullanıcı Özellikleri
- ✅ Ürün listeleme ve detay görüntüleme
- ✅ Kategori bazlı filtreleme (Pizza, Burger, Salata, Tatlı, İçecek)
- ✅ Arama ve fiyat filtresi
- ✅ Sepete ürün ekleme/çıkarma
- ✅ Kullanıcı kayıt ve giriş sistemi
- ✅ Responsive tasarım (mobil uyumlu)

### Admin Özellikleri
- ✅ Admin paneli (dashboard)
- ✅ Ürün yönetimi (ekleme, düzenleme, silme)
- ✅ Sipariş takibi
- ✅ İstatistikler

### Teknik Özellikler
- ✅ PHP 8.1+ uyumlu
- ✅ SQLite3 veritabanı
- ✅ Bootstrap 5 responsive framework
- ✅ Font Awesome ikonları
- ✅ AJAX ile dinamik işlemler
- ✅ Session tabanlı sepet yönetimi
- ✅ XSS koruması

## 📁 Proje Yapısı

```
yemek-e-ticaret-sitesi/
├── admin/                  # Admin paneli
│   ├── dashboard.php      # Ana kontrol paneli
│   ├── products.php       # Ürün yönetimi
│   └── includes/
│       └── admin_auth.php # Admin kimlik doğrulama
├── assets/
│   ├── css/
│   │   └── style.css      # Ana stil dosyası
│   ├── js/
│   │   └── script.js      # JavaScript fonksiyonları
│   └── uploads/           # Ürün görselleri
├── includes/
│   ├── db.php            # Veritabanı bağlantısı
│   ├── functions.php     # Yardımcı fonksiyonlar
│   └── auth.php          # Kimlik doğrulama
├── database/
│   └── yemek_eticaret.db # SQLite veritabanı
├── index.php             # Ana sayfa
├── product.php           # Ürün detay sayfası
├── cart.php              # Sepet sayfası
├── login.php             # Giriş sayfası
├── register.php          # Kayıt sayfası
└── setup_database.php    # Veritabanı kurulum scripti
```

## 🛠️ Kurulum

### 1. Gereksinimler
- PHP 8.1+
- SQLite3 extension
- Web server (Apache/Nginx) veya PHP built-in server

### 2. Kurulum Adımları

```bash
# Projeyi klonlayın veya indirin
git clone [repository-url]

# Proje dizinine gidin
cd yemek-e-ticaret-sitesi

# Veritabanını kurun
php setup_database.php

# PHP built-in server ile çalıştırın
php -S localhost:8000
```

### 3. Varsayılan Admin Hesabı
- **Kullanıcı Adı:** admin
- **Şifre:** admin123

## 🎨 Tasarım

### Renk Paleti
- **Primary:** #e74c3c (Kırmızı)
- **Secondary:** #f39c12 (Turuncu)
- **Success:** #27ae60 (Yeşil)
- **Dark:** #2c3e50 (Koyu Gri)

### Responsive Breakpoints
- **Desktop:** 1200px+
- **Tablet:** 768px - 1199px
- **Mobile:** 767px ve altı

## 📱 Mobil Uyumluluk

Site Bootstrap 5'in responsive grid sistemi kullanılarak tasarlanmıştır:
- Mobil öncelikli tasarım
- Touch-friendly butonlar
- Optimized görsel boyutları
- Hamburger menü (mobilde)

## 🔧 API Endpoints

### Sepet İşlemleri
- `POST /add_to_cart.php` - Sepete ürün ekleme
- `POST /remove_from_cart.php` - Sepetten ürün çıkarma
- `POST /update_cart.php` - Sepet miktarını güncelleme
- `GET /get_cart_count.php` - Sepet ürün sayısını alma

## 🗄️ Veritabanı Yapısı

### Tablolar
- **users** - Kullanıcı bilgileri
- **products** - Ürün bilgileri
- **orders** - Sipariş bilgileri
- **order_items** - Sipariş detayları

## 🔒 Güvenlik

- Password hashing (PHP password_hash)
- XSS koruması (htmlspecialchars)
- SQL Injection koruması (PDO prepared statements)
- Session güvenliği

## 📊 Örnek Veriler

Sistem 12 örnek ürün ile gelir:
- 2 Pizza çeşidi
- 2 Burger çeşidi
- 2 Salata çeşidi
- 2 Tatlı çeşidi
- 4 İçecek çeşidi

## 🚀 Deployment

Proje PHP built-in server veya herhangi bir web server (Apache, Nginx) üzerinde çalışabilir.

### Production Önerileri
- HTTPS kullanın
- Güçlü admin şifresi belirleyin
- Dosya upload güvenliği ekleyin
- Rate limiting implementasyonu
- Backup stratejisi oluşturun

## 📝 Lisans

Bu proje MIT lisansı altında lisanslanmıştır.

## 🤝 Katkıda Bulunma

1. Fork edin
2. Feature branch oluşturun
3. Değişikliklerinizi commit edin
4. Pull request gönderin

## 📞 İletişim

Sorularınız için: info@lezzetdunyasi.com

# Online E-Ticaret Yemek Sitesi
