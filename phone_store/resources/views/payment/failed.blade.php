@extends('layouts.app')

@section('title', 'Thanh Toán Thất Bại - KAIRA Phone Store')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="bi bi-x-circle-fill text-danger" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="mb-3">Thanh Toán Thất Bại</h2>
                    <p class="text-muted mb-4">Đã có lỗi xảy ra trong quá trình thanh toán. Vui lòng thử lại hoặc liên hệ với chúng tôi để được hỗ trợ.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-cart me-2"></i>Quay lại giỏ hàng
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-primary">
                            <i class="bi bi-headset me-2"></i>Liên hệ hỗ trợ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 