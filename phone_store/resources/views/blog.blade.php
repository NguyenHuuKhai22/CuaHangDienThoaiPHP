@extends('layouts.app')

@section('title', 'Tin Tức - HK Phone Store')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4 reveal-section">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tin Tức</li>
        </ol>
    </nav>

    <!-- Blog Header -->
    <div class="text-center mb-5 reveal-section">
        <h1 class="display-4 mb-3" style="font-family: 'Marcellus', serif;">Tin Tức & Cập Nhật</h1>
        <p class="lead text-muted">Khám phá những tin tức mới nhất về công nghệ và smartphone</p>
    </div>

    <!-- Featured Post -->
    <div class="row mb-5 reveal-section">
        <div class="col-12">
            <div class="card border-0 bg-dark text-white">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-star-fill text-dark fs-3"></i>
                        </div>
                        <h5 class="text-warning mb-0 ms-3">Nổi bật</h5>
                    </div>
                    <h2 class="card-title h1 mb-4">iPhone 15 Pro Max: Đột phá công nghệ mới</h2>
                    <p class="card-text lead mb-4">Khám phá những tính năng đột phá và thiết kế revolutionary của iPhone 15 Pro Max.</p>
                    <a href="#" class="btn btn-warning">Đọc thêm</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Blog Posts Grid -->
    <div class="row g-4 reveal-section">
        <!-- Blog Post -->
        <div class="col-md-4 reveal-item">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-dark bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-camera text-dark fs-4"></i>
                        </div>
                        <small class="text-muted ms-3">24 tháng 3, 2024</small>
                    </div>
                    <h5 class="card-title">So sánh camera Samsung S24 Ultra vs iPhone 15 Pro Max</h5>
                    <p class="card-text text-muted">Đánh giá chi tiết về khả năng chụp ảnh của hai flagship hàng đầu...</p>
                    <a href="#" class="btn btn-outline-dark">Xem chi tiết</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 reveal-item">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-dark bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-phone text-dark fs-4"></i>
                        </div>
                        <small class="text-muted ms-3">23 tháng 3, 2024</small>
                    </div>
                    <h5 class="card-title">Top 5 smartphone tầm trung đáng mua 2024</h5>
                    <p class="card-text text-muted">Những lựa chọn smartphone tốt nhất trong phân khúc 5-10 triệu...</p>
                    <a href="#" class="btn btn-outline-dark">Xem chi tiết</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 reveal-item">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-dark bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-lightning-charge text-dark fs-4"></i>
                        </div>
                        <small class="text-muted ms-3">22 tháng 3, 2024</small>
                    </div>
                    <h5 class="card-title">Tương lai của công nghệ 6G</h5>
                    <p class="card-text text-muted">Khám phá những tiềm năng và thách thức của công nghệ 6G...</p>
                    <a href="#" class="btn btn-outline-dark">Xem chi tiết</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/IntersectionObserver.css') }}">
<style>
    .card {
        transition: transform 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/home.js') }}"></script>
@endpush
@endsection 