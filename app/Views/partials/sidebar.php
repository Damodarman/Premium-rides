<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= site_url('dashboard') ?>" class="brand-link">
        <span class="brand-text font-weight-light">Dashboard</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Manage Vehicles Category -->
                <li class="nav-item has-treeview <?= (url_is('vehicles/*')) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= (url_is('vehicles/*')) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-car"></i>
                        <p>
                            Vehicles
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= site_url('vehicles') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('vehicles')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>All Vehicles</p>
                                <span class="badge badge-info right" id="allVehiclesBadge">0</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('vehicles/assigned') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('vehicles/assigned')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Assigned Vehicles</p>
                                <span class="badge badge-info right" id="operatingVehiclesBadge">0</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('vehicles/unassigned') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('vehicles/unassigned')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Unassigned Vehicles</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('vehicles/out-of-work') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('vehicles/out-of-work')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Out of Work</p>
                                <span class="badge badge-info right" id="nonWorkingVehiclesBadge">0</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('vehicles/malfunctions') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('vehicles/malfunctions')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Malfunctions</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Users Category -->
                <li class="nav-item has-treeview <?= (url_is('users/*')) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= (url_is('users/*')) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Users
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= site_url('users/new') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('users/new')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Add New User</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('users/permissions') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('users/permissions')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Manage Permissions</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('users/delete') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('users/delete')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Delete User</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Drivers Category -->
                <li class="nav-item has-treeview <?= (url_is('driver/*')) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= (url_is('driver/*')) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-id-card"></i>
                        <p>
                            Drivers
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= site_url('driver/index') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('driver/index')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Drivers Dashboard</p>

                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('driver/create') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('driver/create')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Add Driver</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('driver/active') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('driver/active')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Active Drivers</p>
                                <span class="badge badge-info right" id="operatingDriversBadge">0</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('driver/inactive') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('driver/inactive')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Inactive Drivers</p>
                                <span class="badge badge-info right" id="nonWorkingDriversBadge">0</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('driver/vacation') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('driver/vacation')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Drivers on Vacation</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('driver/add-vacation') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('driver/add-vacation')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Add Vacation</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Tasks Category -->
                <li class="nav-item has-treeview <?= (url_is('tasks/*')) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= (url_is('tasks/*')) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>
                            Tasks
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= site_url('tasks/new') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('tasks/new')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Add New Task</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('tasks/ongoing') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('tasks/ongoing')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Ongoing Tasks</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('tasks/completed') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('tasks/completed')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Completed Tasks</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Finance Category -->
                <li class="nav-item has-treeview <?= (url_is('finance/*')) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= (url_is('finance/*')) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-wallet"></i>
                        <p>
                            Finance
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= site_url('finance/overview') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('finance/overview')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Finance Overview</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('finance/income') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('finance/income')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Income</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('finance/expenses') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('finance/expenses')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Expenses</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('finance/profit-loss') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('finance/profit-loss')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Profit & Loss</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- App Reports Category -->
                <li class="nav-item has-treeview <?= (url_is('reports/*')) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= (url_is('reports/*')) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            App Reports
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= site_url('reports/uploadreports') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('reports/uploadreports')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>File import</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('reports/uber') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('reports/uber')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Uber</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('reports/bolt') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('reports/bolt')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Bolt</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('reports/taximetar') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('reports/taximetar')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Taximetar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('reports/myPos') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('reports/myPos')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>MyPos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('reports/uber-activity') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('reports/uber-activity')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Uber Activity</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('reports/bolt-activity') ?>" class="nav-link">
                                <i class="nav-icon <?= (current_url() == site_url('reports/bolt-activity')) ? 'fas fa-circle' : 'far fa-circle' ?>"></i>
                                <p>Bolt Activity</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</aside>
