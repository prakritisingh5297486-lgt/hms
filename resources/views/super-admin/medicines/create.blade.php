@extends('super-admin.layouts.main')

@section('content')

<div class="content-body">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold">Add New Medicine</h4>
            <small class="text-muted">Create a new medicine for pharmacy inventory</small>
        </div>

        <a href="{{ route('super-admin.medicines.index') }}" class="btn btn-premium-outline">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="glass-card">

        <form action="{{ route('super-admin.medicines.store') }}" method="POST">

            @csrf

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label">Medicine Name</label>
                    <input type="text"
                           name="medicine_name"
                           class="form-control form-glass @error('medicine_name') is-invalid @enderror"
                           value="{{ old('medicine_name') }}">

                    @error('medicine_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Medicine Code</label>
                    <input type="text"
                           name="medicine_code"
                           class="form-control form-glass @error('medicine_code') is-invalid @enderror"
                           value="{{ old('medicine_code') }}">

                    @error('medicine_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Category</label>
                    <input type="text"
                           name="category"
                           class="form-control form-glass @error('category') is-invalid @enderror"
                           value="{{ old('category') }}">

                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Manufacturer</label>
                    <input type="text"
                           name="manufacturer"
                           class="form-control form-glass"
                           value="{{ old('manufacturer') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Price (₹)</label>
                    <input type="number"
                           step="0.01"
                           name="price"
                           class="form-control form-glass @error('price') is-invalid @enderror"
                           value="{{ old('price') }}">

                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Current Stock</label>
                    <input type="number"
                           name="stock"
                           class="form-control form-glass @error('stock') is-invalid @enderror"
                           value="{{ old('stock') }}">

                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Minimum Stock</label>
                    <input type="number"
                           name="minimum_stock"
                           class="form-control form-glass @error('minimum_stock') is-invalid @enderror"
                           value="{{ old('minimum_stock',10) }}">

                    @error('minimum_stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Expiry Date</label>
                    <input type="date"
                           name="expiry_date"
                           class="form-control form-glass @error('expiry_date') is-invalid @enderror"
                           value="{{ old('expiry_date') }}">

                    @error('expiry_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="mt-4">

                <button class="btn btn-premium">
                    <i class="bi bi-check-circle"></i> Save Medicine
                </button>

                <a href="{{ route('super-admin.medicines.index') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection