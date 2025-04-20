@extends('layouts.app')

@section('title', 'Liên Hệ - HK Phone Store')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4 reveal-section">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Liên Hệ</li>
        </ol>
    </nav>

    <!-- Contact Header -->
    <div class="text-center mb-5 reveal-section">
        <h1 class="display-4 mb-3" style="font-family: 'Marcellus', serif;">Liên Hệ Với Chúng Tôi</h1>
        <p class="lead text-muted">Chúng tôi luôn sẵn sàng hỗ trợ bạn</p>
    </div>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="row gy-4">
        <!-- Contact Information -->
        <div class="col-lg-4 reveal-section">
            <div class="p-4 bg-light rounded-3 h-100">
                <h3 class="h4 mb-4" style="font-family: 'Marcellus', serif;">Thông Tin Liên Hệ</h3>
                
                <div class="d-flex mb-4 reveal-item">
                    <i class="bi bi-geo-alt text-primary fs-4 me-3"></i>
                    <div>
                        <h4 class="h6 mb-1">Địa Chỉ</h4>
                        <p class="text-muted mb-0">123 Đường ABC, Quận 7<br>TP. Hồ Chí Minh</p>
                    </div>
                </div>

                <div class="d-flex mb-4 reveal-item">
                    <i class="bi bi-telephone text-primary fs-4 me-3"></i>
                    <div>
                        <h4 class="h6 mb-1">Điện Thoại</h4>
                        <p class="text-muted mb-0">+84 123 456 789</p>
                    </div>
                </div>

                <div class="d-flex mb-4 reveal-item">
                    <i class="bi bi-envelope text-primary fs-4 me-3"></i>
                    <div>
                        <h4 class="h6 mb-1">Email</h4>
                        <p class="text-muted mb-0">contact@hkstore.com</p>
                    </div>
                </div>

                <div class="d-flex reveal-item">
                    <i class="bi bi-clock text-primary fs-4 me-3"></i>
                    <div>
                        <h4 class="h6 mb-1">Giờ Làm Việc</h4>
                        <p class="text-muted mb-0">Thứ 2 - Chủ Nhật: 8:00 - 21:00</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-8 reveal-section">
            <div class="bg-white p-4 rounded-3 shadow-sm">
                <h3 class="h4 mb-4" style="font-family: 'Marcellus', serif;">Gửi Tin Nhắn</h3>
                <form action="{{ route('contact.submit') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-6 reveal-item">
                        <label for="name" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="col-md-6 reveal-item">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="col-12 reveal-item">
                        <label for="subject" class="form-label">Tiêu đề</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="col-12 reveal-item">
                        <label for="message" class="form-label">Nội dung</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <div class="col-12 reveal-item">
                        <button type="submit" class="btn btn-primary">Gửi tin nhắn</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

     <!-- Map Section -->
     <div class="mt-5 reveal-section">
        <div class="ratio ratio-21x9">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5546.417970999786!2d106.82561053951012!3d10.586228377241367!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31753ec9bb4a25a5%3A0x74ee46543e115534!2sAn%20Ngh%C4%A9a%20Bridge!5e0!3m2!1sfr!2s!4v1745131103452!5m2!1sfr!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div> 
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/IntersectionObserver.css') }}">
<style>
    .reveal-item {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.6s ease;
    }
    
    .reveal-item.active {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/home.js') }}"></script>
@endpush
@endsection 