@extends('layouts.admin')

@section('title', 'Danh mục đã xóa')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title">Danh mục đã xóa</h1>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hình ảnh</th>
                    <th>Tên danh mục</th>
                    <th>Slug</th>
                    <th>Ngày xóa</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>
                            @if($category->image)
                            <img src="{{ asset('images/categories/' .$category->image) }}" 
                                     alt="{{ $category->name }}" 
                                     class="img-thumbnail" 
                                     style="max-width: 100px;">
                            @else
                                <span class="text-muted">Không có ảnh</span>
                            @endif
                        </td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>{{ $category->deleted_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="btn-group">
                                <form action="{{ route('admin.categories.restore', $category->id) }}" 
                                      method="POST" 
                                      class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Khôi phục">
                                        <i class="fas fa-trash-restore"></i>
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.categories.force-delete', $category->id) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn danh mục này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Xóa vĩnh viễn">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Không có danh mục nào trong thùng rác.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $categories->links() }}
    </div>
</div>
@endsection 