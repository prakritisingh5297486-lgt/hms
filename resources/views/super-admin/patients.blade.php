@extends('super-admin.layouts.main')
    @section('content')

    <!-- CONTENT BODY -->
    <div class="content-body">
        
        <!-- BREADCRUMB & HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <nav>
                    <ul class="breadcrumb-custom mb-1">
                        <li class="breadcrumb-item-custom"><a href="/super-admin/dashboard">Home</a></li>
                        <li class="breadcrumb-item-custom">Patients</li>
                    </ul>
                </nav>
                <h4 class="fw-bold mb-0">Patient Registry</h4>
            </div>
            
            <button class="btn btn-premium d-flex align-items-center gap-2" onclick="switchTab('register-tab')">
                <i class="bi bi-person-plus-fill"></i> Patient Admission
            </button>
        </div>

        <!-- SKELETON LOADER -->
        <div class="skeleton-wrapper row g-4 mb-4">
            <div class="col-12"><div class="glass-card skeleton" style="height: 400px;"></div></div>
        </div>
        @if(session('success'))
            <div class="alert alert-success bg-success bg-opacity-10 border border-success border-opacity-20 text-success rounded-4 p-3 mb-4">
                <i class="bi bi-check-circle-fill me-2"></i>{{session('success')}}
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger bg-danger bg-opacity-10 border border-danger border-opacity-20 text-danger rounded-4 p-3 mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Please correct the errors below.
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- DYNAMIC CONTENT -->
        <div class="real-content-wrapper">
            
            <!-- PATIENT VIEW NAVIGATION -->
            <ul class="nav nav-tabs border-light border-opacity-10 mb-4" id="patientTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active border-0 bg-transparent px-3 py-2 fw-semibold" id="table-view-tab" data-bs-toggle="tab" data-bs-target="#tab-table-view" type="button" role="tab"><i class="bi bi-list-columns-reverse me-1"></i> Registry Table</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link border-0 bg-transparent px-3 py-2 fw-semibold" id="card-view-tab" data-bs-toggle="tab" data-bs-target="#tab-card-view" type="button" role="tab"><i class="bi bi-grid-fill me-1"></i> Patient Cards</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link border-0 bg-transparent px-3 py-2 fw-semibold" id="register-tab" data-bs-toggle="tab" data-bs-target="#tab-register" type="button" role="tab"><i class="bi bi-person-plus-fill me-1"></i> New Registration</button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- 1. TABLE VIEW PANEL -->
                <div class="tab-pane fade show active" id="tab-table-view" role="tabpanel">
                    <!-- SEARCH & FILTERS -->
                    <form action="{{ route('super-admin.patients') }}" method="GET" class="glass-card mb-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                            <label for="" class="form-label-custom">Search Query</label>
                                <div class="position-relative">
                                    <i class="bi bi-search position-absolute text-muted" style="left: 1rem; top: 50%; transform: translateY(-50%);"></i>
                                    <input type="text" name="search" value="{{request('search')}}" class="form-control form-glass ps-5" placeholder="Search by name, patient ID, blood group...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="" class="form-label-custom">Blood Group</label>
                                <select class="form-select form-glass">
                                    <option value="">Blood Group (All)</option>
                                    <option value="A+" {{request('blood_group')=='A+' ? 'selected' : ''}}>A+</option>
                                    <option value="A-" {{request('blood_group')=='A' ? 'selected' : ''}}>A-</option>
                                    <option value="B+" {{request('blood_group')=='B+' ? 'selected' : ''}}>B+</option>
                                    <option value="AB-" {{request('blood_group')=='B-' ? 'selected' : ''}}>B-</option>
                                    <option value="AB+" {{request('blood_group')=='AB+' ? 'selected' : ''}}>AB+</option>
                                    <option value="AB-" {{request('blood_group')=='AB-' ? 'selected' : ''}}>AB-</option>
                                    <option value="O+" {{request('blood_group')=='O+' ? 'selected' : ''}}>O+</option>
                                    <option value="O-" {{request('blood_group')=='O-' ? 'selected' : ''}}>O-</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex gap-2 align-items-end">
                                <button type="submit" class="btn btn-premium-outline w-100">Apply Filter</button>
                            </div>
                        </div>
                    </form>

                    <div class="glass-card">
                        <div class="custom-table-container">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>Patient ID</th>
                                        <th>Patient Details</th>
                                        <th>Contact & Email</th>
                                        <th>Blood Group</th>
                                        <th>Illness</th>
                                        <th>Registered</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($patients as $pt)
                                    
                                    <tr>
                                        <td><span class="fw-bold">#PT-{{$pt->id}}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{$pt->profile_photo_url}}" alt="Patient" class="rounded-circle border-light border-opacity-10" style="width: 36px; height: 36px; object-fit: cover;">
                                                <div>
                                                    <div class="fw-semibold">{{$pt->user->name ?? 'NA'}}</div>
                                                    <small class="text-muted">{{ucfirst($pt->gender)}}, {{$pt->age}} years</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div><i class="bi bi-telephone me-1 text-muted"></i>{{$pt->number ?? 'NA'}}</div>
                                                <small class="text-muted">{{$pt->user->email}}</small>
                                            </div>
                                        </td>
                                        <td><span class="custom-badge badge-success">{{$pt->blood_group}}</span></td>
                                        <td><span class="custom-badge badge-info">{{$pt->disease ?? 'NA'}}</span></td>
                                        <td><small class="text-muted">{{$pt->created_at ? $pt->created_at->format('Y-m-d') : 'NA'}}</small></td>
                                        <td class="text-end">
                                            <button class="btn btn-premium btn-sm me-1 btn-sm" onclick="viewPatientDetails({{json_encode([
                                                'id'=>$pt->id,
                                                'name'=>$pt->user->name ?? '',
                                                'id'=>$pt->user->email ?? '',
                                                'age'=>$pt->age,
                                                'gender'=>ucfirst($pt->gender),
                                                'blood_group'=>$pt->blood_group,
                                                'number'=>$pt->number,
                                                'disease'=>$pt->disease ?? '',
                                                'address'=>$pt->address ?? '',
                                                'photo'=>$pt->profile_photo_url ?? '',
                                                'createdAt'=>$pt->created_at ? $pt->created_at->format('Y-m-d') : 'NA',
                                            ])}})" title="Access Medical Record"><i class="bi bi-file-earmark-medical me-1"></i>EHR</button>
                                            <button class="btn btn-sm btn-premium me-1"
                                            onclick="editPatient({{json_encode([
                                            'id'=>$pt->id,
                                            'name'=>$pt->user->name ?? '',
                                            'id'=>$pt->user->email ?? '',
                                            'age'=>$pt->age,
                                            'gender'=>strtolower($pt->gender),
                                            'blood_group'=>$pt->blood_group,
                                            'number'=>$pt->number,
                                            'disease'=>$pt->disease ?? '',
                                            'address'=>$pt->address ?? '',
                                            ])}})" title="Edit Patient"><i class="bi bi-pencil-fill"></i></button>
                                            <form action="{{ route('super-admin.patients.destroy', $pt->id) }}" class="d-inline-block" onsubmit="return confirm('Are you sure want to delete this patient record?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-2" title="Delete Patient">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                        
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">No Patients Record Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 2. CARD VIEW PANEL -->
                <div class="tab-pane fade" id="tab-card-view" role="tabpanel">
                    <div class="row g-4">
                        @forelse ($patients as $pt)
                        
                        <div class="col-md-6 col-lg-4 col-xl-4">
                            <div class="glass-card text-center glass-card-hover h-100 d-flex flex-column p-4">

                                <span class="position-absolute top-0 end-0 custom-badge badge-danger">
                                    {{ $pt->blood_group }}
                                </span>

                                <img src="{{ $pt->profile_photo_url }}"
                                    class="rounded-circle mb-3 border border-light border-opacity-10 mx-auto"
                                    style="width:70px;height:70px;object-fit:cover;">

                                <h5 class="fw-bold mb-1">
                                    {{ $pt->user->name ?? 'NA' }}
                                </h5>

                                <p class="text-muted small mb-3">
                                    ID:
                                    <span class="text-primary fw-semibold">
                                        #PT-{{ $pt->id }}
                                    </span>

                                    <i class="bi bi-dot"></i>

                                    {{ $pt->age }} Years

                                    <i class="bi bi-dot"></i>

                                    {{ ucfirst($pt->gender) }}
                                </p>

                                <div class="glass-sub-card p-3 rounded-3 border border-light border-opacity-10 bg-light bg-opacity-10 flex-grow-1 text-start">

                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">
                                            <i class="bi bi-activity text-warning me-1"></i>
                                            Illness
                                        </span>

                                        <span class="fw-semibold text-white">
                                            {{ $pt->disease }}
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">
                                            <i class="bi bi-telephone text-info me-1"></i>
                                            Number
                                        </span>

                                        <span class="fw-semibold text-white">
                                            {{ $pt->number }}
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">
                                            <i class="bi bi-envelope text-primary me-1"></i>
                                            Email
                                        </span>

                                        <span class="fw-semibold text-white text-truncate ms-2">
                                            {{ $pt->user->email }}
                                        </span>
                                    </div>

                                </div>
                            <div class="d-flex align-items-center justify-content-center gap-2 mt-auto">
                                <button class="btn btn-premium btn-sm me-1 btn-sm" onclick="viewPatientDetails({{json_encode([
                                    'id'=>$pt->id,
                                    'name'=>$pt->user->name ?? '',
                                    'id'=>$pt->user->email ?? '',
                                    'age'=>$pt->age,
                                    'gender'=>ucfirst($pt->gender),
                                    'blood_group'=>$pt->blood_group,
                                    'number'=>$pt->number,
                                    'disease'=>$pt->disease ?? '',
                                    'address'=>$pt->address ?? '',
                                    'photo'=>$pt->profile_photo_url ?? '',
                                    'createdAt'=>$pt->created_at ? $pt->created_at->format('Y-m-d') : 'NA',
                                ])}})" title="Access Medical Record"><i class="bi bi-file-earmark-medical me-1"></i>Access Diagnosis</button>
                            </div>
                            <button class="btn btn-sm btn-premium me-1"
                                onclick="editPatient({{json_encode([
                                'id'=>$pt->id,
                                'name'=>$pt->user->name ?? '',
                                'id'=>$pt->user->email ?? '',
                                'age'=>$pt->age,
                                'gender'=>strtolower($pt->gender),
                                'blood_group'=>$pt->blood_group,
                                'number'=>$pt->number,
                                'disease'=>$pt->disease ?? '',
                                'address'=>$pt->address ?? '',
                                ])}})" title="Edit Patient"><i class="bi bi-pencil-fill"></i></button>
                                <form action="{{ route('super-admin.patients.destroy', $pt->id) }}" class="d-inline-block" onsubmit="return confirm('Are you sure want to delete this patient record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-2" title="Delete Patient">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>   
                        </div>
                        @empty
                            <div class="col-12">
                                <div class="glass-card text-center p-4">
                                    <i class="bi bi-exclamation-triangle-fill text-warning mb-2" style="font-size: 2rem;"></i>
                                    <h6 class="fw-semibold mb-1">No Patient Record Found</h6>
                                    <p class="text-muted small mb-0">Please check back later or add new patient records.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- 3. NEW REGISTRATION FORM -->
                <div class="tab-pane fade" id="tab-register" role="tabpanel">
                    <div class="glass-card">
                        <h5 class="fw-bold mb-4"><i class="bi bi-person-plus-fill me-2 text-primary"></i>Patient Admission & Intake Form</h5>
                        <form action="{{ route('super-admin.patients.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label-custom">Profile Photo</label>
                                    <input type="file" name="profile" class="form-control form-glass" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">Full Name</label>
                                    <input type="text" name="name" class="form-control form-glass" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">Email</label>
                                    <input type="text" name="email" class="form-control form-glass" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">Password</label>
                                    <input type="password" name="password" class="form-control form-glass" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">Age</label>
                                    <input type="number" name="age" class="form-control form-glass" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label-custom">Gender</label>
                                    <select class="form-select form-glass" name="gender" required>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label-custom">Blood Group</label>
                                    <select name="blood_group" class="form-select form-glass" required>
                                        <option value="" selected disabled>Select Blood Group</option>
                                        <option value="A+"></option>
                                        <option value="A-"></option>
                                        <option value="B+"></option>
                                        <option value="AB-"></option>
                                        <option value="AB+"></option>
                                        <option value="AB-"></option>
                                        <option value="O+"></option>
                                        <option value="O-"></option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">Contact Number</label>
                                    <input type="tel" name="number" class="form-control form-glass" placeholder="Enter Contact Number" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">Disease</label>
                                    <input type="text" name="disease" class="form-control form-glass" placeholder="Enter Disease">
                                </div>
                                <div class="col-12">
                                    <label class="form-label-custom">Residential Address</label>
                                    <textarea class="form-control form-glass" name="address" rows="4" placeholder="Brief explanation of current patient diagnoses..."></textarea>
                                </div>
                            </div>
                            <div class="mt-4 pt-3 border-top border-light border-opacity-10 d-flex justify-content-end gap-2">
                                <button type="reset" class="btn btn-premium-outline">Reset Form</button>
                                <button type="submit" class="btn btn-premium">Complete Admission</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- MODAL: PATIENT FILES & DETAILS -->
    <div class="modal fade modal-glass" id="patientDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header border-light border-opacity-10">
                    <h5 class="modal-title fw-bold">Electronic Health Record (EHR) - Eleanor Vance</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <!-- Profile Summary & Documents -->
                        <div class="col-lg-4 border-end border-light border-opacity-10 pe-lg-4">
                            <div class="text-center mb-4">
                                <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&q=80&w=150" alt="Patient" class="rounded-circle mb-3 border border-light border-opacity-10" style="width: 110px; height: 110px; object-fit: cover;">
                                <h5 class="fw-bold mb-1">Eleanor Vance</h5>
                                <span class="custom-badge badge-success mb-2">Age: 28 • Female</span>
                            </div>
                            
                            <h6 class="fw-bold text-primary mb-3">Patient Documents</h6>
                            <div class="d-flex flex-column gap-2 mb-4">
                                <div class="p-2 border border-light border-opacity-10 rounded-3 d-flex justify-content-between align-items-center glass-sub-card">
                                    <span style="font-size: 0.85rem;"><i class="bi bi-file-earmark-pdf-fill text-danger me-2"></i>Blood_Count_Report.pdf</span>
                                    <button class="btn btn-sm text-info p-0" onclick="showToast('Downloading Document', 'Blood_Count_Report.pdf dispatched.', 'success')"><i class="bi bi-download"></i></button>
                                </div>
                                <div class="p-2 border border-light border-opacity-10 rounded-3 d-flex justify-content-between align-items-center glass-sub-card">
                                    <span style="font-size: 0.85rem;"><i class="bi bi-file-earmark-pdf-fill text-danger me-2"></i>Chest_XRay_Scan.pdf</span>
                                    <button class="btn btn-sm text-info p-0" onclick="showToast('Downloading Document', 'Chest_XRay_Scan.pdf dispatched.', 'success')"><i class="bi bi-download"></i></button>
                                </div>
                            </div>

                            <button class="btn btn-premium-outline btn-sm w-100" onclick="showToast('Upload Registry', 'Select files to push.', 'info')">
                                <i class="bi bi-cloud-arrow-up-fill"></i> Upload New Document
                            </button>
                        </div>

                        <!-- Timeline & Clinical Notes -->
                        <div class="col-lg-8 ps-lg-4">
                            <h6 class="fw-bold text-primary mb-4">EHR Medical Timeline & History</h6>
                            <div class="timeline-custom">
                                <div class="timeline-item success">
                                    <div class="fw-semibold">Pathology Lab Blood Analysis (CBC)</div>
                                    <small class="text-muted d-block mb-1">Uploaded by Chief Pathologist Dr. Sarah Connor</small>
                                    <span class="badge bg-success bg-opacity-10 text-success mb-2">Ref Range: Normal</span>
                                    <p class="text-muted small">Hemoglobin, Platelets and WBC count found within typical reference levels.</p>
                                    <span style="font-size: 0.75rem; color: var(--text-muted);">2026-06-28</span>
                                </div>
                                <div class="timeline-item info">
                                    <div class="fw-semibold">Cardiology OPD Consultation</div>
                                    <small class="text-muted d-block mb-1">Assigned Medical Officer: Dr. Sarah Connor</small>
                                    <p class="text-muted small">Patient presented with mild heart palpitation episodes. EKG trace completed, showing no major conduction discrepancies. Prescribed Ivabradine.</p>
                                    <span style="font-size: 0.75rem; color: var(--text-muted);">2026-06-25</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-light border-opacity-10">
                    <button class="btn btn-premium btn-sm" data-bs-dismiss="modal">Close EHR Record</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabId) {
            const triggerEl = document.getElementById(tabId);
            if (triggerEl) {
                bootstrap.Tab.getOrCreateInstance(triggerEl).show();
            }
        }
        function editPatient(data) {
                document.getElementById('editPatientForm').action = '/super-admin/patients/' + data.id + '/update';
                document.getElementById('edit_name').value = data.name;
                document.getElementById('edit_email').value = data.email;
                document.getElementById('edit_age').value = data.age;
                document.getElementById('edit_gender').value = data.gender;
                document.getElementById('edit_blood_group').value = data.blood_group;
                document.getElementById('edit_number').value = data.number;
                document.getElementById('edit_disease').value = data.disease;
                document.getElementById('edit_address').value = data.address;

                var modal = new bootstrap.Modal(document.getElementById('editPatientModal'));
                modal.show();
            }

        function viewPatientDetails(data) {
            document.getElementById('detail_photo').src = data.photo;
            document.getElementById('detail_name').innerText = data.name;
            document.getElementById('detail_id').innerText = '#PT-' + data.id;
            document.getElementById('detail_blood_group').innerText = 'Blood Group: ' + data.blood_group;
            document.getElementById('detail_age_gender').innerText = data.age + ' Years (' + data.gender + ')';
            document.getElementById('detail_number').innerText = data.number || 'N/A';
            document.getElementById('detail_email').innerText = data.email || 'N/A';
            document.getElementById('detail_created_at').innerText = data.created_at || 'N/A';
            document.getElementById('detail_disease').innerText = data.disease || 'General Checkup';
            document.getElementById('detail_address').innerText = data.address || 'No residential address recorded.';

            var modal = new bootstrap.Modal(document.getElementById('patientDetailsModal'));
            modal.show();
        }
    </script>
    

@endsection