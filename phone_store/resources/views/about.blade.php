@extends('layouts.app')

@section('title', 'Giới Thiệu - HK Phone Store')

@section('content')
<!-- Hero Section -->
<section class="about-hero position-relative py-5 bg-dark">
    <div class="container position-relative py-5">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center text-white">
                <h1 class="display-4 mb-4" style="font-family: 'Marcellus', serif;">Về Chúng Tôi</h1>
                <p class="lead mb-0">HK Store - Nơi công nghệ gặp gỡ sự tin cậy</p>
            </div>
        </div>
    </div>
</section>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Giới Thiệu</li>
        </ol>
    </nav>

    <!-- Story Section -->
    <section class="py-5 reveal-section">
        <div class="row align-items-center">
            <div class="col-lg-6 reveal-item">
                <div class="bg-light p-5 rounded-3 shadow-sm">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-shop text-white fs-3"></i>
                        </div>
                        <h3 class="h4 mb-0 ms-3" style="font-family: 'Marcellus', serif;">Cửa Hàng Của Chúng Tôi</h3>
                    </div>
                    <p class="text-muted">123 Đường ABC, Quận 7, TP. Hồ Chí Minh</p>
                </div>
            </div>
            <div class="col-lg-6 reveal-item">
                <h2 class="h1 mb-4" style="font-family: 'Marcellus', serif;">Câu Chuyện Của Chúng Tôi</h2>
                <p class="lead text-dark">Được thành lập vào năm 2020, HK Store đã trở thành điểm đến tin cậy cho những người yêu công nghệ.</p>
                <p class="text-muted">Chúng tôi không chỉ đơn thuần là một cửa hàng điện thoại, mà còn là nơi mang đến những trải nghiệm công nghệ tốt nhất cho khách hàng. Với đội ngũ nhân viên chuyên nghiệp và nhiệt tình, chúng tôi cam kết mang đến sự hài lòng cho mọi khách hàng.</p>
                <div class="d-flex gap-4 mt-4">
                    <div class="text-center">
                        <h3 class="h2 text-dark mb-0">3+</h3>
                        <p class="text-muted mb-0">Năm kinh nghiệm</p>
                    </div>
                    <div class="text-center">
                        <h3 class="h2 text-dark mb-0">10k+</h3>
                        <p class="text-muted mb-0">Khách hàng hài lòng</p>
                    </div>
                    <div class="text-center">
                        <h3 class="h2 text-dark mb-0">50+</h3>
                        <p class="text-muted mb-0">Nhân viên chuyên nghiệp</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-5 bg-light reveal-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="h1 mb-3" style="font-family: 'Marcellus', serif;">Giá Trị Cốt Lõi</h2>
                <p class="text-muted">Những giá trị mà chúng tôi luôn hướng đến</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4 reveal-item">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="icon-box bg-dark bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                                <i class="bi bi-shield-check display-4 text-dark"></i>
                            </div>
                            <h3 class="h4 mb-3">Chất Lượng</h3>
                            <p class="text-muted mb-0">Cam kết cung cấp sản phẩm chính hãng với chất lượng tốt nhất, đảm bảo nguồn gốc xuất xứ rõ ràng.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 reveal-item">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="icon-box bg-dark bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                                <i class="bi bi-heart display-4 text-dark"></i>
                            </div>
                            <h3 class="h4 mb-3">Tận Tâm</h3>
                            <p class="text-muted mb-0">Luôn đặt lợi ích và trải nghiệm của khách hàng lên hàng đầu, phục vụ với tất cả sự nhiệt tình.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 reveal-item">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="icon-box bg-dark bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                                <i class="bi bi-lightning-charge display-4 text-dark"></i>
                            </div>
                            <h3 class="h4 mb-3">Sáng Tạo</h3>
                            <p class="text-muted mb-0">Không ngừng đổi mới để mang đến những giải pháp tốt nhất, luôn cập nhật công nghệ mới nhất.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-5 reveal-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="h1 mb-3" style="font-family: 'Marcellus', serif;">Đội Ngũ Của Chúng Tôi</h2>
                <p class="text-muted">Những người đã tạo nên HK Store</p>
            </div>
            <div class="row g-4">
                <div class="col-md-3 reveal-item">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="bg-dark bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 120px; height: 120px;">
                                <i class="bi bi-person display-1 text-dark"></i>
                            </div>
                            <h5 class="card-title mb-1">Nguyễn Văn A</h5>
                            <p class="text-muted mb-0">Giám đốc điều hành</p>
                            <div class="d-flex justify-content-center gap-2 mt-3">
                                <a href="#" class="text-dark"><i class="bi bi-facebook"></i></a>
                                <a href="#" class="text-dark"><i class="bi bi-twitter"></i></a>
                                <a href="#" class="text-dark"><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Add more team members as needed -->
            </div>
        </div>
    </section>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/IntersectionObserver.css') }}">
<style>
    .about-hero {
        min-height: 300px;
    }
    .icon-box {
        transition: transform 0.3s ease;
    }
    .card:hover .icon-box {
        transform: scale(1.1);
    }
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