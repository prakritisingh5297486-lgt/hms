<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        @if(!empty($setting?->logo))
            <img src="{{ asset('uploads/settings/'.$setting->logo) }}"  
                alt="Hospital Logo" width="50" height="50" class="rounded">
        @else
            <div class="rounded d-flex align-items-center justify-content-center"
                style="width:70px;height:70px;background:linear-gradient(135deg,#3b82f6,#06b6d4);">
                <i class="bi bi-heart-pulse-fill text-white fs-2"></i>
            </div>
        @endif
        <span class="sidebar-brand-name">{{$setting->hospital_name}}</span>
    </div>
    
    <ul class="sidebar-menu">
        <li class="menu-item {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
            <a href="{{ route('doctor.dashboard')}}" class="menu-item-link">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>My Dashboard</span>
            </a>
        </li>
        <li class="menu-item  {{ request()->routeIs('doctor.appointments') ? 'active' : '' }}">
            <a href="{{ route('doctor.appointments')}}" class="menu-item-link">
                <i class="bi bi-calendar2-check-fill"></i>
                <span>My Appointments</span>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('doctor.patients') ? 'active' : '' }}">
            <a href="{{ route('doctor.patients')}}" class="menu-item-link">
                <i class="bi bi-people-fill"></i>
                <span>My Patients</span>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('doctor.settings') ? 'active' : '' }}">
            <a href="{{ route('doctor.settings')}}" class="menu-item-link">
                <i class="bi bi-clock-fill"></i>
                <span>My Schedule Shift</span>
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