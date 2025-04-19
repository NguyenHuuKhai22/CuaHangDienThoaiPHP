@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chi tiết đơn hàng #{{ $order->order_code }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Thông tin khách hàng</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Tên khách hàng:</th>
                                    <td>{{ $order->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $order->user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Số điện thoại:</th>
                                    <td>{{ $order->shipping_phone }}</td>
                                </tr>
                                <tr>
                                    <th>Địa chỉ:</th>
                                    <td>{{ $order->shipping_address }}, {{ $order->shipping_ward }}, {{ $order->shipping_district }}, {{ $order->shipping_city }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4>Thông tin đơn hàng</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Mã đơn hàng:</th>
                                    <td>{{ $order->order_code }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày đặt:</th>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Phương thức thanh toán:</th>
                                    <td>
                                        @if($order->payment_method == 'cod')
                                            Thanh toán khi nhận hàng
                                        @elseif($order->payment_method == 'momo')
                                            Thanh toán qua Momo
                                        @else
                                            Thanh toán qua VNPay
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ghi chú:</th>
                                    <td>{{ $order->note ?? 'Không có ghi chú' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h4>Danh sách sản phẩm</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Màu sắc</th>
                                        <th>RAM</th>
                                        <th>Bộ nhớ</th>
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
                                                <img src="{{ asset('images/products/' . $item->product_image) }}" 
                                                     alt="{{ $item->product_name }}" 
                                                     class="img-thumbnail" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                                <div class="ml-2">
                                                    {{ $item->product_name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $item->product_color }}</td>
                                        <td>{{ $item->product_ram }}</td>
                                        <td>{{ $item->product_storage }}</td>
                                        <td>{{ number_format($item->price) }}đ</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->total) }}đ</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" class="text-right">Tổng tiền:</th>
                                        <th>{{ $order->formatted_total_amount }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h4>Cập nhật trạng thái</h4>
                            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Trạng thái đơn hàng</label>
                                            <select name="status" class="form-control">
                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                                <option value="pending_confirmation" {{ $order->status == 'pending_confirmation' ? 'selected' : '' }}>Chờ xác nhận</option>
                                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                                <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Trạng thái thanh toán</label>
                                            <select name="payment_status" class="form-control">
                                                <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                                <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Thanh toán thất bại</option>
                                                <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 