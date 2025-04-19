// xử lý cập nhật thông tin sản phẩm
function updateProductInfo(productId) {
    fetch(`/products/${productId}/details`)
        .then(response => response.json())
        .then(data => {
            // Cập nhật hình ảnh và tên sản phẩm
            const productImage = document.getElementById('productImage');
            const productName = document.getElementById('productName');
            if (productImage) productImage.src = data.image;
            if (productName) productName.textContent = data.name;

            // Cập nhật giá
            const priceContainer = document.querySelector('.product-info');
            if (priceContainer) {
                // Xóa tất cả các phần tử giá cũ
                const oldPrices = priceContainer.querySelectorAll('h2');
                oldPrices.forEach(price => price.remove());
                
                // Xóa badge giảm giá cũ nếu có
                const existingBadge = priceContainer.querySelector('.badge.bg-danger');
                if (existingBadge) {
                    existingBadge.remove();
                }
                
                // Tạo phần tử div.mb-4 mới cho giá
                const priceDiv = document.createElement('div');
                priceDiv.className = 'mb-4';

                // Thêm nội dung giá dựa trên trạng thái giảm giá
                if (data.discount_price && data.discount_price < data.price) {
                    priceDiv.innerHTML = `
                        <h2 class="text-danger mb-0 d-inline" id="productDiscountPrice">
                            ${data.formatted_discount_price}
                        </h2>
                        <h2 class="text-muted mb-0 d-inline ms-2" id="productOriginalPrice">
                            <del>${data.formatted_price}</del>
                        </h2>
                        <div class="mt-2">
                            <span class="badge bg-danger">Giảm giá</span>
                        </div>
                    `;
                } else {
                    priceDiv.innerHTML = `
                        <h2 class="mb-0" id="productPrice">
                            ${data.formatted_price}
                        </h2>
                    `;
                }

                // Chèn div giá vào sau phần tên sản phẩm
                const categoryText = priceContainer.querySelector('.text-muted.mb-4');
                if (categoryText) {
                    categoryText.after(priceDiv);
                } else {
                    // Nếu không tìm thấy phần category, chèn vào đầu container
                    priceContainer.insertBefore(priceDiv, priceContainer.firstChild.nextSibling);
                }
            }
            
            // Cập nhật thông số kỹ thuật
            const productSpecs = document.getElementById('productSpecs');
            const productStorage = document.getElementById('productStorage');
            const productColor = document.getElementById('productColor');
            if (productSpecs) productSpecs.textContent = data.ram;
            if (productStorage) productStorage.textContent = data.storage;
            if (productColor) productColor.textContent = data.color;
            
            // Cập nhật mô tả sản phẩm
            const productDescription = document.getElementById('productDescription');
            if (productDescription) {
                productDescription.textContent = `iPhone 16 Pro Max ${data.storage}GB - ${data.color}`;
            }
            
            // Cập nhật nút màu sắc
            const colorButtons = document.querySelectorAll('.color-btn');
            colorButtons.forEach(btn => {
                if (btn.dataset.color === data.color) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
            
            // Cập nhật ID sản phẩm cho các nút thêm vào giỏ hàng và yêu thích
            document.querySelectorAll('.add-to-cart, .add-to-wishlist')
                .forEach(btn => {
                    btn.dataset.productId = data.id;
                });

            // Kiểm tra trạng thái wishlist
            checkWishlistStatus(data.id);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải thông tin sản phẩm');
        });
} 