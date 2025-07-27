// Sepete ürün ekleme
function addToCart(productId, productName) {
    fetch('add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'product_id=' + productId + '&quantity=1'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', productName + ' sepete eklendi!');
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

// Sepetten ürün çıkarma
function removeFromCart(productId) {
    if (confirm('Bu ürünü sepetten çıkarmak istediğinizden emin misiniz?')) {
        fetch('remove_from_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'product_id=' + productId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                showAlert('danger', 'Bir hata oluştu!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Bir hata oluştu!');
        });
    }
}

// Sepet sayısını güncelleme
function updateCartCount() {
    fetch('get_cart_count.php')
    .then(response => response.json())
    .then(data => {
        const cartBadge = document.querySelector('.badge-cart');
        if (cartBadge) {
            cartBadge.textContent = data.count;
            if (data.count > 0) {
                cartBadge.style.display = 'inline';
            } else {
                cartBadge.style.display = 'none';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Alert gösterme
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);
        
        // 3 saniye sonra otomatik kapat
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
}

// Miktar değiştirme
function updateQuantity(productId, quantity) {
    if (quantity < 1) {
        removeFromCart(productId);
        return;
    }
    
    fetch('update_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'product_id=' + productId + '&quantity=' + quantity
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            showAlert('danger', 'Bir hata oluştu!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Bir hata oluştu!');
    });
}

// Sayfa yüklendiğinde
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
    
    // Arama formu
    const searchForm = document.querySelector('#searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = document.querySelector('#searchInput');
            if (searchInput && searchInput.value.trim() === '') {
                e.preventDefault();
                showAlert('warning', 'Lütfen arama terimi girin!');
            }
        });
    }
    
    // Kategori filtreleme
    const categoryButtons = document.querySelectorAll('.category-btn');
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.dataset.category;
            const currentUrl = new URL(window.location);
            if (category === 'all') {
                currentUrl.searchParams.delete('category');
            } else {
                currentUrl.searchParams.set('category', category);
            }
            window.location.href = currentUrl.toString();
        });
    });
    
    // Fiyat filtresi
    const priceForm = document.querySelector('#priceForm');
    if (priceForm) {
        priceForm.addEventListener('submit', function(e) {
            const minPrice = document.querySelector('#minPrice').value;
            const maxPrice = document.querySelector('#maxPrice').value;
            
            if (minPrice && maxPrice && parseFloat(minPrice) > parseFloat(maxPrice)) {
                e.preventDefault();
                showAlert('warning', 'Minimum fiyat maksimum fiyattan büyük olamaz!');
            }
        });
    }
});

// Form validasyonu
function validateForm(formId) {
    const form = document.querySelector('#' + formId);
    if (!form) return false;
    
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    return isValid;
}

// Email validasyonu
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Telefon validasyonu
function validatePhone(phone) {
    const re = /^[0-9]{10,11}$/;
    return re.test(phone.replace(/\s/g, ''));
}

// Loading gösterme/gizleme
function showLoading() {
    const loading = document.querySelector('.loading');
    if (loading) {
        loading.style.display = 'block';
    }
}

function hideLoading() {
    const loading = document.querySelector('.loading');
    if (loading) {
        loading.style.display = 'none';
    }
}

