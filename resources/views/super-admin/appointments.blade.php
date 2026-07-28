@extends('super-admin.layouts.main')
    @section('content')
    
    <style>
        .calendar-day-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            text-align: center;
        }
        .calendar-header-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            text-align: center;
            font-weight: 600;
            color: var(--text-muted);
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
        }
        .calendar-cell {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all var(--transition-speed);
            border: 1px solid transparent;
            color: var(--text-secondary);
            /* add */
            position:relative;
        }
        .calendar-cell:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--border-color);
        }
        .calendar-cell.active {
            background: var(--primary);
            color: #fff !important;
            font-weight: 600;
        }
        .calendar-cell.has-appointment {
            color: var(--text-primary);
            font-weight: 600;
            position: relative;
        }
        .calendar-cell.has-appointment::after {
            content: '';
            position: absolute;
            bottom: 4px;
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: var(--secondary);
        }
        .token-card {
            border: 2px dashed var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.01);
            position: relative;
        }
        .token-card::before, .token-card::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            background: var(--body-bg);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
        }
        .token-card::before { left: -11px; }
        .token-card::after { right: -11px; }
        /* add */

        .calendar-cell.empty{
            border:none;
            background:transparent;
            box-shadow:none;
        }

        .appointment-badge{
            position:absolute;
            bottom:6px;
            left:6px;

            width:20px;
            height:20px;

            border-radius:50%;

            background:#22c7ff;
            color:#fff;

            font-size:11px;
            font-weight:600;

            display:flex;
            align-items:center;
            justify-content:center;
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
                        <li class="breadcrumb-item-custom">Appointments</li>
                    </ul>
                </nav>
                <h4 class="fw-bold mb-0">Appointments Planner</h4>
            </div>
        </div>

        <!-- SKELETON LOADER -->
        <div class="skeleton-wrapper row g-4 mb-4">
            <div class="col-md-4"><div class="glass-card skeleton" style="height: 300px;"></div></div>
            <div class="col-md-8"><div class="glass-card skeleton" style="height: 500px;"></div></div>
        </div>

        <!-- REAL CONTENT WRAPPER -->
        <div class="real-content-wrapper d-none">
            
            <div class="row g-4">
                <!-- LEFT COLUMN: BOOKING FORM & CALENDAR -->
                <div class="col-xl-4">
                    <!-- MOCK CALENDAR -->
                    <div class="glass-card mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">
                                {{ $firstDayOfMonth->format('F Y') }}
                            </h6>

                            <div class="d-flex gap-1">
                                <a href="{{ route('super-admin.appointments', [
                                    'month' => $previousMonth->month,
                                    'year' => $previousMonth->year
                                ]) }}"
                                class="btn btn-sm btn-premium-outline p-1">

                                    <i class="bi bi-chevron-left" style="font-size: 0.8rem"></i>

                                </a>

                                <a href="{{ route('super-admin.appointments', [
                                    'month' => $nextMonth->month,
                                    'year' => $nextMonth->year
                                ]) }}"
                                class="btn btn-sm btn-premium-outline p-1">

                                    <i class="bi bi-chevron-right" style="font-size: 0.8rem"></i>

                                </a>
                            </div>
                        </div>

                        <div class="calendar-header-grid">
                            <div>Su</div>
                            <div>Mo</div>
                            <div>Tu</div>
                            <div>We</div>
                            <div>Th</div>
                            <div>Fr</div>
                            <div>Sa</div>
                        </div>

                        <div class="calendar-day-grid">

                            {{-- Empty cells before month starts --}}
                            @for($i=0;$i<$startDay;$i++)
                                <div class="calendar-cell empty"></div>
                            @endfor

                            {{-- Days --}}
                            @for($day=1;$day<=$daysInMonth;$day++)

                                @php
                                    $date = $firstDayOfMonth
                                                ->copy()
                                                ->day($day)
                                                ->format('Y-m-d');

                                    $count = $calendarAppointments[$date] ?? 0;

                                    $isToday = $date == now()->format('Y-m-d');
                                @endphp

                                <div class="calendar-cell
                                            {{ $count ? 'has-appointment' : '' }}
                                            {{ $isToday ? 'active' : '' }}">

                                    {{ $day }}

                                    @if($count)

                                        <span class="appointment-badge active">
                                            {{ $count }}
                                        </span>

                                    @endif

                                </div>

                            @endfor

                        </div>
                    </div>
                    <!-- Appointment Management -->
                    <div class="glass-card">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">
                                <i class="bi bi-funnel me-2"></i>
                                Appointment Management
                            </h5>

                            <span class="badge bg-primary">
                                {{ $appointments->count() }} Records
                            </span>
                        </div>

                        <form action="{{ route('super-admin.appointments') }}" method="GET">

                            <!-- Search Patient -->
                            <div class="mb-3">
                                <label class="form-label-custom">
                                    Patient
                                </label>

                                <select name="patient" class="form-select form-glass">

                                    <option value="">All Patients</option>

                                    @foreach($patients as $patient)

                                        <option value="{{ $patient->id }}"
                                            {{ request('patient') == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->user->name }}
                                        </option>

                                    @endforeach

                                </select>
                            </div>

                            <!-- Doctor -->
                            <div class="mb-3">

                                <label class="form-label-custom">Doctor</label>

                                <select name="doctor" class="form-select form-glass">

                                    <option value="">All Doctors</option>

                                    @foreach($doctors as $doctor)

                                        <option value="{{ $doctor->id }}"
                                            {{ request('doctor')==$doctor->id ? 'selected':'' }}>
                                            {{ $doctor->user->name }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <!-- Status -->
                            <div class="mb-3">

                                <label class="form-label-custom">Status</label>

                                <select name="status" class="form-select form-glass">

                                    <option value="">All Status</option>

                                    <option value="Pending"
                                        {{ request('status')=='Pending' ? 'selected':'' }}>
                                        Pending
                                    </option>

                                    <option value="Confirmed"
                                        {{ request('status')=='Confirmed' ? 'selected':'' }}>
                                        Confirmed
                                    </option>

                                    <option value="Completed"
                                        {{ request('status')=='Completed' ? 'selected':'' }}>
                                        Completed
                                    </option>

                                    <option value="Cancelled"
                                        {{ request('status')=='Cancelled' ? 'selected':'' }}>
                                        Cancelled
                                    </option>

                                </select>

                            </div>

                            <!-- Date -->
                            <div class="mb-4">

                                <label class="form-label-custom">Appointment Date</label>

                                <input type="date" name="date" class="form-control form-glass" value="{{ request('date') }}">

                            </div>

                            <!-- Buttons -->

                            <div class="d-grid gap-2">

                                <button class="btn btn-premium"><i class="bi bi-search me-2"></i>Apply Filter</button>

                                <a href="{{ route('super-admin.appointments') }}" class="btn btn-premium-outline">

                                    <i class="bi bi-arrow-clockwise me-2"></i>

                                    Reset Filter

                                </a>

                            </div>

                        </form>

                        <hr class="my-4">

                        <h6 class="fw-bold mb-3">

                            Today's Statistics

                        </h6>

                        <div class="row g-3">

                            <div class="col-6">

                                <div class="glass-sub-card p-3 text-center">

                                    <h4 class="fw-bold text-primary">

                                        {{ $todayAppointments }}

                                    </h4>

                                    <small>Today's Appointments</small>

                                </div>

                            </div>

                            <div class="col-6">

                                <div class="glass-sub-card p-3 text-center">

                                    <h4 class="fw-bold text-warning">

                                        {{ $pendingAppointments }}

                                    </h4>

                                    <small>Pending</small>

                                </div>

                            </div>

                            <div class="col-6">

                                <div class="glass-sub-card p-3 text-center">

                                    <h4 class="fw-bold text-success">

                                        {{ $completedAppointments }}

                                    </h4>

                                    <small>Completed</small>

                                </div>

                            </div>

                            <div class="col-6">

                                <div class="glass-sub-card p-3 text-center">

                                    <h4 class="fw-bold text-danger">

                                        {{ $cancelledAppointments }}

                                    </h4>

                                    <small>Cancelled</small>

                                </div>

                            </div>

                        </div>

                    </div>
                    <!-- BOOK APPOINTMENT FORM -->
                    {{-- <div class="glass-card">
                        <h6 class="fw-bold mb-4">Book New Appointment</h6>
                        <form action="{{ route('super-admin.appointments.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label-custom">Select Patient</label>
                                <select name="doctor_id" class="form-select form-glass" required>
                                    <option value="">Choose Doctor</option>

                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">
                                            Dr. {{ $doctor->user->name }}
                                            ({{ $doctor->department }})
                                        </option>
                                    @endforeach

                                </select>

                            </div>
                            <div class="mb-3">
                                <label class="form-label-custom">Select Doctor</label>
                                <select name="patient_id" class="form-select form-glass" required>
                                    <option value="">Choose Patient Profile</option>

                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}">
                                            {{ $patient->user->name }}
                                            (#PT-{{ str_pad($patient->id,4,'0',STR_PAD_LEFT) }})
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-custom">Appointment Date</label>
                                <input type="date"
                                    name="appointment_date"
                                    class="form-control form-glass"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label-custom">Appointment Time</label>
                                <input type="time"
                                    name="appointment_time"
                                    class="form-control form-glass"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label-custom">Consultation Type</label>
                                <select name="consultation_type"
                                        class="form-select form-glass"
                                        required>

                                    <option value="Regular">Regular</option>
                                    <option value="Emergency">Emergency</option>
                                    <option value="Follow Up">Follow Up</option>
                                    <option value="Online">Online</option>

                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label-custom">Symptoms / Reason</label>

                                <textarea name="symptoms"
                                        rows="3"
                                        class="form-control form-glass"
                                        placeholder="Enter symptoms"
                                        required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label-custom">Status</label>

                                <select name="status"
                                        class="form-select form-glass">

                                    <option value="Pending">Pending</option>
                                    <option value="Confirmed">Confirmed</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Cancelled">Cancelled</option>

                                </select>
                            </div>
                            <div class="row g-2 mb-4">
                                <div class="col-6">
                                    <label class="form-label-custom">Time Slot</label>

                                    <input type="time"
                                        name="appointment_time"
                                        class="form-control form-glass"
                                        value="{{ old('appointment_time') }}"
                                        required>
                                </div>

                                <div class="col-6">
                                    <label class="form-label-custom">Charge Category</label>

                                    <select name="consultation_type"
                                            class="form-select form-glass"
                                            required>

                                        <option value="">Select Category</option>

                                        <option value="Regular"
                                            {{ old('consultation_type')=='Regular' ? 'selected' : '' }}>
                                            Regular
                                        </option>

                                        <option value="Urgent"
                                            {{ old('consultation_type')=='Urgent' ? 'selected' : '' }}>
                                            Urgent
                                        </option>

                                        <option value="Insurance"
                                            {{ old('consultation_type')=='Insurance' ? 'selected' : '' }}>
                                            Insurance
                                        </option>

                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-premium w-100">Schedule & Issue Token</button>
                        </form>
                    </div> --}}
                </div>

                <!-- RIGHT COLUMN: APPOINTMENT DIRECTORY -->
                <div class="col-xl-8">
                    <div class="glass-card h-100">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="mb-1 fw-bold">Appointment List</h5>
                                <small class="text-muted">Latest Patient Appointments</small>
                            </div>

                            <span class="badge bg-primary">
                                {{ $appointments->count() }} Records
                            </span>
                        </div>

                        <div class="custom-table-container">

                            <table class="custom-table">

                                <thead>

                                    <tr>

                                        <th>Token</th>

                                        <th>Patient</th>

                                        <th>Doctor</th>

                                        <th>Date</th>

                                        <th>Time</th>

                                        <th>Status</th>

                                        <th class="text-center">Action</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($appointments as $appointment)
                                    <tr>
                                        <td>
                                            <span class="badge">

                                                #{{ $appointment->token }}

                                            </span>

                                        </td>

                                        <td>

                                            <div class="fw-semibold">

                                                {{ $appointment->patient->user->name }}

                                            </div>

                                        </td>

                                        <td>

                                            {{ $appointment->doctor->user->name }}

                                        </td>

                                        <td>

                                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}

                                        </td>

                                        <td>

                                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}

                                        </td>

                                        <td>

                                            @if($appointment->status=='Pending')

                                                <span class="badge bg-warning">

                                                    Pending

                                                </span>

                                            @elseif($appointment->status=='Confirmed')

                                                <span class="badge bg-primary">

                                                    Confirmed

                                                </span>

                                            @elseif($appointment->status=='Completed')

                                                <span class="badge bg-success">

                                                    Completed

                                                </span>

                                            @else

                                                <span class="badge bg-danger">

                                                    Cancelled

                                                </span>

                                            @endif

                                        </td>

                                        <td>
                                    
                                            <div class="d-flex justify-content-center gap-2">

                                                <button
                                                    class="btn btn-info btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewAppointmentModal"

                                                    data-patient="{{ $appointment->patient->user->name }}"
                                                    data-doctor="{{ $appointment->doctor->user->name }}"
                                                    data-date="{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}"
                                                    data-time="{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}"
                                                    data-status="{{ $appointment->status }}"
                                                    data-symptoms="{{ $appointment->symptoms }}">

                                                    <i class="bi bi-eye"></i>

                                                </button>

                                                <button
                                                    class="btn btn-warning btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editAppointmentModal"

                                                    data-id="{{ $appointment->id }}"
                                                    data-patient="{{ $appointment->patient_id }}"
                                                    data-doctor="{{ $appointment->doctor_id }}"
                                                    data-datetime="{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d\TH:i') }}"
                                                    data-status="{{ $appointment->status }}"
                                                    data-symptoms="{{ $appointment->symptoms }}">

                                                    <i class="bi bi-pencil-square"></i>

                                                </button>

                                                <button
                                                    type="button"
                                                    class="btn btn-danger btn-sm deleteAppointment"
                                                    data-id="{{ $appointment->id }}"
                                                    data-patient="{{ $appointment->patient->user->name }}">

                                                    <i class="bi bi-trash"></i>

                                                </button>

                                                <form
                                                    id="deleteForm{{ $appointment->id }}"
                                                    action="{{ route('super-admin.appointments.destroy', $appointment->id) }}"
                                                    method="POST"
                                                    class="d-none">

                                                    @csrf
                                                    @method('DELETE')

                                                </form>
                                            </div>

                                        </td>

                                    </tr>

                                    @empty

                                    <tr>

                                        <td colspan="7">

                                            <div class="text-center py-5">

                                                <i class="bi bi-calendar-x display-5 text-muted"></i>

                                                <h6 class="mt-3">

                                                    No Appointment Found

                                                </h6>

                                            </div>

                                        </td>

                                    </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
    <div class="modal fade" id="viewAppointmentModal" tabindex="-1">

        <div class="modal-dialog modal-lg">

            <div class="modal-content glass-card">

                <div class="modal-header">

                    <h5>Appointment Details</h5>

                    <button class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label>Patient</label>

                            <input id="view_patient" class="form-control form-glass" readonly>

                        </div>

                        <div class="col-md-6">

                            <label>Doctor</label>

                            <input id="view_doctor" class="form-control form-glass" readonly>

                        </div>

                        <div class="col-md-6">

                            <label>Date</label>

                            <input id="view_date" class="form-control form-glass" readonly>

                        </div>

                        <div class="col-md-6">

                            <label>Time</label>

                            <input id="view_time" class="form-control form-glass" readonly>

                        </div>

                        <div class="col-md-6">

                            <label>Status</label>

                            <input id="view_status" class="form-control form-glass" readonly>

                        </div>

                        <div class="col-12">

                            <label>Symptoms</label>

                            <textarea id="view_symptoms" class="form-control form-glass" readonly></textarea>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <div class="modal fade" id="editAppointmentModal" tabindex="-1">

        <div class="modal-dialog modal-lg">

            <div class="modal-content glass-card">

                <form method="POST" id="editAppointmentForm">

                    @csrf
                    @method('PUT')

                    <div class="modal-header">

                        <h5 class="fw-bold">
                            Edit Appointment
                        </h5>

                        <button class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>

                    </div>

                    <div class="modal-body">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label-custom">
                                    Patient
                                </label>

                                <select
                                    name="patient_id"
                                    id="edit_patient"
                                    class="form-select form-glass">

                                    @foreach($patients as $patient)

                                    <option value="{{ $patient->id }}">

                                        {{ $patient->user->name }}

                                    </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label-custom">
                                    Doctor
                                </label>

                                <select
                                    name="doctor_id"
                                    id="edit_doctor"
                                    class="form-select form-glass">

                                    @foreach($doctors as $doctor)

                                    <option value="{{ $doctor->id }}">

                                        {{ $doctor->user->name }}

                                    </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label-custom">
                                    Appointment Date & Time
                                </label>

                                <input
                                    type="datetime-local"
                                    name="appointment_date"
                                    id="edit_datetime"
                                    class="form-control form-glass">

                            </div>

                            <div class="col-md-6">

                                <label class="form-label-custom">
                                    Status
                                </label>

                                <select
                                    name="status"
                                    id="edit_status"
                                    class="form-select form-glass">

                                    <option value="Pending">Pending</option>

                                    <option value="Confirmed">Confirmed</option>

                                    <option value="Completed">Completed</option>

                                    <option value="Cancelled">Cancelled</option>

                                </select>

                            </div>

                            <div class="col-12">

                                <label class="form-label-custom">

                                    Symptoms

                                </label>

                                <textarea
                                    name="symptoms"
                                    id="edit_symptoms"
                                    rows="4"
                                    class="form-control form-glass"></textarea>

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button
                            class="btn btn-premium">

                            <i class="bi bi-check-circle me-2"></i>

                            Update Appointment

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
    <div class="modal fade" id="editAppointmentModal" tabindex="-1">

        <div class="modal-dialog modal-lg">

            <div class="modal-content glass-card">

                <form method="POST" id="editAppointmentForm">

                    @csrf
                    @method('PUT')

                    <div class="modal-header">

                        <h5 class="fw-bold">
                            Edit Appointment
                        </h5>

                        <button class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>

                    </div>

                    <div class="modal-body">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label-custom">
                                    Patient
                                </label>

                                <select
                                    name="patient_id"
                                    id="edit_patient"
                                    class="form-select form-glass">

                                    @foreach($patients as $patient)

                                    <option value="{{ $patient->id }}">

                                        {{ $patient->user->name }}

                                    </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label-custom">
                                    Doctor
                                </label>

                                <select
                                    name="doctor_id"
                                    id="edit_doctor"
                                    class="form-select form-glass">

                                    @foreach($doctors as $doctor)

                                    <option value="{{ $doctor->id }}">

                                        {{ $doctor->user->name }}

                                    </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label-custom">
                                    Appointment Date & Time
                                </label>

                                <input
                                    type="datetime-local"
                                    name="appointment_date"
                                    id="edit_datetime"
                                    class="form-control form-glass">

                            </div>

                            <div class="col-md-6">

                                <label class="form-label-custom">
                                    Status
                                </label>

                                <select
                                    name="status"
                                    id="edit_status"
                                    class="form-select form-glass">

                                    <option value="Pending">Pending</option>

                                    <option value="Confirmed">Confirmed</option>

                                    <option value="Completed">Completed</option>

                                    <option value="Cancelled">Cancelled</option>

                                </select>

                            </div>

                            <div class="col-12">

                                <label class="form-label-custom">

                                    Symptoms

                                </label>

                                <textarea
                                    name="symptoms"
                                    id="edit_symptoms"
                                    rows="4"
                                    class="form-control form-glass"></textarea>

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button
                            class="btn btn-premium">

                            <i class="bi bi-check-circle me-2"></i>

                            Update Appointment

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <!-- MODAL: QUEUE SLIP / TOKEN PRINT MODAL -->
    <div class="modal fade modal-glass" id="tokenSlipModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content">
                <div class="modal-header border-light border-opacity-10">
                    <h5 class="modal-title fw-bold">Appointment Queue Ticket</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="token-card mb-4 text-start">
                        <div class="text-center mb-3">
                            <h4 class="fw-bold mb-1">AuraHMS Clinic Slip</h4>
                            <span class="text-muted small">Token ID: #AURA-2026-0042</span>
                        </div>
                        <div class="border-top border-light border-opacity-10 py-3 text-center my-3">
                            <div class="text-muted" style="font-size: 0.85rem;">YOUR QUEUE POSITION:</div>
                            <h1 class="fw-bold text-primary mb-0 mt-1">Queue #14</h1>
                        </div>
                        <div style="font-size: 0.85rem;" class="d-flex flex-column gap-2 text-white text-opacity-80">
                            <div class="d-flex justify-content-between"><span>Patient:</span> <span class="fw-semibold text-white">Michael Corleone</span></div>
                            <div class="d-flex justify-content-between"><span>Doctor:</span> <span class="fw-semibold text-white">Dr. John Carter</span></div>
                            <div class="d-flex justify-content-between"><span>Department:</span> <span class="fw-semibold text-white">Pediatrics Division</span></div>
                            <div class="d-flex justify-content-between"><span>Timing slot:</span> <span class="fw-semibold text-white">10:45 AM (Today)</span></div>
                        </div>
                        <div class="text-center mt-4">
                            <div class="p-3 bg-white d-inline-block rounded-3 shadow-sm">
                                <i class="bi bi-qr-code" style="font-size: 3.5rem; color: #000;"></i>
                            </div>
                            <div class="text-muted mt-2" style="font-size: 0.7rem;">Scan at department waiting room entrance.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-light border-opacity-10">
                    <button class="btn btn-premium-outline w-100 mb-2" onclick="window.print()"><i class="bi bi-printer"></i> Print Token Slip</button>
                    <button class="btn btn-premium w-100" data-bs-dismiss="modal">Close Ticket View</button>
                </div>
            </div>
        </div>
    </div>
    {{-- view appointment --}}
    <script>
        const viewModal = document.getElementById('viewAppointmentModal');

        viewModal.addEventListener('show.bs.modal', function (event) {

            const button = event.relatedTarget;

            document.getElementById('view_patient').value = button.getAttribute('data-patient');
            document.getElementById('view_doctor').value = button.getAttribute('data-doctor');
            document.getElementById('view_date').value = button.getAttribute('data-date');
            document.getElementById('view_time').value = button.getAttribute('data-time');
            document.getElementById('view_status').value = button.getAttribute('data-status');
            document.getElementById('view_symptoms').value = button.getAttribute('data-symptoms');

        });
    </script>
    {{-- edit appointment --}}
    <script>
        const editModal = document.getElementById('editAppointmentModal');

        editModal.addEventListener('show.bs.modal', function(event){

            const button = event.relatedTarget;

            let id = button.getAttribute('data-id');

            document.getElementById('editAppointmentForm').action =
                "/super-admin/appointments/" + id;

            document.getElementById('edit_patient').value =
                button.getAttribute('data-patient');

            document.getElementById('edit_doctor').value =
                button.getAttribute('data-doctor');

            document.getElementById('edit_datetime').value =
                button.getAttribute('data-datetime');

            document.getElementById('edit_status').value =
                button.getAttribute('data-status');

            document.getElementById('edit_symptoms').value =
                button.getAttribute('data-symptoms');

        });
    </script>
    {{-- for delete appointment sweet alert cdn --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.deleteAppointment').forEach(button => {

            button.addEventListener('click', function () {

                let id = this.dataset.id;
                let patient = this.dataset.patient;

                Swal.fire({

                    title: 'Delete Appointment?',

                    html: 'Do you really want to delete <br><b>' + patient + '</b> appointment?',

                    icon: 'warning',

                    showCancelButton: true,

                    confirmButtonColor: '#dc3545',

                    cancelButtonColor: '#6c757d',

                    confirmButtonText: 'Yes, Delete',

                    cancelButtonText: 'Cancel'

                }).then((result) => {

                    if (result.isConfirmed) {

                        document.getElementById('deleteForm' + id).submit();

                    }

                });

            });

        });
    </script>
@endsection