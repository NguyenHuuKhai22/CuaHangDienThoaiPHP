@extends('layouts.admin')

@section('title', 'Chi tiết khuyến mãi')

@section('content')
<style>
.badge-active { background-color: #00A65A; color: white; }
.badge-inactive { background-color: #DD4B39; color: white; }
.badge-upcoming { background-color: #0088cc; color: white; }
.badge-expired { background-color: #777777; color: white; }

.specs-badge {
    display: inline-block;
    padding: 0.25em 0.6em;
    font-size: 12px;
    font-weight: 500;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    margin-right: 4px;
}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chi tiết khuyến mãi</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.promotions.edit', $promotion) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 200px;">Tên khuyến mãi:</th>
                                    <td>{{ $promotion->name }}</td>
                                </tr>
                                <tr>
                                    <th>Mô tả:</th>
                                    <td>{{ $promotion->description ?? 'Không có mô tả' }}</td>
                                </tr>
                                <tr>
                                    <th>Loại giảm giá:</th>
                                    <td>
                                        @if($promotion->discount_type == 'percentage')
                                            Phần trăm
                                        @else
                                            Số tiền cố định
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Giá trị giảm giá:</th>
                                    <td>
                                        @if($promotion->discount_type == 'percentage')
                                            {{ $promotion->discount_value }}%
                                        @else
                                            {{ number_format($promotion->discount_value) }}đ
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 200px;">Ngày bắt đầu:</th>
                                    <td>{{ $promotion->start_date->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày kết thúc:</th>
                                    <td>{{ $promotion->end_date->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Trạng thái:</th>
                                    <td>
                                        @php
                                            $now = now();
                                            $status = 'inactive';
                                            if ($promotion->is_active) {
                                                if ($now < $promotion->start_date) {
                                                    $status = 'upcoming';
                                                } elseif ($now > $promotion->end_date) {
                                                    $status = 'expired';
                                                } else {
                                                    $status = 'active';
                                                }
                                            }
                                        @endphp
                                        <span class="badge badge-{{ $status }}">
                                            @switch($status)
                                                @case('active')
                                                    Đang hoạt động
                                                    @break
                                                @case('inactive')
                                                    Đã tắt
                                                    @break
                                                @case('upcoming')
                                                    Sắp diễn ra
                                                    @break
                                                @case('expired')
                                                    Đã kết thúc
                                                    @break
                                            @endswitch
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ngày tạo:</th>
                                    <td>{{ $promotion->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h4>Sản phẩm áp dụng</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Hình ảnh</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Thông số</th>
                                            <th>Giá gốc</th>
                                            <th>Giá sau giảm</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($promotion->products as $product)
                                            <tr>
                                                <td>{{ $product->id }}</td>
                                                <td>
                                                    <img src="{{ asset('images/products/' . $product->image) }}" 
                                                         alt="{{ $product->name }}"
                                                         style="max-width: 50px; height: auto;">
                                                </td>
                                                <td>{{ $product->name }}</td>
                                                <td>
                                                    <span class="specs-badge">
                                                        <i class="fas fa-palette"></i> {{ $product->color }}
                                                    </span>
                                                    <span class="specs-badge">
                                                        <i class="fas fa-hdd"></i> {{ $product->storage }}GB
                                                    </span>
                                                    <span class="specs-badge">
                                                        <i class="fas fa-memory"></i> {{ $product->ram }}GB RAM
                                                    </span>
                                                </td>
                                                <td>{{ number_format($product->price) }}đ</td>
                                                <td>
                                                    @if($promotion->discount_type == 'percentage')
                                                        {{ number_format($product->price * (1 - $promotion->discount_value/100)) }}đ
                                                    @else
                                                        {{ number_format(max(0, $product->price - $promotion->discount_value)) }}đ
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Không có sản phẩm nào</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 