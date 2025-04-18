@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng - HK')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-person-circle fs-1 me-3"></i>
                        <div>
                            <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                            <p class="text-muted mb-0 text-truncate" style="max-width: 200px;" title="{{ Auth::user()->email }}">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <hr>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile') }}">
                                <i class="bi bi-person me-2"></i> Thông tin cá nhân
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('orders.index') }}">
                                <i class="bi bi-bag me-2"></i> Đơn hàng của tôi
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Chi tiết đơn hàng #{{ $order->order_code }}</h4>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="mb-3">Thông tin đơn hàng</h5>
                            <div class="mb-2">
                                <strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                            </div>
                            <div class="mb-2">
                                <strong>Trạng thái:</strong> 
                                <span class="badge bg-{{ $order->getStatusBadgeClass() }}">
                                    {{ $order->getStatusText() }}
                                </span>
                            </div>
                            <div class="mb-2">
                                <strong>Thanh toán:</strong>
                                <span class="badge bg-{{ $order->getPaymentStatusBadgeClass() }}">
                                    {{ $order->getPaymentStatusText() }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-3">Thông tin giao hàng</h5>
                            <div class="mb-2">
                                <strong>Người nhận:</strong> {{ $order->shipping_name }}
                            </div>
                            <div class="mb-2">
                                <strong>Điện thoại:</strong> {{ $order->shipping_phone }}
                            </div>
                            <div class="mb-2">
                                <strong>Địa chỉ:</strong> {{ $order->shipping_address }}
                            </div>
                            <div class="mb-2">
                                <strong>Ghi chú:</strong> {{ $order->note ?? 'Không có' }}
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-3">Sản phẩm</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Đơn giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('images/products/' . $item->product->image) }}" alt="{{ $item->product->name }}" 
                                                     class="img-thumbnail me-3" style="width: 150px; height: 150px; object-fit: cover;">
                                                <div>
                                                    <div>{{ $item->product->name }}</div>
                                                    <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Tổng tiền:</strong></td>
                                    <td><strong>{{ $order->formatted_total_amount }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white py-3">
                    @if($order->canCancel())
                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                                <i class="bi bi-x"></i> Hủy đơn hàng
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 