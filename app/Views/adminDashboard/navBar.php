<div class="container d-flex align-items-center bg-dark">
	<nav class="navbar navbar-expand-lg bg-body-dark ">
	  <div class="container-fluid">
		<a class="navbar-brand fs-5" href="<?php echo site_url('profile')?>"><?php echo $fleet;?></a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
		  <span class="bi bi-list mobile-nav-toggle"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
		  <div class="navbar-nav ">
 			<?php if($role == 'admin'): ?>
         <li><a class="nav-link  fs-5" href="<?php echo site_url('uberImport')?>">Import reports</a></li>
			<?php endif ?>
          <li><a class="nav-link  fs-5" href="<?php echo site_url('drivers')?>">Drivers</a></li>
   			<?php if($role != 'knjigovoda'): ?>
        <li><a class="nav-link fs-5" href="<?php echo site_url('addDriver')?>">Add New Driver</a></li>
			<?php endif ?>
  			<?php if($role == 'admin'): ?>
         <li><a class="nav-link fs-5" href="<?php echo site_url('obracun')?>">Obračun</a></li>
			<?php endif ?>
			<?php if($role != 'knjigovoda'):?>
          <li><a class="nav-link fs-5" href="<?php echo site_url('dugovi')?>">Dugovi</a></li>
			<?php endif ?>
			<?php if($fleet === 'Premium Rides' && $role == 'admin'): ?>
          <li><a class="nav-link fs-5" href="<?php echo site_url('knjigovodstvo')?>">Knjigovodstvo</a></li>
			<?php endif ?>
			<?php if($fleet === 'Premium Rides' && $role == 'voditelj'): ?>
          <li><a class="nav-link fs-5" href="<?php echo site_url('unosRacuna')?>">Unos Računa</a></li>
			<?php endif ?>
          <li><a class="nav-link fs-5" href="<?php echo site_url('prijaveRadnika')?>">Prijave</a></li>
          <li><a class="nav-link fs-5" href="<?php echo site_url('aktivniDanas')?>">Aktivni sada</a></li>
          <li><a class="nav-link fs-5" href="<?php echo site_url('napomene')?>">Napomene</a></li>
   			<?php if($role != 'knjigovoda'): ?>
          <li><a class="nav-link fs-5" href="<?php echo site_url('vozila')?>">Vozila</a></li>
 			<?php endif ?>
         <li><a class="nav-link fs-5" href="<?php echo site_url('logout')?>">Log out</a></li>
		  </div>
		</div>
	  </div>
	</nav>
</div>

<!--
<div class="container d-flex align-items-center bg-dark">
      <h1 class="logo me-auto"><a href="<?php echo site_url('/profile')?>"><?php echo $fleet;?></a></h1>
      <nav id="navbar" class="navbar navbar-expand-lg">
        <ul>
 			<?php if($role == 'admin'): ?>
         <li><a class="nav-link" href="<?php echo site_url('uberImport')?>">Import reports</a></li>
			<?php endif ?>
          <li><a class="nav-link" href="<?php echo site_url('drivers')?>">Drivers</a></li>
   			<?php if($role != 'knjigovoda'): ?>
        <li><a class="nav-link" href="<?php echo site_url('addDriver')?>">Add New Driver</a></li>
			<?php endif ?>
  			<?php if($role == 'admin'): ?>
         <li><a class="nav-link" href="<?php echo site_url('obracun')?>">Obračun</a></li>
			<?php endif ?>
			<?php if($role != 'knjigovoda'):?>
          <li><a class="nav-link" href="<?php echo site_url('dugovi')?>">Dugovi</a></li>
			<?php endif ?>
			<?php if($fleet === 'Premium Rides' && $role == 'admin'): ?>
          <li><a class="nav-link" href="<?php echo site_url('knjigovodstvo')?>">Knjigovodstvo</a></li>
			<?php endif ?>
			<?php if($fleet === 'Premium Rides' && $role == 'voditelj'): ?>
          <li><a class="nav-link" href="<?php echo site_url('unosRacuna')?>">Unos Računa</a></li>
			<?php endif ?>
          <li><a class="nav-link" href="<?php echo site_url('prijaveRadnika')?>">Prijave</a></li>
          <li><a class="nav-link" href="<?php echo site_url('napomene')?>">Napomene</a></li>
   			<?php if($role != 'knjigovoda'): ?>
          <li><a class="nav-link" href="<?php echo site_url('vozila')?>">Vozila</a></li>
 			<?php endif ?>
         <li><a class="nav-link" href="<?php echo site_url('logout')?>">Log out</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav>

    </div>
-->





<!-- Navbar -->	