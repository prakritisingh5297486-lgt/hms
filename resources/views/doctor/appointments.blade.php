@extends('doctor.layouts.main')

@section('content')
    

            <!-- CONTENT BODY -->
            <div class="content-body">
                
                <!-- BREADCRUMB & HEADER -->
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <div>
                        <nav>
                            <ul class="breadcrumb-custom mb-1">
                                <li class="breadcrumb-item-custom"><a href="/doctor/dashboard">Home</a></li>
                                <li class="breadcrumb-item-custom">Appointments</li>
                            </ul>
                        </nav>
                        <h4 class="fw-bold mb-0">My Consult Appointments</h4>
                    </div>
                </div>

                <!-- SKELETON LOADER -->
                <div class="skeleton-wrapper row g-4 mb-4">
                    <div class="col-12"><div class="glass-card skeleton" style="height: 350px;"></div></div>
                </div>

                <!-- REAL CONTENT WRAPPER -->
                <div class="real-content-wrapper d-none">
                    
                    <!-- MY APPOINTMENTS LOG -->
                    <div class="glass-card">
                        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                            <h5 class="mb-0 fw-bold">Assigned Bookings Database</h5>
                            <div class="d-flex gap-2">
                                <input type="text" class="form-control form-glass py-1 px-3" style="max-width: 200px;" placeholder="Filter by patient name...">
                                <button class="btn btn-premium btn-sm" onclick="showToast('Roster synced', 'Appointment logs refreshed.', 'success')">Filter</button>
                            </div>
                        </div>

                        <div class="custom-table-container">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>Token ID</th>
                                        <th>Patient Profile</th>
                                        <th>Consultation Department</th>
                                        <th>Timing slot</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($appointments as $appoint)
                                    
                                    <tr>
                                        <td><span class="badge bg-primary rounded-pill px-3 py-1">Token #{{ $appoint->id }}</span></td>
                                        <td>
                                            <div class="fw-bold">{{$appoint->patient->user->name}}</div>
                                            <small class="text-muted">ID: #PT-{{$appoint->patient->id}}</small>
                                        </td>
                                        <td>{{$appoint->department}}</td>
                                        <td>{{$appoint->appointment_date->format('h:i A')}}</td>
                                        <td>
                                            <span class="custom-badge
                                                @if($appoint->status == 'Completed') badge-success
                                                @elseif($appoint->status == 'Pending') badge-warning
                                                @else badge-danger
                                                @endif">

                                                <i class="bi
                                                    @if($appoint->status == 'Completed') bi-check-circle
                                                    @elseif($appoint->status == 'Pending') bi-clock
                                                    @else bi-x-circle
                                                    @endif">
                                                </i>

                                                {{ $appoint->status }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            @if($appoint->status == 'Pending') <button class="btn btn-sm btn-premium" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#consultationModal{{ $appoint->id }}"> 
                                            Start Consult
                                            </button>

                                            @elseif($appoint->status == 'Completed')
                                                <button class="btn btn-sm btn-premium"
                                                    data-bs-toggle="modal"
                                                    
                                                    data-bs-target="#reviewModal{{ $appoint->id }}">
                                                    Review Diagnosis
                                                </button>

                                            @elseif($appoint->status == 'Cancelled')
                                                <button class="btn btn-sm btn-secondary" disabled>
                                                    Cancelled
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    {{-- <tr>
                                        <td><span class="badge bg-primary rounded-pill px-3 py-1">Token #14</span></td>
                                        <td>
                                            <div class="fw-bold">Michael Corleone</div>
                                            <small class="text-muted">ID: #PT-1099</small>
                                        </td>
                                        <td>Cardiology OPD</td>
                                        <td>10:45 AM</td>
                                        <td><span class="custom-badge badge-warning"><i class="bi bi-clock"></i> Pending</span></td>
                                        <td class="text-end">
                                            
                                        </td>
                                    </tr>--}}
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No appointments found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        

    <!-- MODAL: CLINICAL CONSULTATION -->
    @foreach($appointments as $appointment)
    <div class="modal fade modal-glass" id="consultationModal{{ $appointment->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-light border-opacity-10">
                    <h5 class="modal-title fw-bold">Consultation Portal: {{ $appointment->patient->user->name }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  action="{{ route('doctor.consultation.store',$appointment->id) }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="p-3 mb-4 rounded-4 border border-light border-opacity-10" style="background: rgba(255, 255, 255, 0.02);">
                            <div class="row text-white text-opacity-80">
                                <div class="col-md-6 mb-2">Patient ID: <span class="fw-bold text-white">#PT-{{ $appointment->patient->id }}</span></div>
                                <div class="col-md-6 mb-2">Age / Blood Group: <span class="fw-bold text-white">{{ $appointment->patient->age }}, {{ $appointment->patient->gender }} / {{ $appointment->patient->blood_group }}</span></div>
                                <div class="col-md-12">Allergies: <span class="fw-bold text-danger">{{ $appointment->patient->allergies ?? 'N/A' }}</span></div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label-custom">Clinical Findings & Diagnosis Summary</label>
                                <textarea class="form-control form-glass" rows="3" placeholder="Enter patient's symptoms, physical examination findings, vital signs, diagnosis, and clinical observations." name="diagnosis" required></textarea>
                            </div>
                            <div class="col-md-12">
                                <h6 class="fw-bold text-primary my-2">Issued Prescriptions</h6>
                                <div class="glass-sub-card p-3 rounded-4 mb-3 medicine-row">
                                    <div class="row g-2 mb-2 align-items-center">
                                        <div class="col-md-5">
                                            <label class="form-label-custom small">Medicine Name</label>
                                            <input type="text" class="form-control form-glass py-1" placeholder="Enter medicine name" name="medicine_name[]" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label-custom small">Dosage / Frequency</label>
                                            <input type="text" class="form-control form-glass py-1" placeholder="Example: 1-0-1 After Meals" name="dosage[]" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label-custom small">Duration</label>
                                            <input type="text" class="form-control form-glass py-1" placeholder="Example: 7 Days" name="duration[]" required>
                                        </div>
                                        <div class="col-md-1 text-end mt-4">
                                            <button class="btn btn-sm text-danger p-0 removeMedicine" type="button" onclick="showToast('Action simulator', 'Remove prescription field.', 'info')"><i class="bi bi-trash-fill"></i></button>
                                        </div>
                                    </div>
                                    <button class="btn btn-premium-outline btn-sm py-1 px-3 mt-2 addMedicine" type="button" onclick="showToast('Add Medicine', 'Appending blank prescription fields.', 'info')">+ Add Medicine</button>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label-custom">Special Directives for Patient / Pharmacy instructions</label>
                                <textarea class="form-control form-glass" rows="2" name="instructions" placeholder="Take tablets after meals. Watch for cardiac slowing symptoms..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-light border-opacity-10 ">
                        
                        <button type="button" class="btn btn-premium-outline" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-premium">Publish Consultation Record</button>
                    </div>
                </form>
                @if($appointment->status == 'Pending')
                <form action="{{ route('doctor.appointments.cancel', $appointment->id) }}"
                        method="POST"
                        class="me-auto">
                        @csrf
                        @method('PATCH')

                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this appointment?')">
                            Cancel Appointment
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade modal-glass" id="reviewModal{{ $appointment->id }}" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header border-light border-opacity-10">

                    <h5 class="modal-title fw-bold"> Consultation Review </h5>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <h6>Patient Name</h6>

                    <p>{{ $appointment->patient->user->name }}</p>

                    <hr>

                    <h6>Diagnosis</h6>

                    <p>{{ optional($appointment->consultation)->diagnosis }}</p>

                    <hr>

                    <h6>Prescriptions</h6>

                    <table class="table">

                        <thead>

                            <tr>

                                <th>Medicine</th>
                                <th>Dosage</th>
                                <th>Duration</th>

                            </tr>

                        </thead>

                        <tbody>

                        @foreach(optional($appointment->consultation)->prescriptions ?? [] as $medicine)

                            <tr>

                                <td>{{ $medicine->medicine_name }}</td>

                                <td>{{ $medicine->dosage }}</td>

                                <td>{{ $medicine->duration }}</td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                    <hr>

                    <h6>Instructions</h6>

                    <p>{{ optional($appointment->consultation)->instructions }}</p>

                </div>

                <div class="modal-footer">

                    <button class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Close

                    </button>

                </div>

            </div>

        </div>

    </div>
    @endforeach

    {{-- for add medicine and remove --}}
    <script>
        document.addEventListener("click", function(e){

        if(e.target.closest(".addMedicine")){

            let btn = e.target.closest(".addMedicine");

            let container = btn.previousElementSibling;

            let html = `
            <div class="glass-sub-card p-3 rounded-4 mb-3 medicine-row">

                <div class="row g-2 align-items-center">

                    <div class="col-md-5">
                        <label class="form-label-custom small">Medicine Name</label>
                        <input type="text"
                            class="form-control form-glass py-1"
                            name="medicine_name[]" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom small">Dosage / Frequency</label>
                        <input type="text"
                            class="form-control form-glass py-1"
                            name="dosage[]" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom small">Duration</label>
                        <input type="text"
                            class="form-control form-glass py-1"
                            name="duration[]" required>
                    </div>

                    <div class="col-md-1 mt-4">
                        <button type="button"
                                class="btn btn-sm text-danger removeMedicine">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </div>

                </div>

            </div>`;

            container.insertAdjacentHTML("beforeend", html);

        }

        if(e.target.closest(".removeMedicine")){

            let row = e.target.closest(".medicine-row");

            let container = row.parentElement;

            if(container.querySelectorAll(".medicine-row").length > 1){
                row.remove();
            }

        }

    });
</script>
@endsection