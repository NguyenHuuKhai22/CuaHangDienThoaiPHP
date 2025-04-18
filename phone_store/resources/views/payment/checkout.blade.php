@extends('layouts.app')

@section('title', 'Thanh Toán - KAIRA Phone Store')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Thanh Toán</h1>

    <div class="row">
        <!-- Thông tin đơn hàng -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Tổng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart->cartItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('images/products/' . $item->product->image) }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="img-thumbnail me-3" 
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-1">{{ $item->product->name }}</h6>
                                                <p class="text-muted small mb-0">{{ $item->product->category->name }}</p>
                                                @if($item->product->discount_price)
                                                    <div class="mt-1">
                                                        <span class="text-decoration-line-through text-muted small">{{ number_format($item->product->price) }}đ</span>
                                                        <span class="text-danger small ms-2">{{ number_format($item->product->discount_price) }}đ</span>
                                                        <span class="badge bg-danger ms-2">
                                                            -{{ round((($item->product->price - $item->product->discount_price) / $item->product->price) * 100) }}%
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item->price) }}đ</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->price * $item->quantity) }}đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                                    <td><strong>{{ number_format($cart->total_amount) }}đ</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Thông tin giao hàng -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin giao hàng</h5>
                </div>
                <div class="card-body">
                    <form id="shipping-form">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">Tỉnh/Thành phố</label>
                                <input type="text" class="form-control" id="city" name="city" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="district" class="form-label">Quận/Huyện</label>
                                <input type="text" class="form-control" id="district" name="district" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="ward" class="form-label">Phường/Xã</label>
                                <input type="text" class="form-control" id="ward" name="ward" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">Ghi chú</label>
                            <textarea class="form-control" id="note" name="note" rows="2"></textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Phương thức thanh toán -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Phương thức thanh toán</h5>
                </div>
                <div class="card-body">
                    <div class="payment-methods">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="momo" value="momo" checked>
                            <label class="form-check-label d-flex align-items-center" for="momo">
                                <img src="{{ asset('images/payment/momo.png') }}" alt="Momo" class="me-2" style="height: 24px;">
                                Thanh toán qua Momo
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="vnpay" value="vnpay">
                            <label class="form-check-label d-flex align-items-center" for="vnpay">
                                <img src="{{ asset('images/payment/vnpay.png') }}" alt="VNPay" class="me-2" style="height: 24px;">
                                Thanh toán qua VNPay
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod">
                            <label class="form-check-label d-flex align-items-center" for="cod">
                                <i class="bi bi-cash-stack me-2"></i>
                                Thanh toán khi nhận hàng (COD)
                            </label>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <span>{{ number_format($cart->total_amount) }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phí vận chuyển:</span>
                            <span>Miễn phí</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Tổng cộng:</strong>
                            <strong class="text-danger">{{ number_format($cart->total_amount) }}đ</strong>
                        </div>
                        <button type="button" class="btn btn-primary w-100" id="place-order-btn">
                            Đặt hàng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const placeOrderBtn = document.getElementById('place-order-btn');
    const shippingForm = document.getElementById('shipping-form');
    
    placeOrderBtn.addEventListener('click', function() {
        // Kiểm tra form giao hàng
        if (!shippingForm.checkValidity()) {
            shippingForm.reportValidity();
            return;
        }
        
        // Lấy phương thức thanh toán đã chọn
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        
        // Lấy thông tin giao hàng
        const formData = {
            name: document.getElementById('name').value,
            phone: document.getElementById('phone').value,
            address: document.getElementById('address').value,
            city: document.getElementById('city').value,
            district: document.getElementById('district').value,
            ward: document.getElementById('ward').value,
            note: document.getElementById('note').value,
            payment_method: paymentMethod
        };
        
        // Gửi request tạo đơn hàng
        fetch('{{ route("payment.process") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (data.redirect_url) {
                    // Chuyển hướng đến URL được cung cấp
                    window.location.href = data.redirect_url;
                } else if (data.payment_url) {
                    // Chuyển hướng đến trang thanh toán
                    window.location.href = data.payment_url;
                } else {
                    // Đơn hàng COD thành công
                    window.location.href = '{{ route("payment.success") }}';
                }
            } else {
                alert(data.message || 'Có lỗi xảy ra. Vui lòng thử lại.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra. Vui lòng thử lại sau.');
        });
    });
});
</script>
@endpush
@endsection 