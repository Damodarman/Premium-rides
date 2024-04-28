    <div class="container d-flex align-items-center bg-dark">

      <h1 class="logo me-auto"><a href="<?php echo base_url('index.php/profile')?>"><?php echo $fleet;?></a></h1>
      <nav id="navbar" class="navbar">
        <ul>
 			<?php if($role == 'admin'): ?>
         <li><a class="nav-link" href="<?php echo base_url('/index.php/uberImport')?>">Import reports</a></li>
			<?php endif ?>
          <li><a class="nav-link" href="<?php echo base_url('/index.php/drivers')?>">Drivers</a></li>
          <li><a class="nav-link" href="<?php echo base_url('/index.php/addDriver')?>">Add New Driver</a></li>
  			<?php if($role == 'admin'): ?>
         <li><a class="nav-link" href="<?php echo base_url('/index.php/obracun')?>">Obračun</a></li>
			<?php endif ?>
          <li><a class="nav-link" href="<?php echo base_url('/index.php/dugovi')?>">Dugovi</a></li>
			<?php if($fleet === 'Premium Rides' && $role == 'admin'): ?>
          <li><a class="nav-link" href="<?php echo base_url('/index.php/knjigovodstvo')?>">Knjigovodstvo</a></li>
			<?php endif ?>
			<?php if($fleet === 'Premium Rides' && $role == 'voditelj'): ?>
          <li><a class="nav-link" href="<?php echo base_url('/index.php/unosRacuna')?>">Unos Računa</a></li>
			<?php endif ?>
          <li><a class="nav-link" href="<?php echo base_url('/index.php/vozila')?>">Vozila</a></li>
          <li><a class="nav-link" href="<?php echo base_url('/index.php/prijaveRadnika')?>">Prijave</a></li>
          <li><a class="nav-link" href="<?php echo base_url('/index.php/napomene')?>">Napomene</a></li>
          <li><a class="nav-link" href="<?php echo base_url('/index.php/logout')?>">Log out</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>





<!-- Navbar -->	