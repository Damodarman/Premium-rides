<div class="container-fluid bg-dark">
    <nav class="navbar navbar-expand-lg bg-body-dark">
        <div class="container-fluid">
            <a class="navbar-brand fs-5" href="<?php echo site_url('profile') ?>"><?php echo $fleet; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="bi bi-list mobile-nav-toggle"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <li><a class="nav-link fs-5" href="<?php echo site_url('tasks') ?>">Zadaci</a></li>
                    <?php if ($role == 'admin'): ?>
                        <li><a class="nav-link fs-5" href="<?php echo site_url('uberImport') ?>">Izvještaji</a></li>
                    <?php endif ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fs-5" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Vozači
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo site_url('drivers') ?>">Popis</a></li>
                            <?php if ($role != 'knjigovoda'): ?>
                                <li><a class="dropdown-item" href="<?php echo site_url('addDriver') ?>">Dodaj</a></li>
                            <?php endif ?>
                            <li><a class="dropdown-item" href="<?php echo site_url('napomene') ?>">Napomene</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo site_url('aktivniDanas') ?>">Aktivni sada</a></li>
                            <li><a class="dropdown-item" href="<?php echo site_url('prijaveRadnika') ?>">Prijave</a></li>
                            <?php if ($role != 'knjigovoda'): ?>
                                <li><a class="dropdown-item" href="<?php echo site_url('vozila') ?>">Vozila</a></li>
                            <?php endif ?>
                        </ul>
                    </li>
                    <?php if ($role == 'admin'): ?>
                        <li><a class="nav-link fs-5" href="<?php echo site_url('obracun') ?>">Obračun</a></li>
                    <?php endif ?>
                    <?php if ($role != 'knjigovoda'): ?>
                        <li><a class="nav-link fs-5" href="<?php echo site_url('dugovi') ?>">Dugovi</a></li>
                    <?php endif ?>
                    <?php if ($fleet === 'Premium Rides' && $role == 'admin'): ?>
                        <li><a class="nav-link fs-5" href="<?php echo site_url('knjigovodstvo') ?>">Knjigovodstvo</a></li>
                    <?php endif ?>
                    <?php if ($fleet === 'Premium Rides' && $role == 'voditelj'): ?>
                        <li><a class="nav-link fs-5" href="<?php echo site_url('unosRacuna') ?>">Unos Računa</a></li>
                    <?php endif ?>
                    <li><a class="nav-link fs-5" href="<?php echo site_url('logout') ?>">Log out</a></li>
                </div>
                <!-- Move icons to the end -->
				<div class="me-1 d-flex">
					<!-- Notifications Dropdown -->
					<div class="nav-item dropdown me-1">
						<a href="#" class="nav-link position-relative" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
							<i class="bi bi-bell fs-4"></i>
							<span id="notification-count" class="position-absolute top-4 start-100 translate-middle badge rounded-pill bg-danger d-none">
								0
							</span>
						</a>
						<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" id="notification-dropdown">
							<li class="dropdown-header">Obavijesti</li>
							<li id="notification-list">
								<p class="text-muted text-center">Nema novih obavijesti</p>
							</li>
						</ul>
					</div>

					<!-- Chat Messages Dropdown -->
					<div class="nav-item dropdown me-1">
						<a href="#" class="nav-link position-relative" id="chatDropdown" data-bs-toggle="dropdown" aria-expanded="false">
							<i class="bi bi-chat-dots fs-4"></i>
							<span id="chat-count" class="position-absolute top-4 start-100 translate-middle badge rounded-pill bg-danger d-none">
								0
							</span>
						</a>
						<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="chatDropdown" id="chat-dropdown">
							<li class="dropdown-header">Chat poruke</li>
							<li id="chat-list">
								<p class="text-muted text-center">Nema novih poruka</p>
							</li>
						</ul>
					</div>
				</div>
            </div>
        </div>
    </nav>
</div>



<!-- Navbar -->	