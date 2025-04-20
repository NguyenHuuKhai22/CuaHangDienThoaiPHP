<header>
    <nav class="navbar navbar-expand-lg bg-white">
        <div class="container py-3">
            <a class="navbar-brand" href="/">HK STORE</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">TRANG CHỦ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('shop*') ? 'active' : '' }}" href="/shop">CỬA HÀNG</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('blog*') ? 'active' : '' }}" href="/blog">TIN TỨC</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('about*') ? 'active' : '' }}" href="/about">GIỚI THIỆU</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('contact*') ? 'active' : '' }}" href="/contact">LIÊN HỆ</a>
                    </li>
                </ul>
            </div>
            
            <div class="d-flex align-items-center gap-4">
                @guest
                    <a href="{{ route('login') }}" class="text-dark text-decoration-none">
                        <i class="bi bi-person"></i>
                        <span class="ms-2 d-none d-lg-inline">Đăng nhập</span>
                    </a>
                @else
                    <!-- Notification Dropdown -->
                    <div class="dropdown notification-dropdown">
                        <a href="#" class="text-dark text-decoration-none dropdown-toggle position-relative" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge" style="display: none;">
                                0
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end notification-menu" style="width: 300px; max-height: 400px; overflow-y: auto;">
                            <h6 class="dropdown-header">Thông báo</h6>
                            <div class="notifications-container">
                                <!-- Notifications will be inserted here -->
                            </div>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-center" href="#">Xem tất cả</a>
                        </div>
                    </div>

                    <div class="dropdown">
                        <a href="#" class="text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                            <span class="ms-2 d-none d-lg-inline">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/profile">Hồ sơ của tôi</a></li>
                            <li><a class="dropdown-item" href="/orders">Đơn hàng của tôi</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Đăng xuất</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest

                <a href="{{ route('wishlist.index') }}" class="text-dark text-decoration-none position-relative">
                    <i class="bi bi-heart"></i>
                    <span class="ms-2 d-none d-lg-inline">Yêu thích</span>
                    @auth
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ Auth::user()->wishlist?->items_count ?? 0 }}
                        </span>
                    @endauth
                </a>

                <a href="{{ route('cart.index') }}" class="text-dark text-decoration-none position-relative">
                    <i class="bi bi-cart3"></i>
                    <span class="ms-2 d-none d-lg-inline">Giỏ hàng</span>
                    @auth
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ Auth::user()->cart?->cartItems->sum('quantity') ?? 0 }}
                        </span>
                    @endauth
                </a>

                <button class="btn btn-link text-dark p-0 d-none d-lg-block" type="button" data-bs-toggle="modal" data-bs-target="#searchModal">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
    </nav>
</header>

<!-- Search Modal -->
<div class="modal search-modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="searchModalLabel">Tìm kiếm sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('search') }}" method="GET" class="search-form">
                    <div class="input-group">
                        <input type="text" 
                               class="form-control" 
                               name="q" 
                               placeholder="Nhập tên sản phẩm cần tìm..."
                               required
                               minlength="2">
                        <button class="btn btn-dark" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- <link rel="stylesheet" href="{{ assert('css/styleHeart.css')}}"> -->

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationDropdown = document.querySelector('.notification-dropdown');
    const notificationBadge = document.querySelector('.notification-badge');
    const notificationContainer = document.querySelector('.notifications-container');

    // Khởi tạo dropdown của Bootstrap
    new bootstrap.Dropdown(notificationDropdown.querySelector('[data-bs-toggle="dropdown"]'));

    function checkNotifications() {
        fetch('/check-promotions')
            .then(response => response.json())
            .then(data => {
                console.log('Notification data:', data); // Debug log
                
                notificationContainer.innerHTML = '';
                
                // Add upcoming promotions
                if (data.upcoming && data.upcoming.length > 0) {
                    data.upcoming.forEach(promotion => {
                        const notificationData = JSON.parse(promotion.data);
                        const item = document.createElement('a');
                        item.href = `/shop?promotion_id=${notificationData.promotion_id}`;
                        item.className = 'dropdown-item notification-item';
                        item.dataset.promotionId = notificationData.promotion_id;
                        
                        // Kiểm tra nếu thông báo đã đọc
                        if (promotion.read_at) {
                            item.classList.add('read');
                        }
                        
                        item.innerHTML = `
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="mb-1">${notificationData.content}</p>
                                    ${promotion.minutes_until_start ? 
                                        `<small class="text-muted">Còn ${promotion.minutes_until_start} phút</small>` : 
                                        ''}
                                </div>
                            </div>
                        `;
                        notificationContainer.appendChild(item);
                    });
                }
                
                // Add started promotions
                if (data.started && data.started.length > 0) {
                    data.started.forEach(promotion => {
                        const notificationData = JSON.parse(promotion.data);
                        const item = document.createElement('a');
                        item.href = `/shop?promotion_id=${notificationData.promotion_id}`;
                        item.className = 'dropdown-item notification-item';
                        item.dataset.promotionId = notificationData.promotion_id;
                        
                        // Kiểm tra nếu thông báo đã đọc
                        if (promotion.read_at) {
                            item.classList.add('read');
                        }
                        
                        item.innerHTML = `
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="mb-1">${notificationData.content}</p>
                                    <small class="text-muted">Vừa bắt đầu</small>
                                </div>
                            </div>
                        `;
                        notificationContainer.appendChild(item);
                    });
                }
                
                // Update badge - hiển thị tổng số thông báo chưa đọc
                if (data.total > 0) {
                    notificationBadge.style.display = 'block';
                    notificationBadge.textContent = data.total;
                } else {
                    notificationBadge.style.display = 'none';
                }
                
                // If no notifications at all
                if (data.upcoming.length === 0 && data.started.length === 0) {
                    const emptyMessage = document.createElement('div');
                    emptyMessage.className = 'dropdown-item text-center text-muted';
                    emptyMessage.textContent = 'Không có thông báo nào';
                    notificationContainer.appendChild(emptyMessage);
                }
            })
            .catch(error => {
                console.error('Error checking notifications:', error);
                notificationContainer.innerHTML = `
                    <div class="dropdown-item text-center text-danger">
                        Có lỗi xảy ra khi tải thông báo
                    </div>
                `;
            });
    }

    // Check notifications immediately and every minute
    checkNotifications();
    setInterval(checkNotifications, 60000);

    // Mark notification as read when clicked
    notificationContainer.addEventListener('click', function(e) {
        const item = e.target.closest('.notification-item');
        if (item) {
            const promotionId = item.dataset.promotionId;
            if (promotionId) {
                e.preventDefault(); // Chặn hành vi chuyển trang mặc định
                
                // Đánh dấu visual là đã đọc ngay lập tức
                item.classList.add('read');
                
                // Cập nhật số lượng thông báo (giảm đi 1)
                const currentCount = parseInt(notificationBadge.textContent);
                if (currentCount > 1) {
                    notificationBadge.textContent = currentCount - 1;
                } else {
                    notificationBadge.style.display = 'none';
                }
                
                // Lưu URL đích để chuyển hướng sau
                const targetUrl = item.getAttribute('href');
                
                // Lấy CSRF token từ meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                
                // Gửi request và đợi hoàn thành trước khi chuyển trang
                fetch('/notifications/mark-as-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        promotion_id: promotionId
                    }),
                    credentials: 'same-origin' // Đảm bảo gửi cookies, bao gồm session
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Mark as read success:', data);
                    // Chuyển hướng đến trang đích sau khi request hoàn thành
                    window.location.href = targetUrl;
                })
                .catch(error => {
                    console.error('Error marking notification as read:', error);
                    // Vẫn chuyển hướng nếu có lỗi
                    window.location.href = targetUrl;
                });
            }
        }
    });
});
</script>

