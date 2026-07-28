@extends('super-admin.layouts.main')
@section('content')

            <!-- CONTENT BODY -->
            <div class="content-body">
                
                <!-- BREADCRUMB & HEADER -->
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <div>
                        <nav>
                            <ul class="breadcrumb-custom mb-1">
                                <li class="breadcrumb-item-custom"><a href="/super-admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item-custom">Reports</li>
                            </ul>
                        </nav>
                        <h4 class="fw-bold mb-0">Analytics & Enterprise Reports</h4>
                    </div>

                    <!-- EXPORTS BUTTON BAR -->
                    <div class="d-flex gap-2">

                    <a href="{{ route('super-admin.reports.export.pdf') }}"
                    class="btn btn-premium btn-sm px-3">

                        <i class="bi bi-file-earmark-pdf"></i>
                        Export PDF

                    </a>

                    <a href="{{ route('super-admin.reports.export.excel') }}"
                    class="btn btn-premium-outline btn-sm px-3">

                        <i class="bi bi-file-earmark-spreadsheet"></i>
                        Export Excel

                    </a>

                </div>
                </div>

                <!-- SKELETON LOADER -->
                <div class="skeleton-wrapper row g-4 mb-4">
                    <div class="col-md-8"><div class="glass-card skeleton" style="height: 400px;"></div></div>
                    <div class="col-md-4"><div class="glass-card skeleton" style="height: 400px;"></div></div>
                </div>

                <!-- REAL CONTENT WRAPPER -->
                <div class="real-content-wrapper d-none">
                    
                    <div class="row g-4">
                        <!-- LEFT COLUMN: LARGE APPOINTMENTS CHART -->
                        <div class="col-xl-8">
                            <div class="glass-card">
                                <h5 class="mb-4">OPD Admission & Clinic Productivity Trends</h5>
                                <div id="activity-reports-chart"></div>
                                <div class="row g-4 mb-4">

                                    <div class="col-xl-3 col-md-6">
                                        <div class="glass-card text-center">
                                            <i class="bi bi-people-fill fs-1 text-primary"></i>
                                            <h3 class="mt-3">{{ $totalPatients }}</h3>
                                            <p class="text-muted mb-0">Total Patients</p>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <div class="glass-card text-center">
                                            <i class="bi bi-person-badge-fill fs-1 text-success"></i>
                                            <h3 class="mt-3">{{ $totalDoctors }}</h3>
                                            <p class="text-muted mb-0">Total Doctors</p>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <div class="glass-card text-center">
                                            <i class="bi bi-calendar-check-fill fs-1 text-warning"></i>
                                            <h3 class="mt-3">{{ $totalAppointments }}</h3>
                                            <p class="text-muted mb-0">Appointments</p>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <div class="glass-card text-center">
                                            <i class="bi bi-cash-stack fs-1 text-danger"></i>
                                            <h3 class="mt-3">₹ {{ number_format($totalRevenue,2) }}</h3>
                                            <p class="text-muted mb-0">Revenue</p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        

                        <!-- RIGHT COLUMN: DRUG INVENTORY & LOW STOCK LABELS -->
                        <div class="col-xl-4">
                            <div class="glass-card h-100">

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="mb-0">Critical Stocks Alert</h5>
                                    <span class="badge bg-danger">Pharmacy</span>
                                </div>

                                <div class="d-flex flex-column gap-3">

                                    @forelse($lowStocks as $medicine)

                                        <div class="p-3 border border-light border-opacity-10 rounded-4 glass-sub-card">

                                            <div class="d-flex justify-content-between align-items-start">

                                                <div>

                                                    <h6 class="fw-bold mb-1">
                                                        {{ $medicine->medicine_name }}
                                                    </h6>

                                                    @if($medicine->stock <= 10)

                                                        <span class="custom-badge badge-danger">
                                                            Low Stock : {{ $medicine->stock }} Left
                                                        </span>

                                                    @else

                                                        <span class="custom-badge badge-warning">
                                                            Restock Warning : {{ $medicine->stock }} Left
                                                        </span>

                                                    @endif

                                                </div>

                                                <span class="badge bg-white bg-opacity-10 text-white">
                                                    #{{ $medicine->medicine_code }}
                                                </span>

                                            </div>

                                            <div class="mt-2 pt-2 border-top border-light border-opacity-10 d-flex justify-content-between small text-muted">

                                                <span>Expiry Date</span>

                                                <span class="fw-semibold text-warning">
                                                    {{ \Carbon\Carbon::parse($medicine->expiry_date)->format('d M Y') }}
                                                </span>

                                            </div>

                                        </div>

                                    @empty

                                        <div class="text-center py-5">

                                            <i class="bi bi-check-circle-fill fs-1 text-success"></i>

                                            <h6 class="mt-3">No Low Stock Medicines</h6>

                                        </div>

                                    @endforelse

                                </div>

                            </div>
                        </div>
                    </div>
                    
                </div>

            </div>
            <div class="glass-card m-4">

                <div class="d-flex justify-content-between mb-3">
                    <h5>Recent Appointments</h5>
                </div>

                <div class="table-responsive">

                    <table class="custom-table">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($appointments as $appointment)

                                <tr>

                                    <td>{{ $loop->iteration }}</td>

                                    <td>{{ $appointment->patient->user->name }}</td>

                                    <td>{{ $appointment->doctor->user->name }}</td>

                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}</td>

                                    <td>
                                        <span class="badge bg-success">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
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

    <!-- ApexCharts JS -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <!-- Activity Report Chart Configuration -->
    {{-- <script>
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                var optionsActivity = {
                    series: [{
                        name: 'Admitted Inpatients',
                        data: [31, 40, 28, 51, 42, 109, 100]
                    }, {
                        name: 'Discharged Outpatients',
                        data: [11, 32, 45, 32, 34, 52, 41]
                    }],
                    chart: {
                        height: 330,
                        type: 'area',
                        background: 'transparent',
                        toolbar: { show: false }
                    },
                    colors: ['#6366f1', '#06b6d4'],
                    theme: {
                        mode: document.documentElement.getAttribute('data-theme') || 'dark'
                    },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 2 },
                    xaxis: {
                        type: 'datetime',
                        categories: ["2026-06-24T00:00:00.000Z", "2026-06-25T01:30:00.000Z", "2026-06-26T02:30:00.000Z", "2026-06-27T03:30:00.000Z", "2026-06-28T04:30:00.000Z", "2026-06-29T05:30:00.000Z", "2026-06-30T06:30:00.000Z"],
                        labels: { style: { colors: '#94a3b8' } }
                    },
                    yaxis: {
                        labels: { style: { colors: '#94a3b8' } }
                    },
                    tooltip: { x: { format: 'dd/MM/yy HH:mm' } },
                    legend: { labels: { colors: '#94a3b8' } },
                    grid: { borderColor: 'rgba(255, 255, 255, 0.05)' }
                };

                var chartActivity = new ApexCharts(document.querySelector("#activity-reports-chart"), optionsActivity);
                chartActivity.render();

                const themeToggle = document.getElementById('theme-toggle');
                if (themeToggle) {
                    themeToggle.addEventListener('click', () => {
                        setTimeout(() => {
                            chartActivity.updateOptions({
                                theme: { mode: document.documentElement.getAttribute('data-theme') }
                            });
                        }, 50);
                    });
                }
            }, 1200);
        });
    </script> --}}
@endsection