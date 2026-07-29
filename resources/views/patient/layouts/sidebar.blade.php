<aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <div class="sidebar-logo">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>
                <span class="sidebar-brand-name">AuraHMS</span>
            </div>
            
            <ul class="sidebar-menu">
                <li class="menu-item {{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">
                    <a href="{{route('patient.dashboard')}}" class="menu-item-link">
                        <i class="bi bi-person-workspace"></i>
                        <span>Patient Portal</span>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('patient.appointments') ? 'active' : '' }}">
                    <a href="{{ route('patient.appointments')}}" class="menu-item-link">
                        <i class="bi bi-calendar-event"></i>
                        <span>My Appointments</span>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('patient.records') ? 'active' : '' }}">
                    <a href="{{ route('patient.records')}}" class="menu-item-link">
                        <i class="bi bi-clipboard2-pulse"></i>
                        <span>Medical Records</span>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('patient.billing') ? 'active' : '' }}">
                    <a href="{{ route('patient.billing')}}" class="menu-item-link">
                        <i class="bi bi-credit-card"></i>
                        <span>Bills & Payments</span>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('patient.settings') ? 'active' : '' }}">
                    <a href="{{ route('patient.settings')}}" class="menu-item-link">
                        <i class="bi bi-sliders"></i>
                        <span>My Settings</span>
                    </a>
                </li>
                <div class="glass-card p-3 mt-3 rounded-4">

                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-telephone-fill me-2"></i>
                        <small>{{ $setting->phone ?? 'Not Available' }}</small>
                    </div>

                    <div class="d-flex align-items-start">
                        <i class="bi bi-geo-alt-fill me-2 mt-1"></i>
                        <small>{{ $setting->address ?? 'Address Not Available' }}</small>
                    </div>

                </div>
                <li class="menu-item mt-auto">
                    <a href="{{ route('logout')}}" class="menu-item-link text-danger">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </aside>