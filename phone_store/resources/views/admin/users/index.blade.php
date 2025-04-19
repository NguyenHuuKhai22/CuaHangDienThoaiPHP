@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center py-4">
        <div>
            <h2 class="h3 mb-0 text-gray-800">Quản lý người dùng</h2>
            <p class="mb-0 text-muted">Quản lý thông tin và trạng thái người dùng</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary d-flex align-items-center">
            <i class="fas fa-plus-circle me-2"></i>
            Thêm người dùng
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('status'))
        <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Main Content -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3" style="width: 60px">ID</th>
                            <th class="px-4 py-3">Tên</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Số điện thoại</th>
                            <th class="px-4 py-3">Địa chỉ</th>
                            <th class="px-4 py-3" style="width: 120px">Trạng thái</th>
                            <th class="px-4 py-3">Ngày tạo</th>
                            <th class="px-4 py-3 text-end" style="width: 150px">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="px-4">{{ $user->id }}</td>
                            <td class="px-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4">{{ $user->email }}</td>
                            <td class="px-4">{{ $user->phone }}</td>
                            <td class="px-4">{{ Str::limit($user->address, 30) }}</td>
                            <td class="px-4">
                                <span class="badge rounded-pill bg-{{ $user->is_blocked ? 'danger' : 'success' }} bg-opacity-10 text-{{ $user->is_blocked ? 'danger' : 'success' }} px-3 py-2">
                                    <i class="fas fa-{{ $user->is_blocked ? 'ban' : 'check-circle' }} me-1"></i>
                                    {{ $user->is_blocked ? 'Đã chặn' : 'Hoạt động' }}
                                </span>
                            </td>
                            <td class="px-4">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 text-end">
                                <div class="btn-group">
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="btn btn-light btn-sm" 
                                       data-bs-toggle="tooltip" 
                                       title="Chỉnh sửa">
                                        <i class="fas fa-edit text-primary"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.users.block', $user) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" 
                                                class="btn btn-light btn-sm"
                                                data-bs-toggle="tooltip"
                                                title="{{ $user->is_blocked ? 'Bỏ chặn' : 'Chặn' }}">
                                            <i class="fas {{ $user->is_blocked ? 'fa-unlock text-success' : 'fa-ban text-warning' }}"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.users.send-reset', $user) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-light btn-sm"
                                                data-bs-toggle="tooltip"
                                                title="Gửi link reset mật khẩu">
                                            <i class="fas fa-key text-secondary"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
                <div class="text-muted">
                    Hiển thị {{ $users->firstItem() }} đến {{ $users->lastItem() }} 
                    trong tổng số {{ $users->total() }} người dùng
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Custom styles */
    .avatar-circle {
        width: 40px;
        height: 40px;
        background-color: #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #495057;
    }

    .table > :not(caption) > * > * {
        padding: 1rem 0.5rem;
    }

    .btn-group .btn {
        padding: 0.375rem 0.75rem;
        border: none;
        transition: all 0.2s;
    }

    .btn-group .btn:hover {
        background-color: #e9ecef;
    }

    .badge {
        font-weight: 500;
    }

    /* Pagination styling */
    .pagination {
        margin-bottom: 0;
    }

    .page-link {
        padding: 0.375rem 0.75rem;
        border: none;
        color: #6c757d;
    }

    .page-link:hover {
        background-color: #e9ecef;
        color: #495057;
    }

    .page-item.active .page-link {
        background-color: #0d6efd;
        color: white;
    }

    /* Alert styling */
    .alert {
        border-radius: 0.5rem;
    }

    /* Card styling */
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }

    /* Button styling */
    .btn-primary {
        padding: 0.5rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
    }
</style>
@endpush

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush

@endsection