@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0">Thêm sản phẩm mới</h2>
            <p class="mb-0 text-muted">Thêm sản phẩm mới vào cửa hàng</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <!-- Thông tin cơ bản -->
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="5" 
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('price') is-invalid @enderror" 
                                               id="price" 
                                               name="price" 
                                               value="{{ old('price') }}" 
                                               required>
                                        <span class="input-group-text">VNĐ</span>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discount_price" class="form-label">Giá khuyến mãi</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('discount_price') is-invalid @enderror" 
                                               id="discount_price" 
                                               name="discount_price" 
                                               value="{{ old('discount_price') }}">
                                        <span class="input-group-text">VNĐ</span>
                                        @error('discount_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" 
                                            id="category_id" 
                                            name="category_id" 
                                            required>
                                        <option value="">Chọn danh mục</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock_quantity" class="form-label">Số lượng trong kho <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('stock_quantity') is-invalid @enderror" 
                                           id="stock_quantity" 
                                           name="stock_quantity" 
                                           value="{{ old('stock_quantity') }}" 
                                           required>
                                    @error('stock_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin chi tiết và ảnh -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="image" class="form-label">Ảnh sản phẩm <span class="text-danger">*</span></label>
                            <input type="file" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   id="image" 
                                   name="image" 
                                   accept="image/*" 
                                   required>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="imagePreview" class="mt-2"></div>
                        </div>

                        <div class="mb-3">
                            <label for="color" class="form-label">Màu sắc <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('color') is-invalid @enderror" 
                                   id="color" 
                                   name="color" 
                                   value="{{ old('color') }}" 
                                   required>
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="storage" class="form-label">Bộ nhớ trong <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('storage') is-invalid @enderror" 
                                   id="storage" 
                                   name="storage" 
                                   value="{{ old('storage') }}" 
                                   required>
                            @error('storage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="ram" class="form-label">RAM <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('ram') is-invalid @enderror" 
                                   id="ram" 
                                   name="ram" 
                                   value="{{ old('ram') }}" 
                                   required>
                            @error('ram')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="screen_size" class="form-label">Kích thước màn hình <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('screen_size') is-invalid @enderror" 
                                   id="screen_size" 
                                   name="screen_size" 
                                   value="{{ old('screen_size') }}" 
                                   required>
                            @error('screen_size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="battery_capacity" class="form-label">Dung lượng pin <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('battery_capacity') is-invalid @enderror" 
                                   id="battery_capacity" 
                                   name="battery_capacity" 
                                   value="{{ old('battery_capacity') }}" 
                                   required>
                            @error('battery_capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="operating_system" class="form-label">Hệ điều hành <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('operating_system') is-invalid @enderror" 
                                   id="operating_system" 
                                   name="operating_system" 
                                   value="{{ old('operating_system') }}" 
                                   required>
                            @error('operating_system')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="is_featured" 
                                       name="is_featured" 
                                       value="1" 
                                       {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    Sản phẩm nổi bật
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="status" 
                                       name="status" 
                                       value="1" 
                                       {{ old('status', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">
                                    Hiển thị sản phẩm
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Lưu sản phẩm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Preview ảnh trước khi upload
document.getElementById('image').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add('img-thumbnail', 'mt-2');
            img.style.maxHeight = '200px';
            preview.appendChild(img);
        }
        
        reader.readAsDataURL(this.files[0]);
    }
});

// Kiểm tra giá khuyến mãi không được lớn hơn giá gốc
document.getElementById('discount_price').addEventListener('input', function() {
    const price = parseFloat(document.getElementById('price').value);
    const discountPrice = parseFloat(this.value);
    
    if (discountPrice >= price) {
        this.setCustomValidity('Giá khuyến mãi phải nhỏ hơn giá gốc');
    } else {
        this.setCustomValidity('');
    }
});
</script>
@endpush

@endsection