@extends('layouts.admin')

@section('title', isset($promotion) ? 'Chỉnh sửa khuyến mãi' : 'Thêm khuyến mãi mới')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ isset($promotion) ? 'Chỉnh sửa khuyến mãi' : 'Thêm khuyến mãi mới' }}</h3>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ isset($promotion) ? route('admin.promotions.update', $promotion) : route('admin.promotions.store') }}" 
                          method="POST">
                        @csrf
                        @if(isset($promotion))
                            @method('PUT')
                        @endif

                        <div class="form-group">
                            <label for="name">Tên khuyến mãi <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $promotion->name ?? '') }}" 
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3">{{ old('description', $promotion->description ?? '') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="discount_type">Loại giảm giá <span class="text-danger">*</span></label>
                            <select class="form-control @error('discount_type') is-invalid @enderror" 
                                    id="discount_type" 
                                    name="discount_type" 
                                    required>
                                <option value="percentage" {{ (old('discount_type', $promotion->discount_type ?? '') == 'percentage') ? 'selected' : '' }}>
                                    Phần trăm
                                </option>
                                <option value="fixed" {{ (old('discount_type', $promotion->discount_type ?? '') == 'fixed') ? 'selected' : '' }}>
                                    Số tiền cố định
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="discount_value">Giá trị giảm giá <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" 
                                       class="form-control @error('discount_value') is-invalid @enderror" 
                                       id="discount_value" 
                                       name="discount_value" 
                                       value="{{ old('discount_value', $promotion->discount_value ?? '') }}" 
                                       min="0" 
                                       step="0.01" 
                                       required>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="discount-suffix">%</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Ngày bắt đầu <span class="text-danger">*</span></label>
                                    <input type="datetime-local" 
                                           class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" 
                                           name="start_date" 
                                           value="{{ old('start_date', isset($promotion) ? $promotion->start_date->format('Y-m-d\TH:i') : '') }}" 
                                           required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">Ngày kết thúc <span class="text-danger">*</span></label>
                                    <input type="datetime-local" 
                                           class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" 
                                           name="end_date" 
                                           value="{{ old('end_date', isset($promotion) ? $promotion->end_date->format('Y-m-d\TH:i') : '') }}" 
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="products">Sản phẩm áp dụng</label>
                            <select class="form-control select2 @error('products') is-invalid @enderror" 
                                    id="products" 
                                    name="products[]" 
                                    multiple>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            {{ (isset($promotion) && $promotion->products->contains($product->id)) ? 'selected' : '' }}>
                                        {{ $product->name }} -
                                        {{ $product->color }} -
                                        {{ $product->ram }} - 
                                        {{ $product->storage }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Để trống nếu muốn áp dụng cho tất cả sản phẩm</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $promotion->is_active ?? true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Kích hoạt khuyến mãi</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                {{ isset($promotion) ? 'Cập nhật' : 'Thêm mới' }}
                            </button>
                            <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        placeholder: 'Chọn sản phẩm áp dụng',
        allowClear: true
    });

    // Update discount suffix based on discount type
    $('#discount_type').change(function() {
        const suffix = $(this).val() === 'percentage' ? '%' : 'đ';
        $('#discount-suffix').text(suffix);
    });

    // Trigger change on load to set initial suffix
    $('#discount_type').trigger('change');
});
</script>
@endpush
@endsection 