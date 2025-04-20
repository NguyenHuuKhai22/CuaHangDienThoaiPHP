@extends('layouts.app')

@section('title', 'Danh sách yêu thích - HK Phone Store')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Danh sách yêu thích</h1>

    @if($wishlist && $wishlist->wishlistItems->count() > 0)
        <div class="row g-4">
            @foreach($wishlist->wishlistItems as $item)
                <div class="col-md-3">
                    <div class="card h-100 border-0 product-card" data-product-id="{{ $item->product->id }}">
                        <div class="position-relative">
                            <img src="{{ $item->product->image ? asset('images/products/' . $item->product->image) : asset('images/placeholder.jpg') }}" 
                                 class="card-img-top" 
                                 alt="{{ $item->product->name }}"
                                 style="height: 300px; object-fit: cover;">
                            <div class="product-overlay position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center gap-2 opacity-0">
                                <button class="btn btn-sm btn-dark rounded-circle p-2 add-to-cart" 
                                        data-product-id="{{ $item->product->id }}"
                                        title="Thêm vào giỏ hàng">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                                <button class="btn btn-sm btn-danger rounded-circle p-2 remove-from-wishlist"
                                        data-product-id="{{ $item->product->id }}"
                                        title="Xóa khỏi yêu thích">
                                    <i class="bi bi-heart-fill"></i>
                                </button>
                                <a href="{{ route('products.show', $item->product) }}" 
                                   class="btn btn-sm btn-dark rounded-circle p-2" 
                                   title="Xem chi tiết">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body px-0 text-center">
                            <p class="text-muted small mb-1">{{ $item->product->category->name }}</p>
                            <h5 class="card-title">{{ $item->product->name }}</h5>
                            <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                                @if($item->product->discount_price)
                                    <p class="card-text text-danger mb-0">{{ $item->product->formatted_discount_price }}</p>
                                    <p class="card-text text-muted mb-0"><del>{{ $item->product->formatted_price }}</del></p>
                                @else
                                    <p class="card-text mb-0">{{ $item->product->formatted_price }}</p>
                                @endif
                            </div>
                            <div class="specs text-muted small">
                                <span class="me-2">{{ $item->product->ram }}</span>|
                                <span class="mx-2">{{ $item->product->storage }}</span>|
                                <span class="ms-2">{{ $item->product->color }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-heart-fill display-1 text-muted"></i>
            <h3 class="mt-3">Danh sách yêu thích trống</h3>
            <p class="text-muted">Bạn chưa có sản phẩm nào trong danh sách yêu thích</p>
            <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                Tiếp tục mua sắm
            </a>
        </div>
    @endif
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/styleHeart.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/cart.js') }}"></script>
<script src="{{ asset('js/wishlist.js') }}"></script>
@endpush
@endsection 