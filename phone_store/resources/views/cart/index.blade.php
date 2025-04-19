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
                    <tr data-stock="{{ $item->product->stock_quantity }}">
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('images/products/' . $item->product->image) }}" 
                                     alt="{{ $item->product->name }} " 
                                     class="img-thumbnail me-3" 
                                     style="width: 80px; height: 80px; object-fit: cover;">
                                <div>
                                    <h5 class="mb-1">{{ $item->product->name }}</h5>
                                    <p class="text-muted mb-0">{{ $item->product->category->name }} - số lượng còn lại: {{ $item->product->stock_quantity }}</p>
                                    @if($item->product->discount_price)
                                        <div class="mt-1">
                                            <span class="text-decoration-line-through text-muted" hidden="true">{{ number_format($item->product->price) }}đ</span>
                                            <span class="text-danger ms-2">{{ number_format($item->product->discount_price) }}đ</span>
                                            <span class="badge bg-danger ms-2">
                                                -{{ round((($item->product->price - $item->product->discount_price) / $item->product->price) * 100) }}%
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($item->product->discount_price)
                                <div>
                                    <span class="text-decoration-line-through text-muted" hidden="true">{{ number_format($item->product->price) }}đ</span>
                                    <br>
                                    <span class="text-danger">{{ number_format($item->product->discount_price) }}đ</span>
                                </div>
                            @else
                                {{ number_format($item->price) }}đ
                            @endif
                        </td>
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
                        <td>
                            @if($item->product->discount_price)
                                <div>
                                    <span class="text-decoration-line-through text-muted">{{ number_format($item->product->price * $item->quantity) }}đ</span>
                                    <br>
                                    <span class="text-danger">{{ number_format($item->product->discount_price * $item->quantity) }}đ</span>
                                </div>
                            @else
                                {{ number_format($item->price * $item->quantity) }}đ
                            @endif
                        </td>
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
                        <td>
                            @php
                                $totalOriginal = $cart->cartItems->sum(function($item) {
                                    return $item->product->price * $item->quantity;
                                });
                                $totalDiscounted = $cart->cartItems->sum(function($item) {
                                    return ($item->product->discount_price ?: $item->product->price) * $item->quantity;
                                });
                            @endphp
                            <div id="cartTotal">
                                @if($totalOriginal > $totalDiscounted)
                                    <div>
                                        <span class="text-decoration-line-through text-muted" id="originalTotal">{{ number_format($totalOriginal) }}đ</span>
                                        <br>
                                        <strong class="text-danger" id="discountedTotal">{{ number_format($totalDiscounted) }}đ</strong>
                                        <span class="badge bg-danger ms-2">
                                            -{{ round((($totalOriginal - $totalDiscounted) / $totalOriginal) * 100) }}%
                                        </span>
                                    </div>
                                @else
                                    <strong>{{ number_format($totalDiscounted) }}đ</strong>
                                @endif
                            </div>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <button class="btn btn-outline-danger" id="clear-cart">
                <i class="bi bi-trash me-2"></i>Xóa giỏ hàng
            </button>
            <a href="{{ route('payment.checkout') }}" class="btn btn-primary">
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
    // Cấu hình Toastr
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "timeOut": "3000"
    };

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
    // cập nhật số lượng
    function updateQuantity(input) {
        const itemId = input.dataset.itemId;
        const quantity = parseInt(input.value);
        const row = input.closest('tr');
        const stockQuantity = parseInt(row.dataset.stock) || 0;

        // Kiểm tra số lượng tồn kho
        if (quantity > stockQuantity) {
            toastr.error(`Số lượng tồn kho chỉ còn ${stockQuantity} sản phẩm`);
            input.value = stockQuantity;
            return;
        }

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
                // Cập nhật giá tổng của sản phẩm
                const totalCell = row.querySelector('td:nth-child(4)');
                const priceInfo = row.querySelector('td:nth-child(2)');
                
                // Lấy giá từ phần tử giá
                const hasDiscount = priceInfo.querySelector('.text-danger') !== null;
                
                if (hasDiscount) {
                    // Lấy giá gốc và giá giảm từ DOM
                    const originalPriceElement = row.querySelector('td:nth-child(1) .text-decoration-line-through');
                    const discountPriceElement = priceInfo.querySelector('.text-danger');
                    
                    const originalPrice = originalPriceElement ? parseFloat(originalPriceElement.textContent.replace(/[^\d]/g, '')) : 0;
                    const discountPrice = discountPriceElement ? parseFloat(discountPriceElement.textContent.replace(/[^\d]/g, '')) : 0;
                    
                    totalCell.innerHTML = `
                        <div>
                            <span class="text-decoration-line-through text-muted">${formatPrice(originalPrice * quantity)}đ</span>
                            <br>
                            <span class="text-danger">${formatPrice(discountPrice * quantity)}đ</span>
                        </div>
                    `;
                } else {
                    const price = parseFloat(priceInfo.textContent.replace(/[^\d]/g, ''));
                    totalCell.textContent = `${formatPrice(price * quantity)}đ`;
                }

                // Tính lại tổng giỏ hàng
                let totalOriginal = 0;
                let totalDiscounted = 0;

                document.querySelectorAll('tbody tr').forEach(tr => {
                    const qty = parseInt(tr.querySelector('.quantity-input').value) || 0;
                    const rowTotal = tr.querySelector('td:nth-child(4)');
                    
                    if (rowTotal.querySelector('.text-danger')) {
                        // Sản phẩm có giảm giá
                        const originalPrice = parseFloat(rowTotal.querySelector('.text-decoration-line-through').textContent.replace(/[^\d]/g, ''));
                        const discountPrice = parseFloat(rowTotal.querySelector('.text-danger').textContent.replace(/[^\d]/g, ''));
                        
                        totalOriginal += originalPrice;
                        totalDiscounted += discountPrice;
                    } else {
                        // Sản phẩm không giảm giá
                        const price = parseFloat(rowTotal.textContent.replace(/[^\d]/g, ''));
                        totalOriginal += price;
                        totalDiscounted += price;
                    }
                });

                // Cập nhật hiển thị tổng cộng
                const cartTotalDiv = document.getElementById('cartTotal');
                if (totalOriginal > totalDiscounted) {
                    const discountPercent = Math.round(((totalOriginal - totalDiscounted) / totalOriginal) * 100);
                    cartTotalDiv.innerHTML = `
                        <div>
                            <span class="text-decoration-line-through text-muted">${formatPrice(totalOriginal)}đ</span>
                            <br>
                            <strong class="text-danger">${formatPrice(totalDiscounted)}đ</strong>
                            <span class="badge bg-danger ms-2">-${discountPercent}%</span>
                        </div>
                    `;
                } else {
                    cartTotalDiv.innerHTML = `<strong>${formatPrice(totalDiscounted)}đ</strong>`;
                }
                
                toastr.success('Cập nhật số lượng thành công');
            } else {
                toastr.error(data.message || 'Có lỗi xảy ra khi cập nhật số lượng');
                // Khôi phục lại số lượng cũ nếu có lỗi
                input.value = data.old_quantity || 1;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Có lỗi xảy ra khi cập nhật số lượng');
        });
    }
    // xóa sản phẩm
    function removeItem(itemId) {
        const row = document.querySelector(`.remove-item[data-item-id="${itemId}"]`).closest('tr');

        fetch(`/cart/items/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                row.remove();
                updateCartTotal(data.cart_total);
                toastr.success('Xóa sản phẩm thành công');

                // Kiểm tra nếu giỏ hàng trống
                if (document.querySelectorAll('tbody tr').length === 0) {
                    location.reload(); // Reload để hiển thị giỏ hàng trống
                }
            } else {
                toastr.error(data.message || 'Có lỗi xảy ra khi xóa sản phẩm');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Có lỗi xảy ra khi xóa sản phẩm');
        });
    }
    //xóa giỏ hàng
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
                document.querySelector('tbody').innerHTML = '';
                updateCartTotal(0);
                toastr.success('Xóa giỏ hàng thành công');
                location.reload(); // Reload để hiển thị giỏ hàng trống
            } else {
                toastr.error(data.message || 'Có lỗi xảy ra khi xóa giỏ hàng');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Có lỗi xảy ra khi xóa giỏ hàng');
        });
    }
    //update lại tổng tiền
    function updateCartTotal(total) {
        const cartTotalDiv = document.getElementById('cartTotal');
        if (!cartTotalDiv) return;

        // Tính tổng giá gốc và giá giảm từ tất cả các sản phẩm
        let totalOriginal = 0;
        let totalDiscounted = 0;

        document.querySelectorAll('tbody tr').forEach(row => {
            const quantity = parseInt(row.querySelector('.quantity-input').value) || 0;
            const totalCell = row.querySelector('td:nth-child(4)');
            if (!totalCell) return;
            
            // Kiểm tra nếu có giá giảm
            const discountPrice = totalCell.querySelector('.text-danger');
            if (discountPrice) {
                const originalPriceElement = totalCell.querySelector('.text-decoration-line-through');
                const discountPriceElement = discountPrice;
                
                if (originalPriceElement && discountPriceElement) {
                    const originalPrice = parseFloat(originalPriceElement.textContent.replace(/[^\d]/g, '')) || 0;
                    const salePrice = parseFloat(discountPriceElement.textContent.replace(/[^\d]/g, '')) || 0;
                    
                    totalOriginal += originalPrice;
                    totalDiscounted += salePrice;
                }
            } else {
                const price = parseFloat(totalCell.textContent.replace(/[^\d]/g, '')) || 0;
                totalOriginal += price;
                totalDiscounted += price;
            }
        });

        // Cập nhật hiển thị tổng cộng
        if (totalOriginal > totalDiscounted) {
            const discountPercent = Math.round(((totalOriginal - totalDiscounted) / totalOriginal) * 100);
            cartTotalDiv.innerHTML = `
                <div>
                    <span class="text-decoration-line-through text-muted">${formatPrice(totalOriginal)}đ</span>
                    <br>
                    <strong class="text-danger">${formatPrice(totalDiscounted)}đ</strong>
                    <span class="badge bg-danger ms-2">-${discountPercent}%</span>
                </div>
            `;
        } else {
            cartTotalDiv.innerHTML = `<strong>${formatPrice(totalDiscounted)}đ</strong>`;
        }
    }
    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price);
    }
});
</script>
@endpush
@endsection 