<style>
/* Notification Dropdown Styles */
.notification-dropdown .dropdown-menu {
    padding: 0;
    margin-top: 0.75rem;
    box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.15);
    min-width: 400px;
    border: none;
    border-radius: 8px;
}

.notification-dropdown .dropdown-header {
    background-color: #f8f9fa;
    font-weight: 600;
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
    color: #1a1a1a;
    font-size: 0.95rem;
    border-radius: 8px 8px 0 0;
}

.notifications-container {
    max-height: 360px;
    overflow-y: auto;
    padding: 0.5rem 0;
}

.notifications-container::-webkit-scrollbar {
    width: 6px;
}

.notifications-container::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.notifications-container::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.notification-item {
    padding: 1rem;
    border-bottom: 1px solid #eee;
    cursor: pointer;
    transition: all 0.2s ease;
    display: block;
    text-decoration: none;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
}

.notification-item.read {
    opacity: 0.7;
    background-color: #f8f9fa;
}

.notification-item p {
    margin: 0;
    font-size: 0.9rem;
    color: #333;
    line-height: 1.4;
}

.notification-item small {
    font-size: 0.75rem;
    color: #6c757d;
    display: block;
    margin-top: 0.25rem;
}

.notification-badge {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
    background-color: #dc3545;
    border: 2px solid #fff;
    box-shadow: 0 0 0 1px rgba(0,0,0,0.1);
}

.dropdown-divider {
    margin: 0;
    border-top: 1px solid #eee;
}

.dropdown-item.text-center {
    padding: 0.75rem;
    color: #6c757d;
    font-size: 0.9rem;
    font-weight: 500;
    transition: color 0.2s ease;
}

.dropdown-item.text-center:hover {
    color: #0d6efd;
}

.text-danger {
    padding: 1rem;
    text-align: center;
    font-size: 0.9rem;
}

/* Animation for new notifications */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.notification-item {
    animation: fadeIn 0.3s ease forwards;
}

/* Hover effect for notification icon */
.notification-dropdown > a {
    transition: transform 0.2s ease;
}

.notification-dropdown > a:hover {
    transform: scale(1.1);
}

.notification-dropdown > a:active {
    transform: scale(0.95);
}

/* Enhanced Navigation link effects */
.navbar-nav .nav-link {
    position: relative;
    color: #333;
    padding: 0.5rem 1rem;
    border-radius: 100px;
    font-weight: 500;
    letter-spacing: 0.5px;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    overflow: hidden;
}

.navbar-nav .nav-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, #333, #222);
    z-index: -1;
    transform: scale(0);
    transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.navbar-nav .nav-link::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: -2px;
    left: 50%;
    background-color: #fff;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    transform: translateX(-50%);
}

.navbar-nav .nav-link:hover {
    color: #fff;
    transform: translateY(-2px);
}

.navbar-nav .nav-link:hover::before {
    transform: scale(1);
}

.navbar-nav .nav-link:hover::after {
    width: 80%;
}

/* Active link style */
.navbar-nav .nav-link.active {
    color: #fff;
    transform: translateY(-2px);
}

.navbar-nav .nav-link.active::before {
    transform: scale(1);
}

.navbar-nav .nav-link.active::after {
    width: 80%;
}

/* Add subtle shadow on hover and active */
.navbar-nav .nav-link:hover,
.navbar-nav .nav-link.active {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>
@endpush