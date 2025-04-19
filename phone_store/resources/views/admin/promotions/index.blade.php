@extends('layouts.admin')

@section('title', 'Quản lý khuyến mãi')

@section('content')
<style>
.badge-active { background-color: #00A65A; color: white; }
.badge-inactive { background-color: #DD4B39; color: white; }
.badge-upcoming { background-color: #0088cc; color: white; }
.badge-expired { background-color: #777777; color: white; }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Danh sách khuyến mãi</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm khuyến mãi mới
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên khuyến mãi</th>
                                    <th>Loại giảm giá</th>
                                    <th>Giá trị</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($promotions as $promotion)
                                <tr>
                                    <td>{{ $promotion->id }}</td>
                                    <td>{{ $promotion->name }}</td>
                                    <td>
                                        @if($promotion->discount_type == 'percentage')
                                            Phần trăm
                                        @else
                                            Số tiền cố định
                                        @endif
                                    </td>
                                    <td>
                                        @if($promotion->discount_type == 'percentage')
                                            {{ $promotion->discount_value }}%
                                        @else
                                            {{ number_format($promotion->discount_value) }}đ
                                        @endif
                                    </td>
                                    <td>{{ $promotion->start_date->format('d/m/Y H:i') }}</td>
                                    <td>{{ $promotion->end_date->format('d/m/Y H:i') }}</td>
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
                                    <td>
                                        <a href="{{ route('admin.promotions.show', $promotion) }}" 
                                           class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.promotions.edit', $promotion) }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.promotions.destroy', $promotion) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa khuyến mãi này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $promotions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.status-switch').change(function() {
        const promotionId = $(this).data('id');
        const checkbox = $(this);

        $.ajax({
            url: `/admin/promotions/${promotionId}/toggle-status`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Trạng thái khuyến mãi đã được cập nhật');
                    // Reload page to update status colors
                    location.reload();
                } else {
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    toastr.error('Có lỗi xảy ra khi cập nhật trạng thái');
                }
            },
            error: function() {
                checkbox.prop('checked', !checkbox.prop('checked'));
                toastr.error('Có lỗi xảy ra khi cập nhật trạng thái');
            }
        });
    });
});
</script>
@endpush
@endsection 