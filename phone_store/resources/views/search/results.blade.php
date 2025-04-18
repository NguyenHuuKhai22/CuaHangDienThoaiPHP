@extends('layouts.app')

@section('title', 'Kết quả tìm kiếm - HK')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Kết quả tìm kiếm</li>
        </ol>
    </nav>

    <div class="mb-4">
        <h1 class="h3 mb-2">Kết quả tìm kiếm cho "{{ $query }}"</h1>
        <p class="text-muted">Tìm thấy {{ $products->total() }} sản phẩm</p>
    </div>

    @if($products->isNotEmpty())
        <div class="row g-4">
            @foreach($products as $product)
                <div class="col-md-3">
                    <div class="card h-100 product-card">
                        <div class="position-relative">
                            <img src="{{ $product->image ? asset('images/products/' . $product->image) : asset('images/placeholder.jpg') }}" 
                                 class="card-img-top product-image" 
                                 alt="{{ $product->name }}"
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
                                @endauth
                                <a href="{{ route('products.show', $product) }}" 
                                   class="btn btn-sm btn-dark rounded-circle p-2" 
                                   title="Xem chi tiết">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <p class="text-muted small mb-1">{{ $product->category->name }}</p>
                            <h5 class="card-title">{{ $product->name }}</h5>
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

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-search display-1 text-muted"></i>
            <h3 class="mt-3">Không tìm thấy sản phẩm</h3>
            <p class="text-muted">Thử tìm kiếm với từ khóa khác</p>
            <a href="{{ route('shop.index') }}" class="btn btn-dark mt-3">
                Xem tất cả sản phẩm
            </a>
        </div>
    @endif
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/styleHeart.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/cart.js') }}"></script>
<script src="{{ asset('js/wishlisthome.js') }}"></script>
@endpush
@endsection 