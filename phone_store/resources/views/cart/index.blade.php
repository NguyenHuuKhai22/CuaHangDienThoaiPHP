@extends('layouts.app')

@section('title', 'Giỏ Hàng - KAIRA Phone Store')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Giỏ Hàng</h1>

    @if($cart && $cart->cartItems->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng</th>
                        <th></th>
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
                                     style="width: 80px; height: 80px; object-fit: cover;">
                                <div>
                                    <h5 class="mb-1">{{ $item->product->name }}</h5>
                                    <p class="text-muted mb-0">{{ $item->product->category->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ number_format($item->price) }}đ</td>
                        <td>
                            <div class="input-group" style="width: 120px;">
                                <button class="btn btn-outline-secondary btn-sm quantity-btn" 
                                        data-action="decrease" 
                                        data-item-id="{{ $item->id }}">-</button>
                                <input type="number" 
                                       class="form-control form-control-sm text-center quantity-input" 
                                       value="{{ $item->quantity }}" 
                                       min="1" 
                                       data-item-id="{{ $item->id }}">
                                <button class="btn btn-outline-secondary btn-sm quantity-btn" 
                                        data-action="increase" 
                                        data-item-id="{{ $item->id }}">+</button>
                            </div>
                        </td>
                        <td>{{ number_format($item->price * $item->quantity) }}đ</td>
                        <td>
                            <button class="btn btn-danger btn-sm remove-item" 
                                    data-item-id="{{ $item->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                        <td><strong>{{ number_format($cart->total_amount) }}đ</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <button class="btn btn-outline-danger" id="clear-cart">
                <i class="bi bi-trash me-2"></i>Xóa giỏ hàng
            </button>
            <a href="#" class="btn btn-primary">
                Thanh toán
                <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-1 text-muted"></i>
            <h3 class="mt-3">Giỏ hàng trống</h3>
            <p class="text-muted">Bạn chưa có sản phẩm nào trong giỏ hàng</p>
            <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                Tiếp tục mua sắm
            </a>
        </div>
    @endif
</div>

@push('styles')
<style>
    .quantity-input {
        width: 50px;
        text-align: center;
    }
    .quantity-btn {
        width: 30px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý nút tăng/giảm số lượng
    document.querySelectorAll('.quantity-btn').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            const currentValue = parseInt(input.value);
            const action = this.dataset.action;
            
            if (action === 'increase') {
                input.value = currentValue + 1;
            } else if (action === 'decrease' && currentValue > 1) {
                input.value = currentValue - 1;
            }
            
            updateQuantity(input);
        });
    });

    // Xử lý khi người dùng nhập số lượng
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            if (this.value < 1) this.value = 1;
            updateQuantity(this);
        });
    });

    // Xử lý nút xóa sản phẩm
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.itemId;
            removeItem(itemId);
        });
    });

    // Xử lý nút xóa giỏ hàng
    document.getElementById('clear-cart')?.addEventListener('click', function() {
        if (confirm('Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?')) {
            clearCart();
        }
    });

    function updateQuantity(input) {
        const itemId = input.dataset.itemId;
        const quantity = input.value;

        fetch(`/cart/items/${itemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }

    function removeItem(itemId) {
        fetch(`/cart/items/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }

    function clearCart() {
        fetch('/cart/clear', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
});
</script>
@endpush
@endsection 