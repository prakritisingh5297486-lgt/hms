@extends('super-admin.layouts.main')
@section('content')
    <style>
        .error-preview-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: var(--body-bg);
            z-index: 1060;
            display: none;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
            animation: fadeIn 0.3s ease forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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
                        <li class="breadcrumb-item-custom">Settings</li>
                    </ul>
                </nav>
                <h4 class="fw-bold mb-0">System Control Centre</h4>
            </div>
        </div>

        <!-- SKELETON LOADER -->
        <div class="skeleton-wrapper row g-4 mb-4">
            <div class="col-12"><div class="glass-card skeleton" style="height: 450px;"></div></div>
        </div>

        <!-- REAL CONTENT WRAPPER -->
        <div class="real-content-wrapper d-none">
            
            <div class="row g-4">
                <div class="col-12">
                    <div class="glass-card">
                        <ul class="nav nav-tabs border-light border-opacity-10 mb-4" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active border-0 bg-transparent px-3 py-2 fw-semibold" id="branding-tab" data-bs-toggle="tab" data-bs-target="#tab-branding" type="button" role="tab"><i class="bi bi-patch-check"></i> Clinic Profile</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link border-0 bg-transparent px-3 py-2 fw-semibold" id="admin-tab" data-bs-toggle="tab" data-bs-target="#tab-admin" type="button" role="tab"><i class="bi bi-person-badge"></i> Admin Profile</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link border-0 bg-transparent px-3 py-2 fw-semibold" id="smtp-tab" data-bs-toggle="tab" data-bs-target="#tab-smtp" type="button" role="tab"><i class="bi bi-envelope-at"></i> Mail Server (SMTP)</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link border-0 bg-transparent px-3 py-2 fw-semibold" id="security-tab" data-bs-toggle="tab" data-bs-target="#tab-security" type="button" role="tab"><i class="bi bi-shield-lock"></i> Staff Credentials</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link border-0 bg-transparent px-3 py-2 fw-semibold" id="backups-tab" data-bs-toggle="tab" data-bs-target="#tab-backups" type="button" role="tab"><i class="bi bi-database-up"></i> DB Backups</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link border-0 bg-transparent px-3 py-2 fw-semibold" id="errors-tab" data-bs-toggle="tab" data-bs-target="#tab-errors" type="button" role="tab"><i class="bi bi-exclamation-octagon"></i> HTTP Error Previews</button>
                            </li>
                        </ul>
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        {{-- Validation Error --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>

                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        <div class="tab-content">
                            <!-- 1. CLINIC PROFILE -->
                            <div class="tab-pane fade show active" id="tab-branding" role="tabpanel">
                                <form action="{{ route('super-admin.settings.update') }}" enctype="multipart/form-data" method="POST">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Hospital Legal Name</label>
                                            <input type="text" name="hospital_name" class="form-control form-glass" value="{{old('hospital_name',$setting->hospital_name ?? '')}}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Hospital Phone Registry</label>
                                            <input type="tel" name="phone" class="form-control form-glass" value="{{ old('phone', $setting->phone ?? '') }}">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label-custom">Hospital Clinic Location Address</label>
                                            <textarea class="form-control form-glass" rows="2"name="address">{{ old('address', $setting->address ?? '') }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Upload Logo</label>
                                            <input type="file" name="logo" class="form-control form-glass">
                                            @if(!empty($setting->logo))
                                                <img src="{{ asset('uploads/settings/'.$setting->logo) }}" width="100">
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Upload Favicon ICO</label>
                                            <input type="file" name="favicon" class="form-control form-glass">
                                            @if(!empty($setting->favicon))
                                                <img src="{{ asset('uploads/settings/'.$setting->favicon) }}" width="40">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-3 border-top border-light border-opacity-10 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-premium">Save Clinic Profile</button>
                                    </div>
                                </form>
                            </div>
                            <!-- ADMIN PROFILE -->
                            <div class="tab-pane fade show" id="tab-admin" role="tabpanel">
                                <form action="{{ route('super-admin.profile.update') }}" enctype="multipart/form-data" method="POST">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Name</label>
                                            <input type="text" 
                                                name="name" 
                                                class="form-control form-glass" 
                                                value="{{ old('name', Auth::user()->name) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <img src="{{ Auth::user()->image ? asset('super-admin/'.Auth::user()->image)
                                            : asset('images/default-user.png') }}" alt="Admin" class="profile-avatar">
                                            <label class="form-label-custom">Upload Profile Picture</label>
                                            <input type="file" name="image" class="form-control form-glass">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Role</label>
                                            <input type="text" class="form-control form-glass" value="{{Auth::user()->role}}" readonly>
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-3 border-top border-light border-opacity-10 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-premium">
                                            Save Admin Profile
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- 2. SMTP MAIL SERVER -->
                            <div class="tab-pane fade" id="tab-smtp" role="tabpanel">
                                <form action="{{route('super-admin.settings.smtp')}}" method="POST">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Mail Driver Protocol</label>
                                            <input type="text" name="mail_mailer" class="form-control form-glass"  value="{{ old('mail_mailer',$setting->mail_mailer ?? 'smtp') }}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label-custom">SMTP Outgoing Host</label>
                                            <input type="text" name="mail_host" class="form-control form-glass" value="{{ old('mail_host',$setting->mail_host ?? '') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label-custom">SMTP Port</label>
                                            <input type="number" name="mail_port" class="form-control form-glass" value="{{ old('mail_port',$setting->mail_port ?? '') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label-custom">Encryption protocol</label>
                                            <select name="mail_encryption" class="form-select form-glass">
                                                <option value="tls" {{ ($setting->mail_encryption ?? '')=='tls' ? 'selected':'' }}>TLS/STARTTLS (Secured)</option>
                                                <option value="ssl" {{ ($setting->mail_encryption ?? '')=='ssl' ? 'selected':'' }}>SSL (Legacy)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label-custom">Authentication User</label>
                                            <input type="text" name="mail_username" class="form-control form-glass" value="{{ old('mail_username',$setting->mail_username ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-3 border-top border-light border-opacity-10 d-flex justify-content-end gap-2">
                                        {{-- <button type="button" class="btn btn-premium-outline">Send Test Connection</button> --}}
                                        <button type="submit" class="btn btn-premium">Apply SMTP</button>
                                    </div>
                                </form>
                            </div>

                            <!-- 3. STAFF CREDENTIALS -->
                            <div class="tab-pane fade" id="tab-security" role="tabpanel">
                                <form method="POST" action="{{ route('super-admin.settings.security')}}">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Email Profile Address</label>
                                            <input type="email" name="email" class="form-control form-glass" value="{{ old('email',auth()->user()->email) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Access PIN / License Code</label>
                                            <input type="text" class="form-control form-glass" value="MC-9428" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label-custom">Current Password</label>
                                            <input type="password" name="current_password" value="{{old('password',auth()->user()->password)}}" class="form-control form-glass" placeholder="••••••••">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label-custom">Configure New Password</label>
                                            <input type="password" name="password" class="form-control form-glass" placeholder="••••••••">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label-custom">Verify Password Change</label>
                                            <input type="password" name="password_confirmation" class="form-control form-glass" placeholder="••••••••">
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-3 border-top border-light border-opacity-10 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-premium">Update Access Profile</button>
                                    </div>
                                </form>
                            </div>

                            <!-- 4. DB BACKUPS -->
                            <div class="tab-pane fade" id="tab-backups" role="tabpanel">

                                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                                    <div>
                                        <h6 class="fw-bold mb-1">AuraHMS Backup Matrix</h6>
                                        <small class="text-muted">
                                            Generate and manage database backups.
                                        </small>
                                    </div>

                                    <form action="{{ route('super-admin.settings.backup') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-premium btn-sm">
                                            <i class="bi bi-cloud-arrow-up"></i>
                                            Generate Backup Now
                                        </button>
                                    </form>

                                </div>

                                <div class="custom-table-container">
                                    <table class="custom-table">

                                        <thead>
                                            <tr>
                                                <th>Backup File</th>
                                                <th>File Size</th>
                                                <th>Status</th>
                                                <th>Created At</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @forelse($backups as $backup)

                                                <tr>

                                                    <td>{{ $backup->file_name }}</td>

                                                    <td>{{ $backup->file_size }}</td>

                                                    <td>
                                                        @if($backup->status=='Completed')

                                                            <span class="custom-badge badge-success">
                                                                Completed
                                                            </span>

                                                        @else

                                                            <span class="custom-badge badge-danger">
                                                                Failed
                                                            </span>

                                                        @endif
                                                    </td>

                                                    <td>
                                                        {{ $backup->created_at->format('d M Y h:i A') }}
                                                    </td>

                                                    <td class="text-end">

                                                        <form action="{{ route('super-admin.settings.backup.restore',$backup->id) }}"
                                                            method="POST"
                                                            class="d-inline">
                                                            @csrf

                                                            <button class="btn btn-sm btn-premium-outline">
                                                                <i class="bi bi-clock-history"></i>
                                                                Restore
                                                            </button>
                                                        </form>

                                                        <a href="{{ route('super-admin.settings.backup.download',$backup->id) }}"
                                                        class="btn btn-sm btn-premium">

                                                            <i class="bi bi-download"></i>
                                                            Download

                                                        </a>

                                                    </td>

                                                </tr>

                                            @empty

                                                <tr>

                                                    <td colspan="5" class="text-center py-4">
                                                        No Backup Available
                                                    </td>

                                                </tr>

                                            @endforelse

                                        </tbody>

                                    </table>
                                </div>

                            </div>

                            <!-- 5. HTTP ERROR PREVIEWS -->
                            <div class="tab-pane fade" id="tab-errors" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <button class="btn btn-premium-outline w-100" onclick="triggerErrorPreview('error-403')">403 Forbidden</button>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-premium-outline w-100" onclick="triggerErrorPreview('error-404')">404 Not Found</button>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-premium-outline w-100" onclick="triggerErrorPreview('error-500')">500 Server Error</button>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-premium-outline w-100" onclick="triggerErrorPreview('error-maint')">Maintenance Mode</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- ERROR PREVIEWS FULLSCREEN OVERLAYS -->
    <!-- 403 Forbidden -->
    <div class="error-preview-overlay" id="error-403">
        <div>
            <i class="bi bi-shield-slash-fill text-danger mb-4" style="font-size: 5rem; display: block;"></i>
            <h1 class="fw-bold mb-2">403 Forbidden</h1>
            <p class="text-muted mb-4 mx-auto" style="max-width: 500px;">Access Violation. The requested action requires senior supervisor clearance permissions.</p>
            <button class="btn btn-premium" onclick="closeErrorPreview('error-403')">Return to System Settings</button>
        </div>
    </div>

    <!-- 404 Not Found -->
    <div class="error-preview-overlay" id="error-404">
        <div>
            <i class="bi bi-search text-warning mb-4" style="font-size: 5rem; display: block;"></i>
            <h1 class="fw-bold mb-2">404 Patient Record Missing</h1>
            <p class="text-muted mb-4 mx-auto" style="max-width: 500px;">We couldn't resolve the directory query reference ID you were searching for.</p>
            <button class="btn btn-premium" onclick="closeErrorPreview('error-404')">Return to System Settings</button>
        </div>
    </div>

    <!-- 500 Server Error -->
    <div class="error-preview-overlay" id="error-500">
        <div>
            <i class="bi bi-bug-fill text-danger mb-4" style="font-size: 5rem; display: block;"></i>
            <h1 class="fw-bold mb-2">500 Server Crash</h1>
            <p class="text-muted mb-4 mx-auto" style="max-width: 500px;">Internal connection timeout. The SQL cluster failed to respond to the clinical query thread.</p>
            <button class="btn btn-premium" onclick="closeErrorPreview('error-500')">Return to System Settings</button>
        </div>
    </div>

    <!-- Maintenance Mode -->
    <div class="error-preview-overlay" id="error-maint">
        <div>
            <i class="bi bi-tools text-info mb-4" style="font-size: 5rem; display: block;"></i>
            <h1 class="fw-bold mb-2">AuraHMS System Upgrade</h1>
            <p class="text-muted mb-4 mx-auto" style="max-width: 500px;">The administration portal is temporarily under scheduled backup sequence optimization.</p>
            <button class="btn btn-premium" onclick="closeErrorPreview('error-maint')">Return to System Settings</button>
        </div>
    </div>

    <script>
        function triggerErrorPreview(overlayId) {
            const overlay = document.getElementById(overlayId);
            if (overlay) {
                overlay.style.display = 'flex';
            }
        }
        function closeErrorPreview(overlayId) {
            const overlay = document.getElementById(overlayId);
            if (overlay) {
                overlay.style.display = 'none';
            }
        }
    </script>

@endsection