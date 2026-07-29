@extends('super-admin.layouts.main')
@section('content')

<!-- CONTENT BODY -->
<div class="content-body">

    <!-- BREADCRUMB -->
    <nav>
        <ul class="breadcrumb-custom">
            <li class="breadcrumb-item-custom"><a href="#">Home</a></li>
            <li class="breadcrumb-item-custom">Dashboard</li>
        </ul>
    </nav>

    <!-- SKELETON LOADER PLACEHOLDER -->
    <div class="skeleton-wrapper row g-4 mb-4">
        <div class="col-md-3">
            <div class="glass-card skeleton" style="height: 120px;"></div>
        </div>
        <div class="col-md-3">
            <div class="glass-card skeleton" style="height: 120px;"></div>
        </div>
        <div class="col-md-3">
            <div class="glass-card skeleton" style="height: 120px;"></div>
        </div>
        <div class="col-md-3">
            <div class="glass-card skeleton" style="height: 120px;"></div>
        </div>
    </div>

    <!-- REAL CONTENT WRAPPER -->
    <div class="real-content-wrapper d-none">

        <!-- STATISTICS CARDS -->
        <div class="row g-4 mb-4">

            <!-- Total Doctors -->
            <div class="col-xl-3 col-md-6">
                <div class="glass-card glass-card-hover stat-card">
                    <span class="text-muted fw-semibold" style="font-size:0.85rem;">
                        TOTAL DOCTORS
                    </span>
                    <h3 class="fw-bold mt-2 mb-1">
                        {{ $totalDoctors }}
                    </h3>
                    <div class="stat-card-icon text-primary">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                </div>
            </div>

            <!-- Total Patients -->
            <div class="col-xl-3 col-md-6">
                <div class="glass-card glass-card-hover stat-card">
                    <span class="text-muted fw-semibold" style="font-size:0.85rem;">
                        TOTAL PATIENTS
                    </span>
                    <h3 class="fw-bold mt-2 mb-1">
                        {{ $totalPatients }}
                    </h3>
                    <div class="stat-card-icon text-success">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="col-xl-3 col-md-6">
                <div class="glass-card glass-card-hover stat-card">
                    <span class="text-muted fw-semibold" style="font-size:0.85rem;">
                        TOTAL USERS
                    </span>
                    <h3 class="fw-bold mt-2 mb-1">
                        {{ $totalUsers }}
                    </h3>
                    <div class="stat-card-icon text-warning">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                </div>
            </div>

            <!-- Departments -->
            <div class="col-xl-3 col-md-6">
                <div class="glass-card glass-card-hover stat-card">
                    <span class="text-muted fw-semibold" style="font-size:0.85rem;">
                        TOTAL DEPARTMENTS
                    </span>
                    <h3 class="fw-bold mt-2 mb-1">
                        {{ $totalDepartments }}
                    </h3>
                    <div class="stat-card-icon text-info">
                        <i class="bi bi-building"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- Total Appointments -->
            <div class="col-xl-4 col-md-6">
                <div class="glass-card glass-card-hover stat-card">
                    <span class="text-muted fw-semibold">
                        TOTAL APPOINTMENTS
                    </span>
                    <h3 class="fw-bold mt-2 mb-1">
                        {{ $totalAppointments }}
                    </h3>
                    <div class="stat-card-icon text-primary">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                </div>
            </div>

            <!-- Today's Appointments -->
            <div class="col-xl-4 col-md-6">
                <div class="glass-card glass-card-hover stat-card">
                    <span class="text-muted fw-semibold">
                        TODAY'S APPOINTMENTS
                    </span>
                    <h3 class="fw-bold mt-2 mb-1">
                        {{ $todayAppointments }}
                    </h3>
                    <div class="stat-card-icon text-success">
                        <i class="bi bi-calendar2-check-fill"></i>
                    </div>
                </div>
            </div>
            <!-- Pending -->
            <div class="col-xl-2 col-md-6">
                <div class="glass-card glass-card-hover stat-card">
                    <span class="text-muted fw-semibold">
                        PENDING
                    </span>
                    <h3 class="fw-bold mt-2 mb-1">
                        {{ $pendingAppointments }}
                    </h3>
                    <div class="stat-card-icon text-warning">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>

            <!-- Completed -->
            <div class="col-xl-2 col-md-6">
                <div class="glass-card glass-card-hover stat-card">
                    <span class="text-muted fw-semibold">
                        COMPLETED
                    </span>
                    <h3 class="fw-bold mt-2 mb-1">
                        {{ $completedAppointments }}
                    </h3>
                    <div class="stat-card-icon text-success">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- Monthly Appointments -->
            <div class="col-lg-8">
                <div class="glass-card h-100">
                    <h5 class="mb-4">
                        Monthly Appointment Analytics
                    </h5>
                    <canvas id="appointmentChart" height="120"></canvas>
                </div>
            </div>
            <!-- Appointment Status -->
            <div class="col-lg-4">
                <div class="glass-card h-100">
                    <h5 class="mb-4">Appointment Status</h5>
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- TABLES & SCHEDULE DETAILS -->
        <div class="row g-4 mb-4">
            <!-- Recent Appointments Table -->
            <div class="col-xl-8">
                <div class="glass-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5>Recent Patient Appointments</h5>
                        <a href="{{route('super-admin.appointments')}}" class="btn btn-premium-outline btn-sm px-3 py-1">View All</a>
                    </div>
                    <div class="custom-table-container">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Doctor</th>
                                    <th>Department</th>
                                    <th>Appointment Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($recentAppointments as $appointment)
                            <tr>

                                <td>
                                    <div class="d-flex align-items-center gap-2">

                                        @if(optional($appointment->patient->user)->image)
                                            <img src="{{ asset('patients/'.optional($appointment->patient->user)->image) }}"
                                                class="rounded-circle"
                                                style="width:32px;height:32px;object-fit:cover;">
                                        @else
                                            <img src="{{ asset('images/default-user.png') }}"
                                                class="rounded-circle"
                                                style="width:32px;height:32px;object-fit:cover;">
                                        @endif

                                        <div class="fw-semibold">
                                            {{ optional($appointment->patient->user)->name }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ optional($appointment->doctor->user)->name }}
                                </td>
                                <td>
                                    {{ optional($appointment->doctor)->department }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}
                                </td>
                                <td>
                                    @if($appointment->status=='Completed')
                                        <span class="custom-badge badge-success">
                                            Completed
                                        </span>
                                    @elseif($appointment->status=='Pending')
                                        <span class="custom-badge badge-warning">
                                            Pending
                                        </span>
                                    @elseif($appointment->status=='Confirmed')
                                        <span class="custom-badge badge-info">
                                            Confirmed
                                        </span>
                                    @else
                                        <span class="custom-badge badge-danger">
                                            Cancelled
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    No Appointments Found
                                </td>
                            </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Doctor Availability -->
            <div class="col-xl-4">
                <div class="glass-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5>Latest Doctors</h5>
                        <a href="{{ route('super-admin.doctors') }}"
                            class="btn btn-premium-outline btn-sm">
                            View All
                        </a>
                    </div>
                    @forelse($latestDoctors as $doctor)
                    <div class="p-3 rounded-4 border border-light border-opacity-10 d-flex justify-content-between align-items-center glass-sub-card mb-3">
                        <div class="d-flex align-items-center gap-3">
                            @if($doctor->user->image)
                                <img src="{{ asset('users/'.$doctor->user->image) }}"
                                    class="rounded-circle"
                                    style="width:45px;height:45px;object-fit:cover;">
                            @else
                                <img src="{{ asset('images/default-user.png') }}"
                                    class="rounded-circle"
                                    style="width:45px;height:45px;object-fit:cover;">
                            @endif
                            <div>
                                <div class="fw-semibold">
                                    Dr. {{ $doctor->user->name }}
                                </div>
                                <small class="text-muted">
                                    {{ $doctor->department ?? 'Not Assigned' }}
                                </small>
                                <br>
                                <small class="text-muted">
                                    {{ $doctor->user->email }}
                                </small>
                            </div>
                        </div>
                        @if($doctor->user->status == 'active')
                            <span class="custom-badge badge-success">
                                Active
                            </span>
                        @else
                            <span class="custom-badge badge-danger">
                                Inactive
                            </span>
                        @endif
                    </div>
                    @empty
                        <p class="text-center text-muted">
                            No Doctors Found
                        </p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- TIMELINE, ACTIVITY, PERFORMANCE -->
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="glass-card h-100">
                    <h5 class="mb-4"> Recent Activities</h5>
                    <div class="timeline-custom">
                        @forelse($recentActivities as $activity)
                            <div class="timeline-item {{ $activity['type'] }}">
                                <div class="fw-semibold" style="font-size:0.85rem;">
                                    {{ $activity['title'] }}
                                </div>
                                <small class="text-muted d-block mb-1">
                                    {{ $activity['description'] }}
                                </small>
                                <span style="font-size:.75rem;color:var(--text-muted);">
                                    {{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}
                                </span>
                            </div>
                        @empty
                            <p class="text-center text-muted">
                                No Recent Activity
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="glass-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5>Quick Actions</h5>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <a href="{{ route('super-admin.doctors') }}"
                            class="text-decoration-none">
                                <div class="glass-sub-card p-4 text-center rounded-4">
                                    <i class="bi bi-person-plus-fill fs-1 text-primary"></i>
                                    <h6 class="mt-3">Manage Doctors</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('super-admin.patients') }}"
                            class="text-decoration-none">
                                <div class="glass-sub-card p-4 text-center rounded-4">
                                    <i class="bi bi-people-fill fs-1 text-success"></i>
                                    <h6 class="mt-3">Manage Patients</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('super-admin.appointments') }}"
                            class="text-decoration-none">
                                <div class="glass-sub-card p-4 text-center rounded-4">
                                    <i class="bi bi-calendar-check-fill fs-1 text-warning"></i>
                                    <h6 class="mt-3">Appointments</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('super-admin.users') }}"
                            class="text-decoration-none">
                                <div class="glass-sub-card p-4 text-center rounded-4">
                                    <i class="bi bi-person-lines-fill fs-1 text-info"></i>
                                    <h6 class="mt-3">Manage Users</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('super-admin.settings') }}"
                            class="text-decoration-none">
                                <div class="glass-sub-card p-4 text-center rounded-4">
                                    <i class="bi bi-gear-fill fs-1 text-secondary"></i>
                                    <h6 class="mt-3">Settings</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('super-admin.reports') }}"
                            class="text-decoration-none">
                                <div class="glass-sub-card p-4 text-center rounded-4">
                                    <i class="bi bi-file-earmark-bar-graph-fill fs-1 text-danger"></i>
                                    <h6 class="mt-3">Reports</h6>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                                    
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Dashboard ApexCharts config -->
<script>
    const appointmentChart = new Chart(
        document.getElementById('appointmentChart'), {
            type: 'bar',
            data: {
                labels: [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec'
                ],
                datasets: [{
                    label: 'Appointments',
                    data: @json($monthlyAppointments),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    const statusChart = new Chart(
        document.getElementById('statusChart'),
        {
            type: 'doughnut',
            data: {
                labels: [
                    'Pending',
                    'Confirmed',
                    'Completed',
                    'Cancelled'
                ],
                datasets: [{
                    data: @json($appointmentStatus)
                }]
            },
            options: {
                responsive: true
            }
        });
</script>


@endsection