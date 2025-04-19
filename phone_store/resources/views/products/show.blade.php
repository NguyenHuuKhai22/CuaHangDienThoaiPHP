@extends('layouts.app')

@section('title', $product->name . ' - KAIRA')

@push('styles')

<link rel="stylesheet" href="{{ asset('css/styleProductDetail.css')}}">

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
                        <button class="btn btn-dark btn-lg rounded-0 add-to-wishlist" 
                                data-product-id="{{ $product->id }}">
                            <i class="bi bi-heart me-2"></i>
                            <span class="wishlist-text">Thêm vào yêu thích</span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-dark btn-lg rounded-0">
                            <i class="bi bi-cart-plus me-2"></i>Đăng nhập để thêm vào giỏ hàng
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
<script src="{{ asset('js/product-gallery.js') }}"></script>
<script src="{{ asset('js/product-info.js') }}"></script>
<script src="{{ asset('js/cart.js') }}"></script>
<script src="{{ asset('js/wishlist.js') }}"></script>
@endpush