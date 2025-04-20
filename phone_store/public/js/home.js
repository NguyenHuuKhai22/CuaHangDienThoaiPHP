/**
 * Xử lý hiệu ứng xuất hiện cho các phần tử khi lướt trang
 * Sử dụng Intersection Observer API để theo dõi các phần tử
 */
document.addEventListener('DOMContentLoaded', function() {
    // Cấu hình cho Intersection Observer
    const observerOptions = {
        root: null,        // null = viewport
        rootMargin: '0px', // không có margin
        threshold: 0.2     // kích hoạt khi phần tử hiển thị 20%
    };

    /**
     * Callback function được gọi khi phần tử xuất hiện trong viewport
     * @param {IntersectionObserverEntry[]} entries - Mảng các phần tử được theo dõi
     * @param {IntersectionObserver} observer - Instance của observer
     */
    const revealCallback = (entries, observer) => {
        entries.forEach(entry => {
            // Kiểm tra nếu phần tử đã xuất hiện trong viewport
            if (entry.isIntersecting) {
                // Thêm class active để kích hoạt animation
                entry.target.classList.add('active');
                
                // Nếu là section, xử lý animation cho các phần tử con
                if (entry.target.classList.contains('reveal-section')) {
                    const items = entry.target.querySelectorAll('.reveal-item');
                    // Animation tuần tự cho từng phần tử con
                    items.forEach((item, index) => {
                        setTimeout(() => {
                            item.classList.add('active');
                        }, index * 100); // Delay 100ms cho mỗi phần tử
                    });
                }
                
                // Ngừng theo dõi phần tử sau khi đã xử lý
                observer.unobserve(entry.target);
            }
        });
    };

    // Khởi tạo Intersection Observer
    const observer = new IntersectionObserver(revealCallback, observerOptions);

    // Bắt đầu theo dõi tất cả các phần tử có class reveal-section và reveal-item
    document.querySelectorAll('.reveal-section, .reveal-item').forEach(element => {
        observer.observe(element);
    });
});