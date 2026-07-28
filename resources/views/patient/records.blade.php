@extends('patient.layouts.main')
@section('content')
    <title>AuraHMS - Patient Medical Records</title>
            <!-- CONTENT BODY -->
            <div class="content-body">
                
                <!-- BREADCRUMB -->
                <nav>
                    <ul class="breadcrumb-custom">
                        <li class="breadcrumb-item-custom"><a href="/patient/dashboard">Home</a></li>
                        <li class="breadcrumb-item-custom">Medical Records</li>
                    </ul>
                </nav>

                <!-- SKELETON LOADER -->
                <div class="skeleton-wrapper row g-4 mb-4">
                    <div class="col-md-7"><div class="glass-card skeleton" style="height: 450px;"></div></div>
                    <div class="col-md-5"><div class="glass-card skeleton" style="height: 450px;"></div></div>
                </div>

                <!-- REAL CONTENT WRAPPER -->
                <div class="real-content-wrapper d-none">
                    
                    <div class="row g-4">
                        <!-- LEFT PANEL: MY MEDICAL FILE & TIMELINE -->
                        <div class="col-xl-7">
                            <div class="glass-card h-100">
                                <h5 class="fw-bold mb-4">Clinical Case File & Timeline</h5>
                                <div class="timeline-custom">

                                    @forelse($timeline as $item)

                                    <div class="timeline-item
                                    @if($item->status=='Completed')
                                    success
                                    @elseif($item->status=='Confirmed')
                                    info
                                    @else
                                    warning
                                    @endif">

                                        <div class="fw-semibold">
                                            {{ $item->consultation_type ?? 'Medical Consultation' }}
                                        </div>

                                        <small class="text-muted">
                                            Dr. {{ $item->doctor->user->name }}
                                        </small>

                                        <p class="text-muted small">
                                            {{ $item->symptoms }}
                                        </p>

                                        <span style="font-size:.75rem;color:var(--text-muted);">
                                            {{ \Carbon\Carbon::parse($item->appointment_date)->format('d M Y h:i A') }}
                                        </span>

                                    </div>

                                    @empty

                                    <div class="text-center py-5">

                                        <i class="bi bi-clock-history fs-2"></i>

                                        <p class="mt-2 mb-0">
                                            No Medical Timeline Found
                                        </p>

                                    </div>

                                    @endforelse

                                    </div>
                            </div>
                        </div>

                        <!-- RIGHT PANEL: ACTIVE PRESCRIPTIONS & DOWNLOADABLE REPORTS -->
                        <div class="col-xl-5">
                            <!-- PRESCRIPTIONS LOG -->
                            <div class="glass-card mb-4">
                                <h5 class="fw-bold mb-3">My Current Prescriptions</h5>
                                <div class="d-flex flex-column gap-3">

                                    @forelse($prescriptions as $prescription)

                                    <div class="p-3 border border-light border-opacity-10 rounded-4 glass-sub-card">

                                        <div class="d-flex justify-content-between">

                                            <div>

                                                <h6 class="fw-bold mb-1">

                                                    {{ $prescription->medicine_name }}

                                                </h6>

                                                <small class="text-muted">

                                                    Dosage :
                                                    {{ $prescription->dosage }}

                                                </small>

                                            </div>

                                            <span class="badge bg-success bg-opacity-10 text-success">

                                                Active

                                            </span>

                                        </div>

                                    </div>

                                    @empty

                                    <div class="text-center py-5">

                                        <i class="bi bi-capsule fs-2"></i>

                                        <p class="mt-2 mb-0">

                                            No Active Prescription

                                        </p>

                                    </div>

                                    @endforelse

                                </div>
                            </div>

                            <!-- MOCK DOCUMENTS -->
                            <div class="glass-card">
                                <h5 class="fw-bold mb-3">My Lab Test Documents</h5>
                                <div class="d-flex flex-column gap-2">
                                    <div class="p-2 border border-light border-opacity-10 rounded-3 d-flex justify-content-between align-items-center glass-sub-card">
                                        <span style="font-size: 0.85rem;"><i class="bi bi-file-earmark-pdf-fill text-danger me-2"></i>Blood_Count_Report.pdf</span>
                                        <button class="btn btn-sm text-info p-0" onclick="showToast('Downloading', 'Blood_Count_Report.pdf downloaded.', 'success')"><i class="bi bi-download"></i></button>
                                    </div>
                                    <div class="p-2 border border-light border-opacity-10 rounded-3 d-flex justify-content-between align-items-center glass-sub-card">
                                        <span style="font-size: 0.85rem;"><i class="bi bi-file-earmark-pdf-fill text-danger me-2"></i>Chest_XRay_Scan.pdf</span>
                                        <button class="btn btn-sm text-info p-0" onclick="showToast('Downloading', 'Chest_XRay_Scan.pdf downloaded.', 'success')"><i class="bi bi-download"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
@endsection