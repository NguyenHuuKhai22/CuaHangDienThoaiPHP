@extends('layouts.admin')

@section('title', 'Dashboard - Admin Panel')

@section('content')
<div class="container-fluid py-4">
    

    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card users h-100 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Tổng người dùng</h6>
                        <div class="d-flex align-items-baseline">
                            <h3 class="mb-0 fw-bold">{{ number_format($totalUsers) }}</h3>
                            <small class="text-success ms-2">
                                <i class="fas fa-arrow-up me-1"></i>Tổng
                            </small>
                        </div>
                    </div>
                    <div class="icon rounded-circle">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card products h-100 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Sản phẩm</h6>
                        <div class="d-flex align-items-baseline">
                            <h3 class="mb-0 fw-bold">{{ number_format($totalProducts) }}</h3>
                            <small class="text-success ms-2">
                                <i class="fas fa-arrow-up me-1"></i>Tổng
                            </small>
                        </div>
                    </div>
                    <div class="icon rounded-circle">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card orders h-100 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Đơn hàng</h6>
                        <div class="d-flex align-items-baseline">
                            <h3 class="mb-0 fw-bold">{{ number_format($totalOrders) }}</h3>
                            <small class="text-success ms-2">
                                <i class="fas fa-arrow-up me-1"></i>Tổng
                            </small>
                        </div>
                    </div>
                    <div class="icon rounded-circle">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card revenue h-100 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Doanh thu</h6>
                        <div class="d-flex align-items-baseline">
                            <h3 class="mb-0 fw-bold">{{ number_format($totalRevenue, 0, ',', '.') }}₫</h3>
                            <small class="{{ $revenueGrowth >= 0 ? 'text-success' : 'text-danger' }} ms-2">
                                <i class="fas fa-arrow-{{ $revenueGrowth >= 0 ? 'up' : 'down' }} me-1"></i>
                                {{ abs($revenueGrowth) }}%
                            </small>
                        </div>
                    </div>
                    <div class="icon rounded-circle">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <div class="chart-card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-4">Thống kê doanh thu tuần này</h5>
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="revenueChart" 
                            data-labels='@json($labels)'
                            data-revenue-data='@json($revenueData)'>
                        </canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="chart-card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-4">Phân bố sản phẩm</h5>
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="categoryDistribution"
                            data-labels='@json($categoryDistribution->pluck('label'))'
                            data-values='@json($categoryDistribution->pluck('value'))'>
                        </canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- san pham duoc mua gan day -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-4">Sản phẩm được mua gần đây</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Lần mua cuối</th>
                                    <th>Số lần mua</th>
                                    <th>Tổng số lượng</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentProducts as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('images/products/' . $item->product->image) }}" 
                                                alt="{{ $item->product->name }}" 
                                                class="rounded"
                                                style="width: 40px; height: 40px; object-fit: cover;">
                                            <div class="ms-3">
                                                <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                <small class="text-muted">
                                                    @if($item->product->discount_price)
                                                        <span class="text-decoration-line-through">{{ number_format($item->product->price, 0, ',', '.') }}₫</span>
                                                        <span class="text-danger ms-1">{{ number_format($item->product->discount_price, 0, ',', '.') }}₫</span>
                                                    @else
                                                        {{ number_format($item->product->price, 0, ',', '.') }}₫
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ Carbon\Carbon::parse($item->last_purchased)->diffForHumans() }}</td>
                                    <td>{{ number_format($item->purchase_count) }}</td>
                                    <td>{{ number_format($item->total_quantity) }}</td>
                                    <td>
                                        <span class="badge bg-success">Đang bán</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/admin/dashboard-charts.js') }}"></script>
@endpush
@endsection