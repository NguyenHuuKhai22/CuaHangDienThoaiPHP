@extends('layouts.app')

@section('title', 'Thanh Toán Thành Công - KAIRA Phone Store')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="mb-3">Thanh Toán Thành Công</h2>
                    <p class="text-muted mb-4">Cảm ơn bạn đã mua hàng tại KAIRA Phone Store. Chúng tôi sẽ xử lý đơn hàng của bạn trong thời gian sớm nhất.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary">
                            <i class="bi bi-house-door me-2"></i>Về trang chủ
                        </a>
                        <a href="{{ route('profile') }}" class="btn btn-primary">
                            <i class="bi bi-person me-2"></i>Xem tài khoản
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 