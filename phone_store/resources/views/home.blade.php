@extends('layouts.app')

@section('title', 'HK - Phone Store')

@section('content')
<!-- Hero Section -->
<section class="hero-section position-relative vh-100">
    <div class="hero-background position-absolute w-100 h-100">
        <img src="{{ asset('images/hero-phone.png') }}" alt="Store Background" class="w-100 h-100 object-fit-cover">
        <div class="overlay position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to right, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 100%);"></div>
    </div>
    
    <div class="container position-relative h-100">
        <div class="row h-100 align-items-center">
            <div class="col-lg-7">
                <div class="hero-content text-white" style="margin-top: -50px;">
                    <h1 class="display-3 fw-bold mb-3" style="font-family: 'Marcellus', serif;">
                        Điện Thoại Cao Cấp<br>
                        <span class="text-warning">Chính Hãng</span>
                    </h1>
                    <p class="lead mb-5" style="font-size: 1.25rem;">Khám phá bộ sưu tập điện thoại thông minh mới nhất với công nghệ tiên tiến và thiết kế đẳng cấp.</p>
                    <div class="hero-buttons">
                        <a href="#featured-products" class="btn btn-warning btn-lg px-5 py-3">
                            Mua Ngay
                            <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .hero-section {
        overflow: hidden;
    }
    
    .hero-background {
        z-index: 1;
    }
    
    .hero-background img {
        object-position: center;
    }
    
    .container {
        z-index: 2;
    }
    
    .hero-content {
        animation: fadeInUp 1s ease;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #000;
        transition: all 0.3s ease;
    }
    
    .btn-warning:hover {
        background-color: #ffca2c;
        border-color: #ffca2c;
        transform: translateY(-2px);
    }
</style>
@endpush

<!-- Categories Section -->
<section class="categories-section py-5">
    <div class="container position-relative">
        <h2 class="display-6 text-center mb-5" style="font-family: 'Marcellus', serif;">Danh Mục Sản Phẩm</h2>

        <div class="swiper categories-slider">
            <div class="swiper-wrapper">
                @foreach($categories as $category)
                <div class="swiper-slide">
                    <a href="{{ route('categories.show', $category) }}" class="text-decoration-none">
                        <div class="category-card">
                            <div class="category-image">
                                <img src="{{ $category->image ? asset('images/categories/' . $category->image) : asset('images/placeholder.jpg') }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-100 h-100 object-fit-cover">
                                <div class="category-overlay">
                                    <h3 class="h4 mb-3 text-white">{{ $category->name }}</h3>
                                    <span class="view-more">
                                        Xem ngay
                                        <i class="bi bi-arrow-right ms-2"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Navigation Arrows -->
        <button class="btn btn-light category-prev position-absolute top-50 start-0 translate-middle-y" 
                style="width: 50px; height: 50px; margin-left: -25px; border-radius: 50%; box-shadow: 0 2px 5px rgba(0,0,0,0.1); z-index: 10;">
            <span style="font-size: 1.5rem; line-height: 1;">&lt;</span>
        </button>
        <button class="btn btn-light category-next position-absolute top-50 end-0 translate-middle-y" 
                style="width: 50px; height: 50px; margin-right: -25px; border-radius: 50%; box-shadow: 0 2px 5px rgba(0,0,0,0.1); z-index: 10;">
            <span style="font-size: 1.5rem; line-height: 1;">&gt;</span>
        </button>
    </div>
</section>

@push('styles')
<style>
    .categories-section {
        background-color: #f8f9fa;
    }

    .categories-slider {
        padding: 1rem 0;
        margin: 0 25px;
    }

    .categories-slider .swiper-slide {
        width: 340px;
        margin-right: 25px;
    }

    .category-card {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .category-image {
        position: relative;
        height: 290px;
        overflow: hidden;
    }

    .category-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 2rem;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        transform: translateY(0);
        transition: all 0.3s ease;
    }

    .category-card:hover .category-overlay {
        transform: translateY(-10px);
    }

    .view-more {
        color: #ffc107;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
    }

    .category-card:hover .view-more {
        color: #fff;
    }

    .category-prev,
    .category-next {
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.8;
    }

    .category-prev:hover,
    .category-next:hover {
        background-color: #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        transform: translateY(-50%) scale(1.1);
        opacity: 1;
    }
</style>
@endpush

