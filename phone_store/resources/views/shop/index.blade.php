@extends('layouts.app')

@section('title', 'Cửa hàng - KAIRA Phone Store')

@push('styles')
<style>
.filter-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 0.5rem;
}

.filter-section .form-check {
    margin-bottom: 0.5rem;
}

.product-card {
    transition: all 0.3s ease;
    border: none !important;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.product-image {
    height: 300px;
    object-fit: cover;
}

.product-overlay {
    background: rgba(255,255,255,0.9);
    transition: all 0.3s ease;
}

.product-card:hover .product-overlay {
    opacity: 1 !important;
}

.sort-select {
    width: auto !important;
}

.category-filter.active {
    color: #000;
    font-weight: 600;
}

.price-filter.active {
    color: #000;
    font-weight: 600;
}
</style>
@endpush

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cửa hàng</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Filters Sidebar -->
        <div class="col-lg-3">
            <div class="filter-section">
                <h5 class="mb-3">Danh mục sản phẩm</h5>
                <div class="list-group list-group-flush">
                    <a href="{{ route('shop.index') }}" 
                       class="list-group-item list-group-item-action category-filter {{ !request('category') ? 'active' : '' }}">
                        Tất cả sản phẩm
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('shop.index', ['category' => $category->slug]) }}" 
                           class="list-group-item list-group-item-action category-filter {{ request('category') == $category->slug ? 'active' : '' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>

                <h5 class="mb-3 mt-4">Khoảng giá</h5>
                <div class="list-group list-group-flush">
                    <a href="{{ route('shop.index', array_merge(request()->except('price'), ['price' => 'under-10'])) }}"
                       class="list-group-item list-group-item-action price-filter {{ request('price') == 'under-10' ? 'active' : '' }}">
                        Dưới 10 triệu
                    </a>
                    <a href="{{ route('shop.index', array_merge(request()->except('price'), ['price' => '10-20'])) }}"
                       class="list-group-item list-group-item-action price-filter {{ request('price') == '10-20' ? 'active' : '' }}">
                        10 - 20 triệu
                    </a>
                    <a href="{{ route('shop.index', array_merge(request()->except('price'), ['price' => '20-30'])) }}"
                       class="list-group-item list-group-item-action price-filter {{ request('price') == '20-30' ? 'active' : '' }}">
                        20 - 30 triệu
                    </a>
                    <a href="{{ route('shop.index', array_merge(request()->except('price'), ['price' => 'over-30'])) }}"
                       class="list-group-item list-group-item-action price-filter {{ request('price') == 'over-30' ? 'active' : '' }}">
                        Trên 30 triệu
                    </a>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            <!-- Sort Options -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <span class="text-muted">Hiển thị {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} / {{ $products->total() ?? 0 }} sản phẩm</span>
                </div>
                <div class="d-flex align-items-center">
                    <label class="me-2 text-nowrap">Sắp xếp theo:</label>
                    <select class="form-select sort-select" onchange="window.location.href=this.value">
                        <option value="{{ route('shop.index', array_merge(request()->except('sort'), ['sort' => 'latest'])) }}"
                                {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>
                            Mới nhất
                        </option>
                        <option value="{{ route('shop.index', array_merge(request()->except('sort'), ['sort' => 'price-asc'])) }}"
                                {{ request('sort') == 'price-asc' ? 'selected' : '' }}>
                            Giá tăng dần
                        </option>
                        <option value="{{ route('shop.index', array_merge(request()->except('sort'), ['sort' => 'price-desc'])) }}"
                                {{ request('sort') == 'price-desc' ? 'selected' : '' }}>
                            Giá giảm dần
                        </option>
                        <option value="{{ route('shop.index', array_merge(request()->except('sort'), ['sort' => 'name-asc'])) }}"
                                {{ request('sort') == 'name-asc' ? 'selected' : '' }}>
                            Tên A-Z
                        </option>
                        <option value="{{ route('shop.index', array_merge(request()->except('sort'), ['sort' => 'name-desc'])) }}"
                                {{ request('sort') == 'name-desc' ? 'selected' : '' }}>
                            Tên Z-A
                        </option>
                    </select>
                </div>
            </div>

            <!-- Products Grid -->
            @if($products->isNotEmpty())
                <div class="row g-4">
                    @foreach($products as $product)
                        <div class="col-md-4">
                            <div class="card h-100 product-card">
                                <div class="position-relative">
                                    <img src="{{ $product->image ? asset('images/products/' . $product->image) : asset('images/placeholder.jpg') }}" 
                                         class="card-img-top product-image" 
                                         alt="{{ $product->name }}">
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
                    <p class="text-muted">Không có sản phẩm nào phù hợp với bộ lọc của bạn</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-primary mt-3">
                        Xóa bộ lọc
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/styleHeart.css') }}">
@endpush
@push('scripts')
<script src="{{ asset('js/cart.js') }}"></script>
<script src="{{ asset('js/wishlisthome.js') }}"></script>
@endpush 