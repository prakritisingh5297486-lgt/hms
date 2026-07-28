@extends('doctor.layouts.main')

@section('content')
    

            <!-- CONTENT BODY -->
            <div class="content-body">
                
                <!-- BREADCRUMB & HEADER -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <nav>
                            <ul class="breadcrumb-custom mb-1">
                                <li class="breadcrumb-item-custom"><a href="/doctor/dashboard">Home</a></li>
                                <li class="breadcrumb-item-custom">Settings</li>
                            </ul>
                        </nav>
                        <h4 class="fw-bold mb-0">Shift & Profile Configurations</h4>
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
                                        <button class="nav-link active border-0 bg-transparent px-3 py-2 fw-semibold" id="profile-tab" data-bs-toggle="tab" data-bs-target="#tab-profile" type="button" role="tab"><i class="bi bi-person-badge"></i> Practitioner Profile</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link border-0 bg-transparent px-3 py-2 fw-semibold" id="shift-tab" data-bs-toggle="tab" data-bs-target="#tab-shift" type="button" role="tab"><i class="bi bi-clock-history"></i> Weekly Availability Shifts</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link border-0 bg-transparent px-3 py-2 fw-semibold" id="sec-tab" data-bs-toggle="tab" data-bs-target="#tab-sec" type="button" role="tab"><i class="bi bi-shield-lock"></i> Passphrase Security</button>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <!-- 1. PRACTITIONER PROFILE -->
                                    <div class="tab-pane fade show active" id="tab-profile" role="tabpanel">
                                        <form action="{{url('doctor/settings/profile')}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label-custom">Practitioner Legal Name</label>
                                                    <input type="text" class="form-control form-glass" value="{{$user->name}}" name="name" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label-custom">Specialty Department Division</label>
                                                    <input type="text" class="form-control form-glass" name="department" value="{{$doctor->department}}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label-custom">Medical License Reference ID</label>
                                                    <input type="text" class="form-control form-glass" name="license_id" value="{{$doctor->license_id}}">
                                                </div>
                                                 <div class="col-md-6 mb-3">
                                                    <label class="form-label-custom">Department</label>
                                                    <select name="department" class="form-select form-glass" id="registerDept" required>
                                                        <option value="">Select Division</option>
                                                        <option value="cardiology" {{old('department',$doctor->department)==='cardiology'?'selected':''}}>Cardiology</option>
                                                        <option value="neurology" {{old('department',$doctor->department)==='neurology'?'selected':''}}>Neurology</option>
                                                        <option value="pediatrics" {{old('department',$doctor->department)==='pediatrics'?'selected':''}}>Pediatrics</option>
                                                        <option value="general" {{old('department',$doctor->department)==='cardiology'?'general':''}}>General Medicine</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">

                                                    <label class="form-label-custom">Profile Picture</label>
                                                    @if($doctor->profile_photo)
                                                        <img src="{{ asset('doctors/profile/'.$doctor->profile_photo) }}" alt="doctor" class="profile-avatar rounded-circle border border-light border-opacity-20">
                                                    @else
                                                        <img src="https://ui-avatars.com/api/?name={{$user->name}}background=0D8ABC&color=fff" alt="doctor" class="profile-avatar">
                                                    @endif
                                                    {{-- <img height="50" src="{{asset(Auth::user()->patient->profile)}}" alt=""><br> --}}
                                                    <input type="file" class="form-control form-glass" name="profile_photo">

                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label-custom">Professional Bio / Resume Statement</label>
                                                    <textarea class="form-control form-glass" rows="4" name="bio">{{$doctor->bio}}</textarea>
                                                </div>
                                            </div>
                                            <div class="mt-4 pt-3 border-top border-light border-opacity-10 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-premium">Update Profile Details</button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- 2. WEEKLY AVAILABILITY SHIFTS -->
                                    <div class="tab-pane fade" id="tab-shift" role="tabpanel">
                                        <form action="{{ route('doctor.settings.shifts')}}" method="post">
                                            @csrf
                                            <div class="row g-3">
                                                <div class="col-md-12">
                                                    <label class="form-label-custom d-block">OPD Consult Days Availability</label>
                                                    <div class="d-flex gap-3 flex-wrap my-2">
                                                        @php
                                                            $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                                                        @endphp
                                                        @foreach($days as $day)    
                                                        
                                                        <div class="form-check form-check-custom">
                                                            <input class="form-check-input" type="checkbox" name="available_days[]" value="{{$day}}" {{in_array($day,$doctor->available_days ?? []) ? 'checked':''}} id="day{{$day}}">
                                                            <label class="form-check-label text-white" for="day{{$day}}">{{$day}}</label>
                                                        </div>

                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label-custom">Shift Start Time</label>
                                                    <input type="time" class="form-control form-glass" name="start_time" value="{{$doctor->start_time}}" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label-custom">Shift Close Time</label>
                                                    <input type="time" class="form-control form-glass" name="end_time" value="{{$doctor->end_time}}" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label-custom">Consult Session Fee ($)</label>
                                                    <input type="number" class="form-control form-glass" name="consultation_fee" value="{{$doctor->consultation_fee}}" required>
                                                </div>
                                            </div>
                                            <div class="mt-4 pt-3 border-top border-light border-opacity-10 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-premium">Save Shift Availability</button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- 3. PASSPHRASE SECURITY -->
                                    <div class="tab-pane fade" id="tab-sec" role="tabpanel">
                                        <form action="{{route('doctor.settings.security')}}" method="POST">
                                            @csrf
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label-custom">Doctor Portal Username Login</label>
                                                    <input type="text" class="form-control form-glass" name="email" value="{{$user->email}}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label-custom">Current Access Password</label>
                                                    <input type="password" class="form-control form-glass" name="current_password" placeholder="••••••••" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label-custom">New Access Password</label>
                                                    <input type="password" class="form-control form-glass" name="new_password" placeholder="••••••••" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label-custom">Verify New Password</label>
                                                    <input type="password" class="form-control form-glass" name="confirm_password" placeholder="••••••••" required>
                                                </div>
                                            </div>
                                            <div class="mt-4 pt-3 border-top border-light border-opacity-10 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-premium">Change Password</button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <script src="{{ asset('js/script.js') }}"></script>

        <!-- Session Toasts -->
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    showToast('Success', "{{ session('success') }}", 'success');
                });
            </script>
        @endif
        @if($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    showToast('Error', "{{ $errors->first() }}", 'danger');
                });
            </script>
        @endif
@endsection