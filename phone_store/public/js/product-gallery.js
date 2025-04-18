//carousel và color buttons
document.addEventListener('DOMContentLoaded', function() {
    const thumbnails = document.querySelectorAll('.product-thumbnail');
    const colorButtons = document.querySelectorAll('.color-btn');
    const carousel = document.getElementById('productCarousel');
    
    // Xử lý khi click vào thumbnail
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            const slideIndex = parseInt(this.getAttribute('data-bs-slide-to'));
            
            // Cập nhật active state cho thumbnails
            thumbnails.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            // Cập nhật active state cho nút màu
            colorButtons.forEach((btn, index) => {
                btn.classList.toggle('active', index === slideIndex);
                if (index === slideIndex) {
                    updateProductInfo(btn.dataset.productId);
                }
            });
        });
    });

    // Xử lý khi carousel chuyển slide
    if (carousel) {
        carousel.addEventListener('slid.bs.carousel', function(event) {
            const activeIndex = event.to;

            // Cập nhật active state cho thumbnails
            thumbnails.forEach(t => t.classList.remove('active'));
            const activeThumb = document.querySelector(`.product-thumbnail[data-bs-slide-to="${activeIndex}"]`);
            if (activeThumb) {
                activeThumb.classList.add('active');
            }

            // Cập nhật active state cho nút màu
            colorButtons.forEach((btn, index) => {
                btn.classList.toggle('active', index === activeIndex);
                if (index === activeIndex) {
                    updateProductInfo(btn.dataset.productId);
                }
            });
        });
    }

    // Xử lý khi click vào nút màu
    colorButtons.forEach((button, index) => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            
            // Cập nhật active state cho nút màu
            colorButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Chuyển carousel đến slide tương ứng
            const carouselInstance = new bootstrap.Carousel(carousel);
            carouselInstance.to(index);

            // Cập nhật active state cho thumbnails
            thumbnails.forEach(t => t.classList.remove('active'));
            const activeThumb = document.querySelector(`.product-thumbnail[data-bs-slide-to="${index}"]`);
            if (activeThumb) {
                activeThumb.classList.add('active');
            }

            // Cập nhật thông tin sản phẩm
            updateProductInfo(productId);
        });
    });
}); 