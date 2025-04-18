@extends('layouts.app')

@section('title', $title ?? 'Hồ sơ của tôi - HK')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-person-circle fs-1 me-3"></i>
                        <div>
                            <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                            <p class="text-muted mb-0 text-truncate" style="max-width: 200px;" title="{{ Auth::user()->email }}">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <hr>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}">
                                <i class="bi bi-person me-2"></i> Thông tin cá nhân
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                                <i class="bi bi-bag me-2"></i> Đơn hàng của tôi
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('profile_content')
        </div>
    </div>
</div>
@endsection 