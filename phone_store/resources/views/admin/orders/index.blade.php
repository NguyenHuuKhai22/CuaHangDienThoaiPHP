@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')
<style>
.badge-pending { background-color: #FFA500; color: white; }
.badge-processing { background-color: #0088cc; color: white; }
.badge-shipping { background-color: #9933CC; color: white; }
.badge-completed { background-color: #00A65A; color: white; }
.badge-cancelled { background-color: #DD4B39; color: white; }
.badge-payment-pending { background-color: #FFA500; color: white; }
.badge-payment-paid { background-color: #00A65A; color: white; }
.badge-payment-failed { background-color: #DD4B39; color: white; }
.badge-payment-refunded { background-color: #0088cc; color: white; }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Danh sách đơn hàng</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Mã đơn hàng</th>
                                    <th>Khách hàng</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Thanh toán</th>
                                    <th>Ngày đặt</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>{{ $order->order_code }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->formatted_total_amount }}</td>
                                    <td>
                                        <span class="badge badge-{{ $order->status }}">
                                            {{ $order->getStatusText() }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-payment-{{ $order->payment_status }}">
                                            {{ $order->getPaymentStatusText() }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Chi tiết
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 