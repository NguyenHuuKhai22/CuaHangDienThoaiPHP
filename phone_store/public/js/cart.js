document.addEventListener('DOMContentLoaded', function() {
    // Xử lý nút thêm vào giỏ hàng
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            addToCart(productId);
        });
    });
});

function addToCart(productId) {
    console.log('Adding to cart:', productId);
    
    // Get the CSRF token from the meta tag
    const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
    if (!csrfTokenElement) {
        console.error('CSRF token meta tag not found');
        alert('Có lỗi xảy ra khi thêm vào giỏ hàng. Vui lòng thử lại sau.');
        return;
    }
    
    const csrfToken = csrfTokenElement.content;
    
    fetch(`/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ quantity: 1 })
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            if (response.status === 401) {
                // User is not logged in, redirect to login page
                if (confirm('Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng. Bạn có muốn đăng nhập ngay bây giờ?')) {
                    window.location.href = '/login';
                }
                return Promise.reject('Unauthorized');
            }
            return response.json().then(data => {
                throw new Error(data.message || 'Network response was not ok');
            });
        }
        return response.json();
    })
    .then(data => {
        if (!data) return; // Skip if we redirected to login
        
        console.log('Response data:', data);
        if (data.success) {
            alert('Sản phẩm đã được thêm vào giỏ hàng');
            if (data.cartCount !== undefined) {
                // Update the cart count badge in the header
                const cartBadges = document.querySelectorAll('a[href="/cart"] .badge');
                cartBadges.forEach(badge => {
                    badge.textContent = data.cartCount;
                });
                
                // Nếu không tìm thấy badge, thử tìm bằng cách khác
                if (cartBadges.length === 0) {
                    const allBadges = document.querySelectorAll('.badge');
                    allBadges.forEach(badge => {
                        // Kiểm tra xem badge có nằm trong thẻ a có href chứa "cart" không
                        const parentLink = badge.closest('a[href*="cart"]');
                        if (parentLink) {
                            badge.textContent = data.cartCount;
                        }
                    });
                }
            }
        } else {
            alert(data.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (error === 'Unauthorized') {
            // Already handled in the response check
            return;
        }
        alert('Có lỗi xảy ra khi thêm vào giỏ hàng. Vui lòng thử lại sau.');
    });
} 