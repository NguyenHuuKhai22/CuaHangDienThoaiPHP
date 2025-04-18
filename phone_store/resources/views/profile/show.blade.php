@extends('layouts.profile')

@section('title', 'Thông tin cá nhân - HK')

@push('styles')
<style>

</style>
@endpush

@section('profile_content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="mb-0">Thông tin cá nhân</h4>
            <span class="badge bg-primary">Người Dùng</span>
        </div>

       
        
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">
                        <i class="bi bi-person me-2"></i>Họ và tên
                    </label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope me-2"></i>Email
                    </label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">
                        <i class="bi bi-telephone me-2"></i>Số điện thoại
                    </label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                           id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="address" class="form-label">
                        <i class="bi bi-geo-alt me-2"></i>Địa chỉ
                    </label>
                    <textarea class="form-control @error('address') is-invalid @enderror" 
                              id="address" name="address" rows="1">{{ old('address', Auth::user()->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-check2 me-2"></i>Cập nhật thông tin
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="mb-0">Đổi mật khẩu</h4>
            <i class="bi bi-shield-lock fs-4"></i>
        </div>
        
        <form action="{{ route('profile.password') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="current_password" class="form-label">
                        <i class="bi bi-key me-2"></i>Mật khẩu hiện tại
                    </label>
                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                           id="current_password" name="current_password" required>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock me-2"></i>Mật khẩu mới
                    </label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="password_confirmation" class="form-label">
                        <i class="bi bi-check-circle me-2"></i>Xác nhận mật khẩu mới
                    </label>
                    <input type="password" class="form-control" 
                           id="password_confirmation" name="password_confirmation" required>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-shield-check me-2"></i>Đổi mật khẩu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection