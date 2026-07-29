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
                        <li class="breadcrumb-item-custom">Manage Users</li>
                    </ul>
                </nav>
                <h4 class="fw-bold mb-0">System User & Role Center</h4>
            </div>
            
            <button class="btn btn-premium d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="bi bi-person-plus-fill"></i> Provision User
            </button>
        </div>

        <!-- SKELETON LOADER -->
        <div class="skeleton-wrapper row g-4 mb-4">
            <div class="col-md-8"><div class="glass-card skeleton" style="height: 400px;"></div></div>
            <div class="col-md-4"><div class="glass-card skeleton" style="height: 400px;"></div></div>
        </div>

        <!-- REAL CONTENT WRAPPER -->
        <div class="real-content-wrapper d-none">
            <div class="row g-4 mb-4">
                @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif


                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                <!-- USER ROSTER -->
                <div class="col-xl-12">
                    <div class="glass-card h-100">
                        <h5 class="mb-4 fw-bold">Active User Directory</h5>
                        
                        <div class="custom-table-container">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>User Profile</th>
                                        <th>System Role</th>
                                        <th>Active Permissions</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ $user->profile_photo_url }}"
                                                    class="rounded-circle"
                                                    style="width:36px;height:36px;object-fit:cover;">
                                                <div>
                                                    <div class="fw-bold">
                                                        {{ $user->name }}
                                                    </div>
                                                    <small class="text-muted">
                                                        {{ $user->email }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                            $badge=[
                                                'super-admin'=>'primary',
                                                'admin'=>'primary',
                                                'doctor'=>'info',
                                                'patient'=>'success',
                                                'receptionist'=>'warning',
                                                'staff'=>'secondary'
                                            ];
                                            @endphp
                                            <span class="badge bg-{{ $badge[$user->role] ?? 'dark' }}">{{ ucfirst($user->role) }}</span>
                                        </td>
                                        <td>
                                            @switch($user->role)
                                            @case('super-admin')
                                                All System Permissions
                                                @break
                                            @case('doctor')
                                                Schedule, Consult, Assigned Patients
                                                @break
                                            @case('patient')
                                                Patient Portal Access
                                                @break
                                            @default
                                                Limited Access
                                            @endswitch
                                        </td>
                                        <td>
                                            @if($user->status == 'active')
                                                <span class="custom-badge badge-success">Active</span>
                                            @else
                                                <span class="custom-badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-premium-outline me-2" onclick='editUser(@json($user))'>
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            {{-- <button type="button" 
                                                class="btn btn-sm btn-premium-outline me-2 editUserBtn"
                                                data-id="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                                data-email="{{ $user->email }}"
                                                data-role="{{ $user->role }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editUserModal">
                                                <i class="bi bi-pencil-square"></i>
                                            </button> --}}
                                            @if($user->role!='super-admin')
                                            <form action="{{ route('super-admin.users.destroy',$user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-premium" onclick="return confirm('Delete this user?')">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                    <td colspan="5" class="text-center py-5">
                                    No Users Found
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

    <!-- MODAL: ADD / PROVISION USER WITH PERMISSIONS CHECKBOXES -->
    <div class="modal fade modal-glass" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-light border-opacity-10">
                    <h5 class="modal-title fw-bold">System Credentials Provisioning</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('super-admin.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                    <div class="modal-body">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label-custom">Staff Full Name</label>
                                <input type="text" name="name" class="form-control form-glass" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Corporate Email Address</label>
                                <input type="email" name="email" class="form-control form-glass" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Password</label>
                                <input type="password" name="password" class="form-control form-glass" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Primary System Role</label>
                                <select name="role" class="form-select form-glass" required>
                                <option value="" disabled selected>Select Role</option>
                                <option value="super-admin">Super Admin</option>
                                <option value="doctor">Doctor</option>
                                <option value="patient">Patient</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Status</label>
                                <select name="status" class="form-select form-glass">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-light border-opacity-10">
                        <button type="button" class="btn btn-premium-outline" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-premium">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade modal-glass" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header border-light border-opacity-10">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2 text-primary"></i>
                        Update User
                    </h5>

                    <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>
                </div>

                <form id="editUserForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-custom">Full Name</label>
                                <input type="text" class="form-control form-glass" id="edit_name" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Email</label>
                                <input type="email" class="form-control form-glass" id="edit_email" name="email" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-custom">Password</label>
                                <input type="password" class="form-control form-glass" id="edit_password" name="password" placeholder="Leave blank to keep current password">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Role</label>

                                <select class="form-select form-glass" id="edit_role" name="role">
                                    <option value="super-admin">Super Admin</option>
                                    <option value="doctor">Doctor</option>
                                    <option value="patient">Patient</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Status</label>
                                <select name="status" id="edit_status" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-light border-opacity-10">
                        <button type="button" class="btn btn-premium-outline" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-premium">
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
    // Edit User
        function editUser(data) {
            document.getElementById('editUserForm').action =
                '/super-admin/users/' + data.id + '/update';
                document.getElementById('edit_name').value = data.name ?? '';
                document.getElementById('edit_email').value = data.email ?? '';
                document.getElementById('edit_role').value = data.role ?? '';
                document.getElementById('edit_status').value = data.status;

            // Password hamesha blank rahega
            document.getElementById('edit_password').value = '';

            let modal = new bootstrap.Modal(document.getElementById('editUserModal'));
            modal.show();
        }

    // Reset Add User Form
    const addModal = document.getElementById('addUserModal');

    if(addModal){

        addModal.addEventListener('hidden.bs.modal', function (){

            document.getElementById('addUserForm').reset();

        });

    }

    // Reset Edit User Form
    const editModal = document.getElementById('editUserModal');

    if(editModal){

        editModal.addEventListener('hidden.bs.modal', function (){

            document.getElementById('editUserForm').reset();

        });

    }

</script>
@endsection    