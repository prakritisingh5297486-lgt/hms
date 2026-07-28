@extends('super-admin.layouts.main')
    @section('content')
    
    <style>
        .drag-drop-zone {
            border: 2px dashed var(--border-color);
            background: rgba(255, 255, 255, 0.01);
            border-radius: 16px;
            padding: 3rem 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all var(--transition-speed);
        }
        .drag-drop-zone:hover {
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.05);
        }
    </style>

            <!-- CONTENT BODY -->
            <div class="content-body">
                
                <!-- BREADCRUMB & HEADER -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <nav>
                            <ul class="breadcrumb-custom mb-1">
                                <li class="breadcrumb-item-custom"><a href="/super-admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item-custom">Laboratory</li>
                            </ul>
                        </nav>
                        <h4 class="fw-bold mb-0">Laboratory & Diagnostic Centre</h4>
                    </div>
                </div>

                <!-- SKELETON LOADER -->
                <div class="skeleton-wrapper row g-4 mb-4">
                    <div class="col-md-7"><div class="glass-card skeleton" style="height: 450px;"></div></div>
                    <div class="col-md-5"><div class="glass-card skeleton" style="height: 450px;"></div></div>
                </div>

                <!-- REAL CONTENT WRAPPER -->
                <div class="real-content-wrapper d-none">
                    
                    <div class="row g-4">
                        <!-- LEFT COLUMN: LAB REPORTS LIST -->
                        <div class="col-xl-7">
                            <div class="glass-card h-100">
                                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                                    <h5 class="mb-0">Recent Diagnostic Reports</h5>
                                    <input type="text" class="form-control form-glass py-1 px-3" style="max-width: 200px;" placeholder="Filter by Patient ID...">
                                </div>

                                <div class="custom-table-container">
                                    <table class="custom-table">
                                        <thead>
                                            <tr>
                                                <th>Patient Name</th>
                                                <th>Test Category</th>
                                                <th>Lab Status</th>
                                                <th>Reference Range</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @forelse($reports as $report)

                                            <tr>

                                                <td>

                                                    <div class="fw-semibold">

                                                        {{ $report->patient->user->name }}

                                                    </div>

                                                    <small class="text-muted">

                                                        #PT-{{ $report->patient->id }}

                                                    </small>

                                                </td>

                                                <td>

                                                    {{ $report->report_type }}

                                                </td>

                                                <td>

                                                    @if($report->status=="Completed")

                                                        <span class="custom-badge badge-success">

                                                            Completed

                                                        </span>

                                                    @else

                                                        <span class="custom-badge badge-warning">

                                                            Pending

                                                        </span>

                                                    @endif

                                                </td>

                                                <td>

                                                    {{ $report->report_name }}

                                                </td>

                                                <td class="text-end">

                                                    <div class="d-flex justify-content-end gap-2">

                                                        <button
                                                        class="btn btn-premium btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewReport{{ $report->id }}">

                                                        <i class="bi bi-eye"></i>

                                                        </button>

                                                        <button
                                                        class="btn btn-warning btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editReport{{ $report->id }}">

                                                        <i class="bi bi-pencil"></i>

                                                        </button>

                                                        <button
                                                        class="btn btn-danger btn-sm"
                                                        onclick="deleteReport({{ $report->id }})">

                                                        <i class="bi bi-trash"></i>

                                                        </button>

                                                        </div>

                                                </td>

                                            </tr>

                                            @empty

                                            <tr>

                                            <td colspan="5" class="text-center">

                                            No Reports Found

                                            </td>

                                            </tr>
                                            @endforelse

                                            </tbody>
                                    </table>
                                    @foreach($reports as $report)

                                    <form
                                        id="delete-report-{{ $report->id }}"
                                        action="{{ route('super-admin.laboratory.destroy',$report) }}"
                                        method="POST"
                                        style="display:none">

                                        @csrf
                                        @method('DELETE')

                                    </form>

                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT COLUMN: CONDUCT NEW TEST & UPLOAD -->
                        <div class="col-xl-5">
                            <div class="glass-card h-100">
                                <h5 class="fw-bold mb-4">Upload Laboratory Diagnostic File</h5>
                                <form action="{{ route('super-admin.laboratory.store') }}" method="POST" enctype="multipart/form-data">

                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label-custom">Select Patient</label>

                                        <select name="patient_id"
                                                class="form-select form-glass"
                                                required>

                                            <option value="">Choose Patient</option>

                                            @foreach($patients as $patient)

                                                <option value="{{ $patient->id }}">
                                                    {{ $patient->user->name }}
                                                </option>

                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label-custom">Doctor</label>

                                        <select name="doctor_id"
                                                class="form-select form-glass">

                                            <option value="">Select Doctor</option>

                                            @foreach($doctors as $doctor)

                                                <option value="{{ $doctor->id }}">
                                                    {{ $doctor->user->name }}
                                                </option>

                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="mb-3">

                                        <label class="form-label-custom">

                                            Report Name

                                        </label>

                                        <input type="text"
                                            name="report_name"
                                            class="form-control form-glass"
                                            placeholder="CBC Report"
                                            required>

                                    </div>
                                    <div class="mb-3">

                                        <label class="form-label-custom">

                                            Report Type

                                        </label>

                                        <select name="report_type"
                                                class="form-select form-glass"
                                                required>

                                            <option value="">Select</option>

                                            <option>CBC</option>

                                            <option>LFT</option>

                                            <option>KFT</option>

                                            <option>X-Ray</option>

                                            <option>MRI</option>

                                            <option>CT Scan</option>

                                            <option>Urine Test</option>

                                            <option>Blood Sugar</option>

                                        </select>

                                    </div>
                                    <div class="mb-3">

                                        <label class="form-label-custom">

                                            Status

                                        </label>

                                        <select name="status"
                                                class="form-select form-glass">

                                            <option value="Pending">

                                                Pending

                                            </option>

                                            <option value="Completed">

                                                Completed

                                            </option>

                                        </select>

                                    </div>
                                    <div class="mb-4">

                                        <label class="form-label-custom">

                                            Upload PDF

                                        </label>

                                        <input type="file"
                                            name="file"
                                            class="form-control form-glass"
                                            accept=".pdf"
                                            required>

                                    </div>
                                    {{-- <div class="mb-3">
                                        <label class="form-label-custom">Test Category Code</label>
                                        <select class="form-select form-glass" required>
                                            <option value="cbc">Complete Blood Count (CBC) - $120</option>
                                            <option value="lipid">Lipid Profile Diagnostics - $200</option>
                                            <option value="mri">Brain MRI Contrast Scan - $850</option>
                                        </select>
                                    </div> --}}
                                    {{-- <div class="mb-4">
                                        <label class="form-label-custom">Select Diagnostic Report PDF</label>
                                        <div class="drag-drop-zone" id="file-dropzone">
                                            <i class="bi bi-file-earmark-arrow-up text-primary" style="font-size: 2.5rem;"></i>
                                            <h6 class="fw-semibold mt-2">Drag Diagnostic PDF here</h6>
                                            <p class="text-muted small mb-0">Max allowed file size 5MB. Standard reports only.</p>
                                        </div>
                                    </div> --}}
                                    <button class="btn btn-premium w-100">

                                        Upload Report

                                    </button>

                                    {{-- <button type="submit" class="btn btn-premium w-100">Link File & Notify Doctor</button> --}}
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

    <!-- MODAL: LAB REPORT DETAILS PREVIEW -->
    @foreach($reports as $report)

    <div class="modal fade" id="viewReport{{ $report->id }}" tabindex="-1">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content glass-card">

                <div class="modal-header">

                    <h5 class="fw-bold">

                        Laboratory Report

                    </h5>

                    <button
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="text-muted small">

                                Patient

                            </label>

                            <div class="fw-semibold">

                                {{ $report->patient->user->name }}

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="text-muted small">

                                Doctor

                            </label>

                            <div class="fw-semibold">

                                {{ optional($report->doctor?->user)->name ?? '-' }}

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="text-muted small">

                                Report Name

                            </label>

                            <div>

                                {{ $report->report_name }}

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="text-muted small">

                                Test Type

                            </label>

                            <div>

                                {{ $report->report_type }}

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="text-muted small">

                                Status

                            </label>

                            <div>

                                @if($report->status=="Completed")

                                    <span class="badge bg-success">

                                        Completed

                                    </span>

                                @else

                                    <span class="badge bg-warning">

                                        Pending

                                    </span>

                                @endif

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="text-muted small">

                                Uploaded

                            </label>

                            <div>

                                {{ $report->created_at->format('d M Y') }}

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <a href="{{ asset('uploads/lab_reports/'.$report->file) }}"
                    target="_blank"
                    class="btn btn-premium">

                        <i class="bi bi-eye"></i>

                        View PDF

                    </a>

                    <a href="{{ asset('uploads/lab_reports/'.$report->file) }}"
                    download
                    class="btn btn-premium-outline">

                        <i class="bi bi-download"></i>

                        Download

                    </a>

                </div>

            </div>

        </div>

    </div>
    <div class="modal fade" id="editReport{{ $report->id }}" tabindex="-1">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content glass-card">
                <form action="{{ route('super-admin.laboratory.update',$report) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="modal-header">

                    <h5 class="fw-bold">

                        Edit Laboratory Report

                    </h5>

                    <button
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="text-muted small">

                                Patient

                            </label>

                             <select name="patient_id"
                                    class="form-select form-glass">

                                @foreach($patients as $patient)

                                    <option value="{{ $patient->id }}"
                                        {{ $report->patient_id==$patient->id ? 'selected':'' }}>

                                        {{ $patient->user->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label class="text-muted small">

                                Doctor

                            </label>

                            <select name="doctor_id"
                                    class="form-select form-glass">

                                <option value="">None</option>

                                @foreach($doctors as $doctor)

                                    <option value="{{ $doctor->id }}"
                                        {{ $report->doctor_id==$doctor->id ? 'selected':'' }}>

                                        {{ $doctor->user->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label class="text-muted small">

                                Report Name

                            </label>

                            <div>

                                <input type="text" name="report_name" value="{{ $report->report_name }}" class="form-control form-glass">

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label-custom">

                                Report Type

                            </label>

                            <input
                                type="text" name="report_type" value="{{ $report->report_type }}" class="form-control form-glass">

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted small">

                                Status

                            </label>

                             <select
                                name="status"
                                class="form-select form-glass">

                                <option value="Pending"
                                    {{ $report->status=='Pending'?'selected':'' }}>

                                    Pending

                                </option>

                                <option value="Completed"
                                    {{ $report->status=='Completed'?'selected':'' }}>

                                    Completed

                                </option>

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label-custom">

                                Replace PDF

                            </label>

                            <input
                                type="file"
                                name="file"
                                class="form-control form-glass"
                                accept=".pdf">

                            <small class="text-muted">

                                Leave blank to keep current PDF

                            </small>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                     <button
                        type="button"
                        class="btn btn-premium-outline"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button
                        type="submit"
                        class="btn btn-premium">

                        <i class="bi bi-check-circle"></i>

                        Update Report

                    </button>

                </div>

                </form>

            </div>

        </div>

    </div>

    @endforeach

   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
            // for sweet alert

        function deleteReport(id){

        Swal.fire({

        title:'Delete Report?',

        text:'This report will be permanently deleted.',

        icon:'warning',

        showCancelButton:true,

        confirmButtonText:'Delete'

        }).then((result)=>{

        if(result.isConfirmed){

        document.getElementById('delete-report-'+id).submit();

        }

        });

        }
    </script>


@endsection