<!-- Featured Products -->
<section class="py-5 bg-light" id="featured-products">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
            <h2 class="mb-4" style="font-family: 'Marcellus', serif;">Sản Phẩm Nổi Bật</h2>
            <p class="text-muted">Khám phá những mẫu điện thoại thông minh cao cấp của chúng tôi</p>
            </div>
        </div>
        <div class="row g-4">
            @foreach($latestProducts as $product)
            <div class="col-md-3">
                <div class="card h-100 border-0 product-card">
                    <div class="position-relative">
                        <img src="{{ $product->image ? asset('images/products/' . $product->image) : asset('images/placeholder.jpg') }}" 
                             class="card-img-top" alt="{{ $product->name }}"
                             style="height: 300px; object-fit: cover;">
                        <div class="product-overlay position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center gap-2 opacity-0">
                            @auth
                                <button class="btn btn-sm btn-dark rounded-circle p-2 add-to-cart" 
                                        data-product-id="{{ $product->id }}"
                                        title="Thêm vào giỏ hàng">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-dark rounded-circle p-2 add-to-wishlist"
                                        data-product-id="{{ $product->id }}"
                                        title="Thêm vào yêu thích">
                                    <i class="bi bi-heart"></i>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-sm btn-dark rounded-circle p-2" 
                                   title="Đăng nhập để mua hàng">
                                    <i class="bi bi-cart-plus"></i>
                                </a>
                                <a href="{{ route('login') }}" class="btn btn-sm btn-dark rounded-circle p-2" 
                                   title="Đăng nhập để thêm vào yêu thích">
                                    <i class="bi bi-heart"></i>
                                </a>
                            @endauth
                            <a href="{{ route('products.show', $product) }}" 
                               class="btn btn-sm btn-dark rounded-circle p-2" 
                               title="Xem chi tiết">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-0 text-center">
                        <p class="text-muted small mb-1">{{ $product->category->name }}</p>
                        <h5 class="card-title" style="font-family: 'Marcellus', serif;">{{ $product->name }}</h5>
                        <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                            @if($product->discount_price)
                                <p class="card-text text-danger mb-0">{{ $product->formatted_discount_price }}</p>
                                <p class="card-text text-muted mb-0"><del>{{ $product->formatted_price }}</del></p>
                            @else
                                <p class="card-text mb-0">{{ $product->formatted_price }}</p>
                            @endif
                        </div>
                        <div class="specs text-muted small">
                            <span class="me-2">{{ $product->ram }}</span>|
                            <span class="mx-2">{{ $product->storage }}</span>|
                            <span class="ms-2">{{ $product->color }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Collection Banner -->
<!-- <section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="position-relative overflow-hidden" style="height: 500px;">
                    <img src="{{ asset('images/collection-banner.jpg') }}" 
                         alt="Collection" 
                         class="w-100 h-100 object-fit-cover">
                </div>
            </div>
            <div class="col-md-5 offset-md-1">
                <h2 class="mb-4" style="font-family: 'Marcellus', serif;">Latest Smartphones</h2>
                <p class="mb-4 text-muted">Experience the future of mobile technology with our premium smartphone collection. Featuring cutting-edge innovations, stunning cameras, and powerful performance.</p>
                <a href="#" class="btn btn-dark rounded-0 px-5 py-3">EXPLORE NOW</a>
            </div>
        </div>
    </div>
</section> -->

<!-- New Arrivals -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="mb-4" style="font-family: 'Marcellus', serif;">Hàng Mới Về</h2>
                <p class="text-muted">Khám phá những mẫu điện thoại mới nhất của chúng tôi</p>
            </div>
        </div>
        <div class="row g-4">
            @foreach($latestProducts->take(3) as $product)
            <div class="col-md-4">
                <div class="card h-100 border-0">
                    <div class="position-relative" style="height: 340px;">
                        <img src="{{ $product->image ? asset('images/products/' . $product->image) : asset('images/placeholder.jpg') }}" 
                             class="w-100 h-100 object-fit-cover" 
                             alt="{{ $product->name }}">
                    </div>
                    <div class="card-body px-0">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <p class="text-muted mb-0">{{ $product->category->name }}</p>
                            <p class="text-muted mb-0">{{ $product->created_at->format('F d, Y') }}</p>
                        </div>
                        <h5 class="card-title" style="font-family: 'Marcellus', serif;">{{ $product->name }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($product->description, 100) }}</p>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-outline-dark rounded-0">Xem Chi Tiết</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/styleHeart.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/cart.js') }}"></script>
<script src="{{ asset('js/wishlisthome.js') }}"></script>
@endpush
@endsection 