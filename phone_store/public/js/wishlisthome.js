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
    // Kiểm tra trạng thái wishlist cho tất cả sản phẩm
    document.querySelectorAll('.add-to-wishlist').forEach(button => {
        const productId = button.dataset.productId;
        checkWishlistStatus(productId, button);

        // Thêm event listener cho nút wishlist
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const isInWishlist = this.dataset.inWishlist === 'true';
            toggleWishlist(productId, this);
        });
    });

    new Swiper('.categories-slider', {
        slidesPerView: 'auto',
        spaceBetween: 20,
        navigation: {
            nextEl: '.category-next',
            prevEl: '.category-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            1024: {
                slidesPerView: 4,
            },
        }
    });
});

function checkWishlistStatus(productId, button) {
    fetch(`/wishlist/check/${productId}`)
        .then(response => response.json())
        .then(data => {
            updateWishlistButton(data.isInWishlist, button);
        })
        .catch(error => {
            console.error('Error checking wishlist status:', error);
        });
}

function updateWishlistButton(isInWishlist, button) {
    if (!button) return;

    button.dataset.inWishlist = isInWishlist;
    const icon = button.querySelector('i');

    if (isInWishlist) {
        button.classList.remove('btn-dark');
        button.classList.add('active');
        icon.classList.remove('bi-heart');
        icon.classList.add('bi-heart-fill');
        button.title = 'Xóa khỏi yêu thích';
    } else {
        button.classList.remove('active');
        button.classList.add('btn-dark');
        icon.classList.remove('bi-heart-fill');
        icon.classList.add('bi-heart');
        button.title = 'Thêm vào yêu thích';
    }
}

function updateWishlistCount(count) {
    // Cập nhật số lượng trong header
    const wishlistBadges = document.querySelectorAll('a[href="/wishlist"] .badge');
    wishlistBadges.forEach(badge => {
        badge.textContent = count;
    });

    // Nếu không tìm thấy badge bằng cách trên, thử tìm bằng cách khác
    if (wishlistBadges.length === 0) {
        const allBadges = document.querySelectorAll('.badge');
        allBadges.forEach(badge => {
            const parentLink = badge.closest('a[href*="wishlist"]');
            if (parentLink) {
                badge.textContent = count;
            }
        });
    }
}

function toggleWishlist(productId, button) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
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
        if (!data) return;
        
        if (data.success) {
            // Cập nhật trạng thái nút
            updateWishlistButton(!isInWishlist, button);

            // Cập nhật số lượng trong header
            if (data.wishlistCount !== undefined) {
                updateWishlistCount(data.wishlistCount);
            }

            // Thông báo thành công
            const message = isInWishlist ? 'Đã xóa khỏi danh sách yêu thích' : 'Đã thêm vào danh sách yêu thích';
            toastr.success(message);
        } else {
            toastr.error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (error === 'Unauthorized') return;
        toastr.error('Có lỗi xảy ra khi thao tác với danh sách yêu thích. Vui lòng thử lại sau.');
    });
}