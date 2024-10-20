<!-- app/Views/partials/navbar.php -->

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <!-- Sidebar toggle button -->
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        
        <!-- Dashboard Link (for quick access) -->
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= site_url('dashboard') ?>" class="nav-link">Dashboard</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end" aria-labelledby="notificationsDropdown">
                <li><span class="dropdown-header">15 Notifications</span></li>
                <li>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> 4 new messages
                        <span class="float-right text-muted text-sm">3 mins</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-users mr-2"></i> 8 friend requests
                        <span class="float-right text-muted text-sm">12 hours</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-file mr-2"></i> 3 new reports
                        <span class="float-right text-muted text-sm">2 days</span>
                    </a>
                </li>
                <li class="dropdown-divider"></li>
                <li><a href="#" class="dropdown-item dropdown-footer">See All Notifications</a></li>
            </ul>
        </li>

        <!-- User Account Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user"></i>
                <span class="ml-1">User</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a href="<?= site_url('profile') ?>" class="dropdown-item"><i class="fas fa-user-circle mr-2"></i> Profile</a></li>
                <li><a href="<?= site_url('settings') ?>" class="dropdown-item"><i class="fas fa-cog mr-2"></i> Settings</a></li>
                <li class="dropdown-divider"></li>
                <li><a href="<?= site_url('logout') ?>" class="dropdown-item"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>
