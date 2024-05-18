<div class="container d-flex align-items-center bg-dark">
	<nav class="navbar navbar-expand-lg bg-body-dark ">
	  <div class="container-fluid">
		<a class="navbar-brand fs-5" href="<?php echo base_url('index.php/profile')?>"><?php echo $fleet;?></a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
		  <span class="bi bi-list mobile-nav-toggle"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
		  <div class="navbar-nav ">
 			<?php if($role == 'admin'): ?>
         <li><a class="nav-link  fs-5" href="<?php echo base_url('/index.php/uberImport')?>">Import reports</a></li>
			<?php endif ?>
          <li><a class="nav-link  fs-5" href="<?php echo base_url('/index.php/drivers')?>">Drivers</a></li>
   			<?php if($role != 'knjigovoda'): ?>
        <li><a class="nav-link fs-5" href="<?php echo base_url('/index.php/addDriver')?>">Add New Driver</a></li>
			<?php endif ?>
  			<?php if($role == 'admin'): ?>
         <li><a class="nav-link fs-5" href="<?php echo base_url('/index.php/obracun')?>">Obračun</a></li>
			<?php endif ?>
			<?php if($role != 'knjigovoda'):?>
          <li><a class="nav-link fs-5" href="<?php echo base_url('/index.php/dugovi')?>">Dugovi</a></li>
			<?php endif ?>
			<?php if($fleet === 'Premium Rides' && $role == 'admin'): ?>
          <li><a class="nav-link fs-5" href="<?php echo base_url('/index.php/knjigovodstvo')?>">Knjigovodstvo</a></li>
			<?php endif ?>
			<?php if($fleet === 'Premium Rides' && $role == 'voditelj'): ?>
          <li><a class="nav-link fs-5" href="<?php echo base_url('/index.php/unosRacuna')?>">Unos Računa</a></li>
			<?php endif ?>
          <li><a class="nav-link fs-5" href="<?php echo base_url('/index.php/prijaveRadnika')?>">Prijave</a></li>
          <li><a class="nav-link fs-5" href="<?php echo base_url('/index.php/napomene')?>">Napomene</a></li>
   			<?php if($role != 'knjigovoda'): ?>
          <li><a class="nav-link fs-5" href="<?php echo base_url('/index.php/vozila')?>">Vozila</a></li>
 			<?php endif ?>
         <li><a class="nav-link fs-5" href="<?php echo base_url('/index.php/logout')?>">Log out</a></li>
		  </div>
		</div>
	  </div>
	</nav>
</div>

<!--
<div class="container d-flex align-items-center bg-dark">
      <h1 class="logo me-auto"><a href="<?php echo base_url('index.php/profile')?>"><?php echo $fleet;?></a></h1>
      <nav id="navbar" class="navbar navbar-expand-lg">
        <ul>
 			<?php if($role == 'admin'): ?>
         <li><a class="nav-link" href="<?php echo base_url('/index.php/uberImport')?>">Import reports</a></li>
			<?php endif ?>
          <li><a class="nav-link" href="<?php echo base_url('/index.php/drivers')?>">Drivers</a></li>
   			<?php if($role != 'knjigovoda'): ?>
        <li><a class="nav-link" href="<?php echo base_url('/index.php/addDriver')?>">Add New Driver</a></li>
			<?php endif ?>
  			<?php if($role == 'admin'): ?>
         <li><a class="nav-link" href="<?php echo base_url('/index.php/obracun')?>">Obračun</a></li>
			<?php endif ?>
			<?php if($role != 'knjigovoda'):?>
          <li><a class="nav-link" href="<?php echo base_url('/index.php/dugovi')?>">Dugovi</a></li>
			<?php endif ?>
			<?php if($fleet === 'Premium Rides' && $role == 'admin'): ?>
          <li><a class="nav-link" href="<?php echo base_url('/index.php/knjigovodstvo')?>">Knjigovodstvo</a></li>
			<?php endif ?>
			<?php if($fleet === 'Premium Rides' && $role == 'voditelj'): ?>
          <li><a class="nav-link" href="<?php echo base_url('/index.php/unosRacuna')?>">Unos Računa</a></li>
			<?php endif ?>
          <li><a class="nav-link" href="<?php echo base_url('/index.php/prijaveRadnika')?>">Prijave</a></li>
          <li><a class="nav-link" href="<?php echo base_url('/index.php/napomene')?>">Napomene</a></li>
   			<?php if($role != 'knjigovoda'): ?>
          <li><a class="nav-link" href="<?php echo base_url('/index.php/vozila')?>">Vozila</a></li>
 			<?php endif ?>
         <li><a class="nav-link" href="<?php echo base_url('/index.php/logout')?>">Log out</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav>

    </div>
-->





<!-- Navbar -->	