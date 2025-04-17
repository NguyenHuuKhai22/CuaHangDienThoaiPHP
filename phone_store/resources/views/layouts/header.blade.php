<header>
    <nav class="navbar navbar-expand-lg bg-white">
        <div class="container py-3">
            <a class="navbar-brand" href="/">HK STORE</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/">TRANG CHỦ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/shop">CỬA HÀNG</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/blog">TIN TỨC</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/about">GIỚI THIỆU</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">LIÊN HỆ</a>
                    </li>
                </ul>
            </div>
            
            <div class="d-flex align-items-center gap-4">
                @guest
                    <a href="{{ route('login') }}" class="text-dark text-decoration-none">
                        <i class="bi bi-person"></i>
                        <span class="ms-2 d-none d-lg-inline">Đăng nhập</span>
                    </a>
                @else
                    <div class="dropdown">
                        <a href="#" class="text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                            <span class="ms-2 d-none d-lg-inline">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/profile">Hồ sơ của tôi</a></li>
                            <li><a class="dropdown-item" href="/orders">Đơn hàng của tôi</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Đăng xuất</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest

                <a href="{{ route('wishlist') }}" class="text-dark text-decoration-none position-relative">
                    <i class="bi bi-heart"></i>
                    <span class="ms-2 d-none d-lg-inline">Yêu thích</span>
                    @auth
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ Auth::user()->wishlist?->items_count ?? 0 }}
                        </span>
                    @endauth
                </a>

                <a href="{{ route('cart') }}" class="text-dark text-decoration-none position-relative">
                    <i class="bi bi-cart3"></i>
                    <span class="ms-2 d-none d-lg-inline">Giỏ hàng</span>
                    @auth
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ Auth::user()->cart?->items_count ?? 0 }}
                        </span>
                    @endauth
                </a>

                <button class="btn btn-link text-dark p-0 d-none d-lg-block">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
    </nav>
</header> 