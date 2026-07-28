@extends('patient.layouts.main')
@section('content')
    <title>AuraHMS - Patient Portal Panel</title>
    <style>
        .patient-badge {
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.15) 0%, rgba(99, 102, 241, 0.15) 100%);
            border: 1px solid var(--border-color);
        }
    </style>

            <!-- CONTENT BODY -->
            <div class="content-body">
                
                <!-- BREADCRUMB -->
                <nav>
                    <ul class="breadcrumb-custom">
                        <li class="breadcrumb-item-custom"><a href="#">Home</a></li>
                        <li class="breadcrumb-item-custom">Patient Portal</li>
                    </ul>
                </nav>

                <!-- SKELETON LOADER -->
                <div class="skeleton-wrapper row g-4 mb-4">
                    <div class="col-md-3"><div class="glass-card skeleton" style="height: 120px;"></div></div>
                    <div class="col-md-3"><div class="glass-card skeleton" style="height: 120px;"></div></div>
                    <div class="col-md-3"><div class="glass-card skeleton" style="height: 120px;"></div></div>
                    <div class="col-md-3"><div class="glass-card skeleton" style="height: 120px;"></div></div>
                </div>

                <!-- REAL CONTENT WRAPPER -->
                <div class="real-content-wrapper d-none">
                    
                    <!-- PATIENT SUMMARY INFO -->
                    <div class="row g-4 mb-4">
                        <div class="col-xl-3 col-md-6">
                            <div class="glass-card patient-badge">
                                <span class="text-muted fw-semibold small">NEXT CLINIC APPOINTMENT</span>
                                @if($nextAppointment)
                                <h5 class="fw-bold mt-2 mb-1 text-primary">Dr. {{ $nextAppointment->doctor->user->name }}</h5>
                                <div class="small text-white text-opacity-80"><i class="bi bi-calendar3"></i> {{ $nextAppointment->appointment_date }}</div>
                                @else
                                <h5 class="fw-bold mt-2 mb-1 text-danger">No Appointment</h5>
                                <div class="small text-muted">Book your next consultation</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="glass-card patient-badge">
                                <span class="text-muted fw-semibold small">PENDING PAYMENTS</span>
                                <h5 class="fw-bold mt-2 mb-1 text-warning">₹{{ number_format($pendingPayment,2) }}</h5>
                                @if($pendingPayment>0)
                                <div class="small text-warning"><i class="bi bi-exclamation-circle"></i>Payment Pending</div>
                                @else
                                <div class="small text-success"><i class="bi bi-check-circle-fill"></i> No outstanding invoices</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="glass-card patient-badge">
                                <span class="text-muted fw-semibold small">LAB DIAGNOSTICS</span>
                                <h5 class="fw-bold mt-2 mb-1 text-success">{{ $readyReports }} Ready</h5></h5>
                                <div class="small text-white text-opacity-80"><i class="bi bi-file-earmark-check"></i> Completed Reports Available</div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="glass-card patient-badge">
                                <span class="text-muted fw-semibold small">ACTIVE PRESCRIPTIONS</span>
                                <h5 class="fw-bold mt-2 mb-1 text-info">{{ $activePrescriptions }} Prescription(s)</h5>
                                <div class="small text-white text-opacity-80"><i class="bi bi-prescription"></i> Updated Automatically</div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- LEFT PANEL: MY MEDICAL FILE & TIMELINE -->
                        <div class="col-lg-8">
                            <!-- UPCOMING SHIFT & BOOKING LINK -->
                            <div class="glass-card mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold mb-0">Active Treatment Timeline</h5>
                                    <button class="btn btn-premium btn-sm" onclick="showToast('Appointment Scheduler', 'Redirecting to booking calendar.', 'info')">
                                        <i class="bi bi-calendar-plus"></i> Request Appointment
                                    </button>
                                </div>
                               <div class="timeline-custom pt-2">

                                    @forelse($appointments as $appointment)

                                    <div class="timeline-item
                                        @if($appointment->status=='Completed')
                                            success
                                        @elseif($appointment->status=='Confirmed')
                                            info
                                        @else
                                            warning
                                        @endif">

                                        <div class="fw-semibold">

                                            {{ $appointment->consultation_type ?? 'Medical Consultation' }}

                                        </div>

                                        <small class="text-muted">

                                            Dr.
                                            {{ $appointment->doctor->user->name }}

                                        </small>

                                        <span class="d-block small text-white text-opacity-70 mt-1">

                                            {{ $appointment->symptoms }}

                                        </span>

                                        <span
                                            style="font-size:.75rem;color:var(--text-muted)">

                                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y h:i A') }}

                                        </span>

                                    </div>

                                    @empty

                                    <div class="text-center py-5">

                                        <i class="bi bi-calendar-x fs-1"></i>

                                        <p class="mt-3">

                                            No Treatment Timeline Available

                                        </p>

                                    </div>

                                    @endforelse

                                    </div>
                            </div>

                            <!-- ACTIVE PRESCRIPTIONS TABLE -->
                            <div class="glass-card">
                                <h5 class="fw-bold mb-4">Active Pharmaceutical Prescriptions</h5>
                                <div class="custom-table-container">
                                    <table class="custom-table">
                                        <thead>
                                            <tr>
                                                <th>Medicine Name</th>
                                                <th>Dosage / Routine</th>
                                                <th>Duration</th>
                                                <th>Prescribing Doctor</th>
                                                <th class="text-end">Print Layout</th>
                                            </tr>
                                        </thead>
                                       <tbody>

                                        @forelse($prescriptions as $prescription)

                                        <tr>

                                            <td>

                                                <span class="fw-bold">

                                                    {{ $prescription->medicine_name }}

                                                </span>

                                            </td>

                                            <td>

                                                {{ $prescription->dosage }}

                                            </td>

                                            <td>

                                                {{ $prescription->duration }}

                                            </td>

                                            <td>

                                                Dr.
                                                {{ $prescription->doctor->user->name }}

                                            </td>

                                            <td class="text-end">

                                                <a href="{{ route('patient.prescription.print',$prescription->id) }}"
                                                    class="btn btn-premium-outline btn-sm">

                                                    <i class="bi bi-printer"></i>

                                                </a>

                                            </td>

                                        </tr>

                                        @empty

                                        <tr>

                                            <td colspan="5" class="text-center py-5">

                                                <i class="bi bi-capsule fs-2"></i>

                                                <br>

                                                No Active Prescription Found

                                            </td>

                                        </tr>

                                        @endforelse

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT PANEL: LAB DOCUMENTS & STATS -->
                        <div class="col-lg-4">
                            <div class="glass-card mb-4">
                                <h5 class="fw-bold mb-3">Health Metrics Log</h5>
                                <div class="p-3 border border-light border-opacity-10 rounded-4 glass-sub-card mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            {{-- BP Heart rate blood --}}
                                            {{-- <div class="text-muted small">Blood Pressure</div>
                                            <h4 class="fw-bold text-success mt-1 mb-0">120 / 80 <span style="font-size: 0.75rem;">mmHg</span></h4> --}}
                                            <div class="text-muted small">Blood Group</div>
                                            <h4 class="fw-bold text-success mt-1 mb-0">{{Auth::user()->patient->blood_group}}</span></h4>
                                        </div>
                                        <i class="bi bi-droplet-fill text-danger" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                                <div class="p-3 border border-light border-opacity-10 rounded-4 glass-sub-card mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="text-muted small">Age</div>
                                            <h4 class="fw-bold text-info mt-1 mb-0">{{Auth::user()->patient->age}}</h4>
                                        </div>
                                        <i class="bi bi-person-badge text-info" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                                <div class="p-3 border border-light border-opacity-10 rounded-4 glass-sub-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="text-muted small">Disease</div>
                                            <h4 class="fw-bold text-warning mt-1 mb-0">{{Auth::user()->patient->disease}}</h4>
                                        </div>
                                        <i class="bi bi-heart-pulse-fill text-success" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="glass-card">

                                <h5 class="fw-bold mb-3">

                                    My Diagnostic Documents

                                </h5>

                                <div class="d-flex flex-column gap-2">

                                    @forelse($reports as $report)

                                    <div class="p-3 border border-light border-opacity-10 rounded-4 glass-sub-card d-flex justify-content-between align-items-center">

                                        <div>

                                            <span class="fw-semibold d-block small">

                                                {{ $report->report_name }}

                                            </span>

                                            <span
                                                class="text-muted"
                                                style="font-size:.75rem;">

                                                {{ $report->report_type }}

                                                •

                                                {{ $report->file_size }}

                                            </span>

                                        </div>

                                        <a
                                            href="{{ asset('uploads/lab_reports/'.$report->file) }}"
                                            download
                                            class="btn btn-premium btn-sm">

                                            <i class="bi bi-download"></i>

                                        </a>

                                    </div>

                                    @empty

                                    <div class="text-center py-5">

                                        <i class="bi bi-file-earmark-medical fs-1 text-muted"></i>

                                        <p class="mt-3 mb-0">

                                            No Diagnostic Reports Available

                                        </p>

                                    </div>

                                    @endforelse

                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>
@endsection            