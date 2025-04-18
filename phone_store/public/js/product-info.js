// xử lý cập nhật thông tin sản phẩm
function updateProductInfo(productId) {
    fetch(`/products/${productId}/details`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('productImage').src = data.image;
            document.getElementById('productName').textContent = data.name;
            
            if (data.discount_price) {
                document.getElementById('productOriginalPrice').innerHTML = 
                    `<del>${data.formatted_price}</del>`;
                document.getElementById('productDiscountPrice').textContent = 
                    data.formatted_discount_price;
            } else {
                document.getElementById('productPrice').textContent = 
                    data.formatted_price;
            }
            
            document.getElementById('productSpecs').textContent = data.ram;
            document.getElementById('productStorage').textContent = data.storage;
            document.getElementById('productColor').textContent = data.color;
            document.getElementById('productDescription').textContent = data.description;
            
            document.querySelectorAll('.add-to-cart, .add-to-wishlist')
                .forEach(btn => {
                    btn.dataset.productId = data.id;
                });

            // Kiểm tra trạng thái wishlist cho sản phẩm mới
            checkWishlistStatus(data.id);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải thông tin sản phẩm');
        });
} 