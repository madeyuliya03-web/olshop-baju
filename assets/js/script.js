// Format harga ke Rupiah
function formatRupiah(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

// Tambah ke keranjang
function addToCart(produkId) {
    fetch('ajax/add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'produk_id=' + produkId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Produk berhasil ditambahkan ke keranjang', 'success');
            updateCartCount();
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan', 'error');
    });
}

// Ubah jumlah di keranjang
function updateCartQuantity(cartId, quantity) {
    if (quantity < 1) return;
    
    fetch('ajax/update_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'cart_id=' + cartId + '&quantity=' + quantity
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            showNotification(data.message, 'error');
        }
    });
}

// Hapus dari keranjang
function removeFromCart(cartId) {
    if (confirm('Yakin ingin menghapus produk ini?')) {
        fetch('ajax/remove_from_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'cart_id=' + cartId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Produk berhasil dihapus', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(data.message, 'error');
            }
        });
    }
}

// Update cart count di navbar
function updateCartCount() {
    fetch('ajax/get_cart_count.php')
    .then(response => response.json())
    .then(data => {
        const cartBadge = document.querySelector('.cart-badge');
        if (cartBadge) {
            if (data.count > 0) {
                cartBadge.textContent = data.count;
            } else {
                cartBadge.remove();
            }
        }
    });
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = 'alert alert-' + type;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
    `;
    
    document.body.insertBefore(notification, document.body.firstChild);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Validasi form
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;

    const inputs = form.querySelectorAll('input[required], textarea[required]');
    for (let input of inputs) {
        if (!input.value.trim()) {
            showNotification('Semua field harus diisi', 'warning');
            input.focus();
            return false;
        }
    }
    return true;
}

// Search produk
function searchProducts(query) {
    const formData = new FormData();
    formData.append('search', query);

    fetch('ajax/search_products.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        displayProducts(data);
    });
}

// Tampilkan produk dari hasil search
function displayProducts(products) {
    const container = document.querySelector('.products-grid');
    if (!container) return;

    if (products.length === 0) {
        container.innerHTML = '<p class="text-center">Produk tidak ditemukan</p>';
        return;
    }

    container.innerHTML = products.map(product => `
        <div class="product-card">
            <div class="product-image">
                ${product.gambar ? `<img src="uploads/${product.gambar}" alt="${product.nama}">` : '<i class="fas fa-image"></i>'}
            </div>
            <div class="product-info">
                <div class="product-name">${product.nama}</div>
                <div class="product-category">${product.kategori}</div>
                <div class="product-description">${product.deskripsi.substring(0, 50)}...</div>
                <div class="product-price">${formatRupiah(product.harga)}</div>
                <div class="product-stok">
                    ${product.stok > 0 
                        ? `<span class="stok-tersedia">Stok: ${product.stok}</span>` 
                        : '<span class="stok-habis">Stok Habis</span>'}
                </div>
                <div class="product-actions">
                    <a href="detail.php?id=${product.id}" class="btn btn-secondary">
                        <i class="fas fa-eye"></i> Lihat
                    </a>
                    ${product.stok > 0 
                        ? `<button class="btn btn-primary" onclick="addToCart(${product.id})">
                            <i class="fas fa-shopping-cart"></i> Keranjang
                        </button>` 
                        : '<button class="btn btn-secondary" disabled>Habis</button>'}
                </div>
            </div>
        </div>
    `).join('');
}

// DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi event listeners
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                searchProducts(this.value);
            }
        });
    }
});
