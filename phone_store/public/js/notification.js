
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
