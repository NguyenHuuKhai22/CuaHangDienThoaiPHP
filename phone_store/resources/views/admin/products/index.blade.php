@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0">Quản lý sản phẩm</h2>
            <p class="mb-0 text-muted">Quản lý danh sách sản phẩm trong cửa hàng</p>
        </div>
        <div>
            <a href="{{ route('admin.products.trashed') }}" class="btn btn-outline-danger me-2">
                <i class="fas fa-trash"></i> Thùng rác
            </a>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm sản phẩm
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Ảnh</th>
                            <th class="px-4 py-3">Tên sản phẩm</th>
                            <th class="px-4 py-3">Danh mục</th>
                            <th class="px-4 py-3">Giá</th>
                            <th class="px-4 py-3">Kho</th>
                            <th class="px-4 py-3">Trạng thái</th>
                            <th class="px-4 py-3 text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td class="px-4">
                                <img src="{{ asset('images/products/' . $product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="img-thumbnail" 
                                     width="50">
                            </td>
                            <td class="px-4">{{ $product->name }}</td>
                            <td class="px-4">{{ $product->category->name }}</td>
                            <td class="px-4">
                                {{ $product->formatted_price }}
                                @if($product->discount_price)
                                    <br>
                                    <small class="text-danger">{{ $product->formatted_discount_price }}</small>
                                @endif
                            </td>
                            <td class="px-4">{{ $product->stock_quantity }}</td>
                            <td class="px-4">
                                <span class="badge bg-{{ $product->status ? 'success' : 'danger' }}">
                                    {{ $product->status ? 'Đang bán' : 'Ngừng bán' }}
                                </span>
                            </td>
                            <td class="px-4 text-end">
                                <div class="btn-group">
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger" 
                                                title="Xóa">
                                            <i class="fas fa-trash"></i>
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
                    Hiển thị {{ $products->firstItem() }} đến {{ $products->lastItem() }} 
                    trong tổng số {{ $products->total() }} sản phẩm
                </div>
                <div>
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection