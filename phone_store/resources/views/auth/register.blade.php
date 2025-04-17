@extends('layouts.app')

@section('title', 'Đăng ký - KAIRA Phone Store')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-serif">Tạo Tài Khoản</h1>
                        <p class="text-gray-600 mt-2">Tham gia cùng chúng tôi ngay hôm nay</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label text-gray-600">Họ và Tên</label>
                            <input type="text" class="form-control form-control-lg bg-light @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name') }}" required autofocus
                                placeholder="Nhập họ và tên của bạn">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label text-gray-600">Địa chỉ Email</label>
                            <input type="email" class="form-control form-control-lg bg-light @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}" required
                                placeholder="Nhập địa chỉ email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label text-gray-600">Số Điện Thoại</label>
                            <input type="tel" class="form-control form-control-lg bg-light @error('phone') is-invalid @enderror"
                                id="phone" name="phone" value="{{ old('phone') }}"
                                placeholder="Nhập số điện thoại">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label text-gray-600">Địa Chỉ</label>
                            <textarea class="form-control form-control-lg bg-light @error('address') is-invalid @enderror"
                                id="address" name="address" rows="2"
                                placeholder="Nhập địa chỉ của bạn">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label text-gray-600">Mật Khẩu</label>
                            <input type="password" class="form-control form-control-lg bg-light @error('password') is-invalid @enderror"
                                id="password" name="password" required
                                placeholder="Tạo mật khẩu">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label text-gray-600">Xác Nhận Mật Khẩu</label>
                            <input type="password" class="form-control form-control-lg bg-light"
                                id="password_confirmation" name="password_confirmation" required
                                placeholder="Nhập lại mật khẩu">
                        </div>

                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-dark btn-lg">
                                Đăng Ký
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            <p class="text-muted mb-0">
                                Bạn đã có tài khoản? 
                                <a href="{{ route('login') }}" class="text-dark text-decoration-none">
                                    Đăng nhập
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 