@extends('layouts.app')

@section('title', $product->name . ' - KAIRA')

@push('styles')
<style>
.color-btn.active {
    background-color: #000 !important;
    color: #fff !important;
    border-color: #000 !important;
}

.color-btn:not(.active) {
    background-color: transparent !important;
    color: #000 !important;
    border-color: #000 !important;
}

.color-btn:hover {
    background-color: #000 !important;
    color: #fff !important;
}

.product-carousel {
    position: relative;
    margin-bottom: 1rem;
}

.product-carousel .carousel-item img {
    width: 100%;
    height: 500px;
    object-fit: cover;
}

.product-thumbnails {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}

.product-thumbnail {
    width: 80px;
    height: 80px;
    cursor: pointer;
    opacity: 0.6;
    transition: opacity 0.3s ease;
    border: 2px solid transparent;
}

.product-thumbnail:hover,
.product-thumbnail.active {
    opacity: 1;
    border-color: #000;
}

.product-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>
@endpush

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none text-muted">Sản phẩm</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Product Images -->
        <div class="col-lg-6">
            <div class="product-images">
                <div id="productCarousel" class="carousel slide product-carousel" data-bs-ride="false">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ $product->image ? asset('images/products/' . $product->image) : asset('images/placeholder.jpg') }}"
                                 class="d-block" alt="{{ $product->name }}" id="productImage">
                        </div>
                        @if($variants->isNotEmpty())
                            @foreach($variants as $variant)
                                <div class="carousel-item">
                                    <img src="{{ $variant->image ? asset('images/products/' . $variant->image) : asset('images/placeholder.jpg') }}"
                                         class="d-block" alt="{{ $variant->name }}">
                                </div>
                            @endforeach
                        @endif
                    </div>
                    @if($variants->isNotEmpty())
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>
                
                @if($variants->isNotEmpty())
                    <div class="product-thumbnails">
                        <div class="product-thumbnail active" data-bs-target="#productCarousel" data-bs-slide-to="0">
                            <img src="{{ $product->image ? asset('images/products/' . $product->image) : asset('images/placeholder.jpg') }}"
                                 alt="{{ $product->name }}">
                        </div>
                        @foreach($variants as $key => $variant)
                            <div class="product-thumbnail" data-bs-target="#productCarousel" data-bs-slide-to="{{ $key + 1 }}">
                                <img src="{{ $variant->image ? asset('images/products/' . $variant->image) : asset('images/placeholder.jpg') }}"
                                     alt="{{ $variant->name }}">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-6">
            <div class="product-info">
                <h1 class="h2 mb-2" style="font-family: 'Marcellus', serif;" id="productName">{{ $product->name }}</h1>
                <p class="text-muted mb-4">{{ $product->category->name }}</p>

                <!-- Price -->
                <div class="mb-4">
                    @if($product->discount_price)
                        <h2 class="text-danger mb-0 d-inline" id="productOriginalPrice">
                            <del>{{ $product->formatted_price }}</del>
                        </h2>
                        <h2 class="text-danger mb-0 d-inline" id="productDiscountPrice">{{ $product->formatted_discount_price }}</h2>
                        <div class="mt-2">
                            <span class="badge bg-danger">Giảm giá</span>
                        </div>
                    @else
                        <h2 class="mb-0" id="productPrice">{{ $product->formatted_price }}</h2>
                    @endif
                </div>

                <!-- Color Variants -->
                @if($variants->isNotEmpty())
                <div class="mb-4">
                    <h3 class="h5 mb-3">Màu sắc:</h3>
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-outline-dark color-btn active" 
                                data-product-id="{{ $product->id }}"
                                data-color="{{ $product->color }}">
                            {{ $product->color }}
                        </button>
                        @foreach($variants as $variant)
                            <button class="btn btn-outline-dark color-btn"
                                    data-product-id="{{ $variant->id }}"
                                    data-color="{{ $variant->color }}">
                                {{ $variant->color }}
                            </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Specifications -->
                <div class="specs mb-4">
                    <h3 class="h5 mb-3">Thông số kỹ thuật:</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="bg-light" style="width: 30%">RAM</th>
                                    <td id="productSpecs">{{ $product->ram }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Bộ nhớ trong</th>
                                    <td id="productStorage">{{ $product->storage }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Màu sắc</th>
                                    <td id="productColor">{{ $product->color }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <h3 class="h5 mb-3">Mô tả sản phẩm:</h3>
                    <div class="text-muted" id="productDescription">{{ $product->description }}</div>
                </div>

                <!-- Actions -->
                <div class="d-grid gap-3">
                    @auth
                        <button class="btn btn-dark btn-lg rounded-0 add-to-cart" data-product-id="{{ $product->id }}">
                            <i class="bi bi-cart-plus me-2"></i>Thêm vào giỏ hàng
                        </button>
                        <button class="btn btn-outline-dark btn-lg rounded-0 add-to-wishlist" data-product-id="{{ $product->id }}">
                            <i class="bi bi-heart me-2"></i>Thêm vào yêu thích
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-dark btn-lg rounded-0">
                            <i class="bi bi-cart-plus me-2"></i>Đăng nhập để mua hàng
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-dark btn-lg rounded-0">
                            <i class="bi bi-heart me-2"></i>Đăng nhập để thêm vào yêu thích
                        </a>
                    @endauth
                </div>

                <!-- Additional Info -->
                <div class="mt-4">
                    <div class="row g-4">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-truck fs-4 me-2"></i>
                                <div>
                                    <h4 class="h6 mb-1">Miễn phí vận chuyển</h4>
                                    <p class="small text-muted mb-0">Cho đơn hàng trên 2 triệu</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-shield-check fs-4 me-2"></i>
                                <div>
                                    <h4 class="h6 mb-1">Bảo hành chính hãng</h4>
                                    <p class="small text-muted mb-0">12 tháng bảo hành</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
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
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi tải thông tin sản phẩm');
            });
    }

    // Xử lý nút thêm vào giỏ hàng
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            addToCart(productId);
        });
    });

    // Xử lý nút thêm vào danh sách yêu thích
    document.querySelectorAll('.add-to-wishlist').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            addToWishlist(productId);
        });
    });
});

function addToCart(productId) {
    fetch(`/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Sản phẩm đã được thêm vào giỏ hàng');
            if (data.cartCount !== undefined) {
                document.querySelector('.cart-count').textContent = data.cartCount;
            }
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi thêm vào giỏ hàng');
    });
}

function addToWishlist(productId) {
    fetch(`/wishlist/add/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Sản phẩm đã được thêm vào danh sách yêu thích');
            if (data.wishlistCount !== undefined) {
                document.querySelector('.wishlist-count').textContent = data.wishlistCount;
            }
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi thêm vào danh sách yêu thích');
    });
}
</script>
@endpush