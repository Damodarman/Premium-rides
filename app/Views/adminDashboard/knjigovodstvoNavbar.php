<div class="container">
	<div class="row">
		<div class="col-1"></div>
		<div class="col-10">
			<nav class="navbar navbar-expand-lg navbar-light bg-light">
			  <div class="container-fluid">
				<a class="navbar-brand" href="#">Knjigovodstvo</a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
				  <span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
				  <div class="navbar-nav">
					  <?php if($role == 'admin'): ?>
					<a class="nav-link" href="<?php echo site_url('addprodajnomjesto')?>">Dodaj prodajno mjesto trgovca</a>
					<a class="nav-link" href="<?php echo site_url('generator')?>">Generator</a>
					<a class="nav-link" href="<?php echo site_url('ulazniRacuni')?>">Ulazni Računi</a>
					  <?php endif ?>
					<a class="nav-link" href="<?php echo site_url('addTrgovca')?>">Dodaj Trgovca</a>
					<a class="nav-link" href="<?php echo site_url('unosRacuna')?>">Unos Računa</a>
					<a class="nav-link" href="<?php echo site_url('profile')?>"><?php echo $fleet;?></a>
				  </div>
				</div>
			  </div>
			</nav>
		
		</div>
		<div class="col-1"></div>
	</div>
</div>