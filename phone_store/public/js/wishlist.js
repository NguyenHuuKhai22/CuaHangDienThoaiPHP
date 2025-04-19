document.addEventListener('DOMContentLoaded', function() {
    // Cấu hình Toastr
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    // Xử lý nút thêm vào danh sách yêu thích
    document.querySelectorAll('.add-to-wishlist').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            addToWishlist(productId);
        });
    });

    // Xử lý nút xóa khỏi danh sách yêu thích
    document.querySelectorAll('.remove-from-wishlist').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            removeFromWishlist(productId);
        });
    });

    // Kiểm tra trạng thái wishlist khi trang tải
    const wishlistButton = document.querySelector('.add-to-wishlist');
    if (wishlistButton) {
        const productId = wishlistButton.dataset.productId;
        checkWishlistStatus(productId);
    }
});

function checkWishlistStatus(productId) {
    fetch(`/wishlist/check/${productId}`)
        .then(response => response.json())
        .then(data => {
            updateWishlistButton(data.isInWishlist);
        })
        .catch(error => {
            console.error('Error checking wishlist status:', error);
        });
}

function updateWishlistButton(isInWishlist) {
    const button = document.querySelector('.add-to-wishlist');
    if (!button) return;

    button.dataset.inWishlist = isInWishlist;
    const icon = button.querySelector('i');
    const text = button.querySelector('.wishlist-text');

    if (isInWishlist) {
       
        button.classList.add('active');
        icon.classList.remove('bi-heart');
        icon.classList.add('bi-heart-fill', 'text-danger');
        text.textContent = 'Xóa khỏi yêu thích';
    } else {
        button.classList.remove('active');
        icon.classList.remove('bi-heart-fill', 'text-danger');
        icon.classList.add('bi-heart');
        text.textContent = 'Thêm vào yêu thích';
    }
}

function addToWishlist(productId) {
    // Get the CSRF token from the meta tag
    const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
    if (!csrfTokenElement) {
        console.error('CSRF token meta tag not found');
        alert('Có lỗi xảy ra khi thêm vào danh sách yêu thích. Vui lòng thử lại sau.');
        return;
    }
    
    const csrfToken = csrfTokenElement.content;
    const button = document.querySelector(`.add-to-wishlist[data-product-id="${productId}"]`);
    const isInWishlist = button.dataset.inWishlist === 'true';
    
    fetch(`/wishlist/${isInWishlist ? 'remove' : 'add'}/${productId}`, {
        method: isInWishlist ? 'DELETE' : 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            if (response.status === 401) {
                // User is not logged in, redirect to login page
                if (confirm('Bạn cần đăng nhập để thêm sản phẩm vào danh sách yêu thích. Bạn có muốn đăng nhập ngay bây giờ?')) {
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
        
        if (data.success) {
            // Cập nhật trạng thái nút
            updateWishlistButton(!isInWishlist);

            // Cập nhật số lượng trong wishlist ở header
            
        if (data.wishlistCount !== undefined) {
            const wishlistBadges = document.querySelectorAll('a[href="/wishlist"] .badge');
            wishlistBadges.forEach(badge => {
                badge.textContent = data.wishlistCount;
            });
        }

            toastr.success(isInWishlist ? 'Sản phẩm đã được xóa khỏi danh sách yêu thích' : 'Sản phẩm đã được thêm vào danh sách yêu thích');
        } else {
            toastr.error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (error === 'Unauthorized') {
            return;
        }
        toastr.error('Có lỗi xảy ra khi thêm vào danh sách yêu thích. Vui lòng thử lại sau.');
    });
    
}

function removeFromWishlist(productId) {
    if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi danh sách yêu thích?')) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        fetch(`/wishlist/remove/${productId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cập nhật số lượng trong wishlist ở header
                const wishlistBadges = document.querySelectorAll('a[href="/wishlist"] .badge');
                wishlistBadges.forEach(badge => {
                    badge.textContent = data.wishlistCount;
                });
                
                // Xóa sản phẩm khỏi giao diện
                const productCard = document.querySelector(`.product-card[data-product-id="${productId}"]`);
                if (productCard) {
                    const parentCol = productCard.closest('.col-md-3');
                    if (parentCol) {
                        parentCol.remove();
                    }
                }
                
                // Kiểm tra nếu không còn sản phẩm nào trong wishlist
                const remainingProducts = document.querySelectorAll('.product-card');
                if (remainingProducts.length === 0) {
                    location.reload(); // Tải lại trang để hiển thị thông báo danh sách trống
                }
            } else {
                toastr.error(data.message || 'Có lỗi xảy ra khi xóa khỏi danh sách yêu thích');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Có lỗi xảy ra khi xóa khỏi danh sách yêu thích');
        });
    }
} 