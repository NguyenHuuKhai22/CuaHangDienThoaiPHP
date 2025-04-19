@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0">Thùng rác</h2>
            <p class="mb-0 text-muted">Danh sách sản phẩm đã xóa tạm thời</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
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
                            <th class="px-4 py-3">Ngày xóa</th>
                            <th class="px-4 py-3 text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trashedProducts as $product)
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
                            <td class="px-4">{{ $product->deleted_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 text-end">
                                <div class="btn-group">
                                    <form action="{{ route('admin.products.restore', $product->id) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" 
                                                class="btn btn-sm btn-success" 
                                                title="Khôi phục">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.products.force-delete', $product->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa vĩnh viễn sản phẩm này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger" 
                                                title="Xóa vĩnh viễn">
                                            <i class="fas fa-times"></i>
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
                    Hiển thị {{ $trashedProducts->firstItem() }} đến {{ $trashedProducts->lastItem() }} 
                    trong tổng số {{ $trashedProducts->total() }} sản phẩm đã xóa
                </div>
                <div>
                    {{ $trashedProducts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection