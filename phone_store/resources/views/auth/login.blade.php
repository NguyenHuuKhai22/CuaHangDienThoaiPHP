@extends('layouts.app')

@section('title', 'Đăng nhập - KAIRA Phone Store')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-serif">Chào mừng trở lại</h1>
                        <p class="text-gray-600 mt-2">Đăng nhập vào tài khoản của bạn</p>
                    </div>

                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            {{ Session::get('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="email" class="form-label text-gray-600">Địa chỉ Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="form-control form-control-lg bg-light @error('email') is-invalid @enderror"
                                placeholder="Nhập địa chỉ email của bạn">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label text-gray-600">Mật khẩu</label>
                            <input type="password" id="password" name="password" required
                                class="form-control form-control-lg bg-light @error('password') is-invalid @enderror"
                                placeholder="Nhập mật khẩu của bạn">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-dark btn-lg">
                                Đăng nhập
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('password.request') }}" class="text-decoration-none text-muted">
                                Quên mật khẩu?
                            </a>
                        </div>

                        <div class="text-center mt-4">
                            <p class="text-muted mb-0">
                                Bạn chưa có tài khoản? 
                                <a href="{{ route('register') }}" class="text-dark text-decoration-none">
                                    Đăng ký ngay
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