@extends('layouts.profile')

@section('title', 'Đơn hàng của tôi - HK')

@push('styles')
<style>
.badge.status-badge {
    --badge-color: #777;
    background-color: var(--badge-color);
    font-size: 14px;
    padding: 6px 12px;
}
</style>
@endpush

@section('profile_content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h4 class="mb-4">Đơn hàng của tôi</h4>

        @if($orders->isEmpty())
            <div class="alert alert-info">
                Bạn chưa có đơn hàng nào.
            </div>
        @else
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thanh toán</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('orders.show', $order) }}" class="text-dark text-decoration-none">
                                        #{{ $order->order_code }}
                                    </a>
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ number_format($order->total_amount) }}đ</td>
                                <td>
                                    <span class="badge status-badge" style="--badge-color: {{ $order->getStatusColor() }}">
                                        {{ $order->getStatusText() }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge status-badge" style="--badge-color: {{ $order->getPaymentStatusColor() }}">
                                        {{ $order->getPaymentStatusText() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('orders.show', $order) }}" 
                                           class="btn btn-sm btn-outline-primary me-2"
                                           title="Xem chi tiết">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($order->canCancel())
                                            <form action="{{ route('orders.cancel', $order) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Hủy đơn hàng"
                                                        onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 