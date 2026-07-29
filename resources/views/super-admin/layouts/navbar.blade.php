<header class="top-navbar">
    <div class="navbar-left">
        <button class="sidebar-toggle-btn" id="sidebar-toggle">
            <i class="bi bi-justify"></i>
        </button>
        <div class="global-search-container d-none d-md-block">
            <span>
                Welcome, {{ Auth::user()->name }}
                <i class="bi bi-emoji-smile-fill text-warning"></i>
            </span>
        </div>
    </div>
    
    <div class="navbar-right">
        <button class="nav-icon-btn" id="theme-toggle">
            <i class="bi bi-moon-stars-fill"></i>
        </button>
        <div class="dropdown">
            <div class="profile-dropdown-trigger" data-bs-toggle="dropdown">
                <img src="{{ Auth::user()->image ? asset('super-admin/'.Auth::user()->image)
                : asset('images/default-user.png') }}" 
                alt="Admin" 
                class="profile-avatar">
                <div class="profile-info d-none d-lg-flex">
                    <span class="profile-name">{{ Auth::user()->name }}</span>
                    <span class="profile-role">{{ Auth::user()->role }}</span>
                </div>
            </div>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-glass">
                <li><a class="dropdown-item dropdown-item-glass" href="{{route('super-admin.settings')}}"><i class="bi bi-person-fill me-2"></i>My Profile</a></li>
                <li><a class="dropdown-item dropdown-item-glass" href="{{route('super-admin.settings')}}"><i class="bi bi-gear-fill me-2"></i>System Settings</a></li>
                <li><hr class="dropdown-divider border-light border-opacity-10"></li>
                <li><a class="dropdown-item dropdown-item-glass text-danger" href="{{route('logout')}}"><i class="bi bi-box-arrow-right me-2"></i>Sign Out</a></li>
            </ul>
        </div>
    </div>
</header>