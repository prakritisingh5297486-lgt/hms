@extends('super-admin.layouts.main')

@section('content')

<div class="content-body">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold">Medicine Management</h4>
            <small class="text-muted">Manage all pharmacy medicines</small>
        </div>

        <a href="{{ route('super-admin.medicines.create') }}" class="btn btn-premium">
            <i class="bi bi-plus-circle"></i> Add Medicine
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="glass-card">

        <div class="table-responsive">

            <table class="custom-table">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Medicine</th>
                        <th>Category</th>
                        <th>Manufacturer</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Minimum</th>
                        <th>Expiry</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($medicines as $medicine)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $medicine->medicine_code }}</td>

                        <td>{{ $medicine->medicine_name }}</td>

                        <td>{{ $medicine->category }}</td>

                        <td>{{ $medicine->manufacturer }}</td>

                        <td>₹ {{ number_format($medicine->price,2) }}</td>

                        <td>

                            @if($medicine->stock <= $medicine->minimum_stock)

                                <span class="badge bg-danger">
                                    {{ $medicine->stock }}
                                </span>

                            @else

                                <span class="badge bg-success">
                                    {{ $medicine->stock }}
                                </span>

                            @endif

                        </td>

                        <td>{{ $medicine->minimum_stock }}</td>

                        <td>

                            {{ \Carbon\Carbon::parse($medicine->expiry_date)->format('d M Y') }}

                        </td>

                        <td>

                            <a href="{{ route('super-admin.medicines.edit',$medicine->id) }}"
                               class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('super-admin.medicines.destroy',$medicine->id) }}"
                                  method="POST"
                                  class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete this medicine?')">

                                    Delete

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="10" class="text-center py-5">

                            No Medicines Found

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">

            {{ $medicines->links() }}

        </div>

    </div>

</div>

@endsection