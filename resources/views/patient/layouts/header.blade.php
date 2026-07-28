<header class="top-navbar">
                <div class="navbar-left">
                    <button class="sidebar-toggle-btn" id="sidebar-toggle">
                        <i class="bi bi-justify"></i>
                    </button>
                    <span class="navbar-brand-name ms-2 d-none d-md-inline-block text-primary fw-bold">
                        @if(Route::currentRouteNamed('patient.dashboard'))
                            Dashboard
                        @elseif(Route::currentRouteNamed('patient.appointments'))
                            My Appointments
                        @elseif(Route::currentRouteNamed('patient.billing'))
                            Billing & Payments
                        @elseif(Route::currentRouteNamed('patient.settings'))
                            My Settings
                        @elseif(Route::currentRouteNamed('patient.records'))
                            Medical Records
                        @else
                            Patient Panel
                        @endif
                    </span>
                </div>
                
                <div class="navbar-right">
                    <button class="nav-icon-btn" id="theme-toggle">
                        <i class="bi bi-moon-stars-fill"></i>
                    </button>
                    <div class="dropdown">
                        <div class="profile-dropdown-trigger" data-bs-toggle="dropdown">
                            {{-- @if(Auth::check() && Auth::user()->image)
                                <img src="{{ asset('patients/' . Auth::user()->image) }}" alt="Patient" class="profile-avatar">
                            @else
                                <img src="{{ asset('images/default-user.png') }}" alt="Default Patient" class="profile-avatar">
                            @endif --}}
                            @if(Auth::check() && Auth::user()->patient->profile)
                                <img src="{{ asset(Auth::user()->patient->profile) }}" alt="Patient" class="profile-avatar rounded-circle border border-light border-opacity-20">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{Auth::user()->name}}background=0D8ABC&color=fff" alt="patient" class="profile-avatar">
                            @endif
                            {{-- https://ui-avatars.com/api/?background=0D8ABC&color=fff --}}
                            {{-- or --}}
                            {{-- @else
                                <img src="https://ui-avatars.com/api/?name={{Auth::user()->name}}background=0D8ABC&color=fff" alt="patient" class="profile-avatar">
                            @endif --}}

                            <div class="profile-info d-none d-lg-flex">
                                <span class="profile-name">{{ Auth::user()->name }}</span>
                                <span class="profile-role">ID: #PT-{{ Auth::user()->id }}</span>
                            </div>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-glass">
                            <li><a class="dropdown-item dropdown-item-glass" href="{{route('patient.settings')}}"><i class="bi bi-person-badge me-2"></i>My Profile</a></li>
                            <li><a class="dropdown-item dropdown-item-glass" href="{{route('patient.settings')}}"><i class="bi bi-shield-lock me-2"></i>Security Settings</a></li>
                            <li><hr class="dropdown-divider border-light border-opacity-10"></li>
                            <li><a class="dropdown-item dropdown-item-glass text-danger" href="{{route('logout')}}"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </header>