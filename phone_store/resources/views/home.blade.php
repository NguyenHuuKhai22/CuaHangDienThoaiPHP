@extends('layouts.app')

@section('title', 'HK - Phone Store')

@section('content')
<!-- Hero Section -->
<section class="hero-section position-relative vh-100">
    <div class="hero-background position-absolute w-100 h-100">
        <img src="{{ asset('images/hero-phone.png') }}" alt="Store Background" class="w-100 h-100 object-fit-cover" loading="eager">
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



<!-- Categories Section -->
<section class="categories-section py-5 reveal-section">
    <div class="container position-relative">
        <h2 class="display-6 text-center mb-5 reveal-item" style="font-family: 'Marcellus', serif;">Danh Mục Sản Phẩm</h2>

        <div class="swiper categories-slider">
            <div class="swiper-wrapper">
                @foreach($categories as $category)
                <div class="swiper-slide">
                    <a href="{{ route('categories.show', $category) }}" class="text-decoration-none">
                        <div class="category-card">
                            <div class="category-image">
                                <img src="{{ $category->image ? asset('images/categories/' . $category->image) : asset('images/placeholder.jpg') }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-100 h-100 object-fit-cover"
                                     loading="lazy"
                                     decoding="async">
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



<!-- Featured Products -->
<section class="py-5 bg-light reveal-section" id="featured-products">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center reveal-item">
                <h2 class="mb-4" style="font-family: 'Marcellus', serif;">Sản Phẩm Nổi Bật</h2>
                <p class="text-muted">Khám phá những mẫu điện thoại thông minh cao cấp của chúng tôi</p>
            </div>
        </div>
        <div class="row g-4">
            @foreach($latestProducts as $product)
            <div class="col-md-3 reveal-item delay-{{ $loop->index }}">
                <div class="card h-100 border-0 product-card">
                    <div class="position-relative">
                        <img src="{{ $product->image ? asset('images/products/' . $product->image) : asset('images/placeholder.jpg') }}" 
                             class="card-img-top" alt="{{ $product->name }}"
                             style="height: 300px; object-fit: cover;"
                             loading="lazy"
                             decoding="async">
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



<!-- New Arrivals -->
<section class="py-5 bg-light reveal-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center reveal-item">
                <h2 class="mb-4" style="font-family: 'Marcellus', serif;">Hàng Mới Về</h2>
                <p class="text-muted">Khám phá những mẫu điện thoại mới nhất của chúng tôi</p>
            </div>
        </div>
        <div class="row g-4">
            @foreach($latestProducts->take(3) as $product)
            <div class="col-md-4 reveal-item delay-{{ $loop->index }}">
                <div class="card h-100 border-0">
                    <div class="position-relative" style="height: 340px;">
                        <img src="{{ $product->image ? asset('images/products/' . $product->image) : asset('images/placeholder.jpg') }}" 
                             class="w-100 h-100 object-fit-cover" 
                             alt="{{ $product->name }}"
                             loading="lazy"
                             decoding="async">
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

<!-- @push('styles')
<link rel="stylesheet" href="{{asset('css/IntersectionObserver.css')}}">
<link rel="stylesheet" href="{{ asset('css/home.css')}}">
<link rel="stylesheet" href="{{ asset('css/styleHeart.css') }}">


@endpush -->

@push('scripts')
<script src="{{ asset('js/cart.js') }}"></script>
<script src="{{ asset('js/wishlisthome.js') }}"></script>
<script src="{{ asset('js/home.js') }}"></script>
@endpush
@endsection 