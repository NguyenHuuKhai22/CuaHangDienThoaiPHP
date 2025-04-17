@extends('layouts.app')

@section('title', $category->name . ' - HK')

@push('styles')
<style>
    .product-grid {
        margin-top: 2rem;
    }
    .product-card {
        background: #fff;
        border: 1px solid #eee;
        transition: all 0.3s ease;
    }
    .product-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(-5px);
    }
    .product-image {
        position: relative;
        padding-top: 100%;
        overflow: hidden;
    }
    .product-image img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 1rem;
    }
    .discount-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: #dc3545;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-weight: 500;
        font-size: 0.875rem;
    }
    .product-info {
        padding: 1rem;
    }
    .product-name {
        font-family: 'Mulish', sans-serif;
        font-size: 0.875rem;
        font-weight: 600;
        color: #333;
        text-decoration: none;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 2.5rem;
        margin-bottom: 0.5rem;
    }
    .product-name:hover {
        color: #0056b3;
    }
    .product-price {
        font-weight: 700;
        color: #dc3545;
        font-size: 1rem;
    }
    .original-price {
        text-decoration: line-through;
        color: #6c757d;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/shop" class="text-decoration-none">Cửa hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    <!-- Category Header -->
    <div class="text-center mb-4">
        <h1 class="display-6 mb-3">{{ $category->name }}</h1>
        <p class="text-muted">Khám phá các sản phẩm {{ $category->name }} chất lượng cao tại HK STORE</p>
    </div>

    @if($products->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-inbox display-1 text-muted"></i>
            <p class="mt-3 text-muted">Không có sản phẩm nào trong danh mục này.</p>
        </div>
    @else
        <!-- Products Grid -->
        <div class="row g-4 product-grid">
            @foreach($products as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card h-100">
                        <div class="product-image">
                            @if($product->discount_price)
                                @php
                                    $discountPercent = round((($product->price - $product->discount_price) / $product->price) * 100);
                                @endphp
                                <div class="discount-badge">-{{ $discountPercent }}%</div>
                            @endif
                            <a href="{{ route('products.show', $product) }}">
                                <img src="{{ $product->image ? asset('images/products/' . $product->image) : asset('images/no-image.png') }}" 
                                     alt="{{ $product->name }}"
                                     class="img-fluid">
                            </a>
                        </div>
                        <div class="product-info">
                            <a href="{{ route('products.show', $product) }}" class="product-name">
                                {{ $product->name }}
                            </a>
                            <div class="mt-auto">
                                @if($product->discount_price)
                                    <div class="original-price">{{ number_format($product->price) }}đ</div>
                                    <div class="product-price">{{ number_format($product->discount_price) }}đ</div>
                                @else
                                    <div class="product-price">{{ number_format($product->price) }}đ</div>
                                @endif
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
    @endif
</div>
@endsection