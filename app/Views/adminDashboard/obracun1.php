
<div class="position-fixed top-50 end-0 translate-middle-y me-5">
  <div class="btn-group-vertical">
	<a href="#obracuni" class="btn btn-primary my-2">Obračuni</a>
	<a href="#obracuniFirma" class="btn btn-primary my-2">Firma</a>
	<a href="#revolut" class="btn btn-primary my-2">Revolut</a>
	<a href="#hrIban" class="btn btn-primary my-2">HR IBAN</a>
	<a href="#uDugu" class="btn btn-primary my-2">U dugu</a>
  </div>
</div>

<div class="box">
	<div class="container">

		<div id="obracuni" class="row">
			
							<?php if (session()->has('msgSlika')){ ?>
			<div class="col-2"></div>
				<div class="col-8">
					<div class="container alert <?=session()->getFlashdata('alert-class') ?> alert-dismissible fade show justify-content-center fixed-top fixed-center">
						<?=session()->getFlashdata('msgSlika') ?>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				</div>
			<div class="col-2"></div>
				<?php } ?>	

		<?php 
			if(isset($obracun)): ?>
				<?php 
				$allPerHour = 0 ;
				$allPerOHour = 0 ;
				$count = 0;
			
			
			$obracunFirmaID = $obracunFirma['id'];
			$zipName = $obracunFirma['week'] .'-obračun-' .$fleet;
			$reportIDs = array();
			$taximetarnum = 0;
			$taximetarsum = 0;
			foreach ($obracun as $driver):?>
			<?php 
			$driver_slug_id = str_replace(' ','_', $driver['vozac']); 
			$driver_slug_id = str_replace('č','c', $driver_slug_id); 
			$driver_slug_id = str_replace('ć','c', $driver_slug_id); 
			$driver_slug_id = str_replace('ž','z', $driver_slug_id); 
			$driver_slug_id = str_replace('š','s', $driver_slug_id); 
			$driver_slug_id = str_replace('đ','d', $driver_slug_id); 
			$driver_slug_id = str_replace('Č','C', $driver_slug_id); 
			$driver_slug_id = str_replace('Ć','C', $driver_slug_id); 
			$driver_slug_id = str_replace('Ž','Z', $driver_slug_id); 
			$driver_slug_id = str_replace('Š','S', $driver_slug_id); 
			$driver_slug_id = str_replace('Đ','D', $driver_slug_id); 
			$raspon_slug = str_replace(' - ','_', $driver['raspon']);
			$driver_slug_id = $driver_slug_id .'_' .$raspon_slug;
			$reportIDs[] = $driver_slug_id;
			?>
			<div class="col-2 d-flex align-items-center">
				<form action="<?php echo site_url('AdminController/poslatiObracun');?>" method="post" >
					<input type="hidden" name="id" value="<?php echo $driver['id'] ?>">
					<button type="submit" class="btn btn-primary">Pošalji obračun vozaču</button>
				</form>
			</div>
			<div class="col col-xxl-8 col-xl-8 col-lg-12 col-m-12 col-sm-12">
				<div id="<?php echo $driver['id'] ?>">
				<div id="<?php echo $driver_slug_id ?>" data-element-id="<?php echo $driver['id'] ?>" class="card border-danger text-white bg-secondary mt-3">
					<div class="card-header text-center"><h4 class="fw-bold"><a href="<?php echo site_url('drivers/'). '/' .$driver['vozac_id']?>"><?php echo $driver['vozac']; ?></a></h4>
					<h6 class=" border-dark"><a href="<?php echo site_url('editirajObracun/'). '/' .$driver['id']?>"><?php echo $driver['raspon']; ?></a></h6>
					</div>
					<div class="card-body">
						<?php if($obracunFirma['activity'] != 0): ?>
	
						<div class="row text-center">
							<table class="table table-sm table-bordered table-dark">
								 <thead>
									<tr>
										<th>Service</th>
										<th>Sati na mreži</th>
										<th>Sati u vožnji</th>
										<th>€/h na mreži</th>
										<th>€/h u vožnji</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Uber</td>
										<td><?=$driver['uberOnline'] ?></td>
										<td><?=$driver['uberActiv'] ?></td>
										<td><?=$driver['uberPerOH'] ?></td>
										<td><?=$driver['uberPerH'] ?></td>
									</tr>
									<tr>
										<td>Bolt</td>
										<td><?=$driver['boltOnline'] ?></td>
										<td><?=$driver['boltActiv'] ?></td>
										<td><?=$driver['boltPerOH'] ?></td>
										<td><?=$driver['boltPerH'] ?></td>
									</tr>
									<tr>
										<td>Ukupno</td>
										<td><?=$driver['uberOnline'] + $driver['boltOnline'] ?></td>
										<td><?=$driver['uberActiv'] + $driver['boltActiv'] ?></td>
										<td><?=$driver['totalPerOH'] ?></td>
										<td><?=$driver['totalPerH'] ?></td>
									</tr>
								</tbody>
							</table>
						</div>
						
						<?php endif ?>
						
						<div class="row text-center">
							<div class="col-sm">
								<h4>Obračun</h4>
								<ul class="list-group list-group-flush border-danger">
									<li class="list-group-item fs-6">Neto: </li>
									<li class="list-group-item fs-6">Napojnice: </li>
									<li class="list-group-item fs-6">Povrati: </li>
									<li class="list-group-item fs-6">Gotovina: </li>
									<li class="list-group-item fs-6">Razlika: </li>
								</ul>
							</div>
							<?php if($driver['boltNeto'] != 0) :  ?>
							<div class="col-sm">
								<h4>Bolt</h4>
								<ul class="list-group list-group-flush border-danger">
									<li class="list-group-item fs-6"> <?php echo $driver['boltNeto']; ?> €</li>
									<li class="list-group-item fs-6"> <?php echo $driver['boltNapojnica']; ?> €</li>
									<li class="list-group-item fs-6"> <?php echo $driver['boltPovrat']; ?> €</li>
									<li class="list-group-item fs-6"> <?php echo $driver['boltGotovina']; ?> €</li>
									<li class="list-group-item fs-6"> <?php echo $driver['boltRazlika']; ?> €</li>
								</ul>
							</div>
							<?php endif ?>
							<?php if($driver['uberNeto'] != 0) :  ?>
							<div class="col-sm">
								<h4>Uber</h4>
								<ul class="list-group list-group-flush border-danger">
									<li class="list-group-item fs-6"> <?php echo $driver['uberNeto']; ?> €</li>
									<li class="list-group-item fs-6"> <?php echo $driver['uberNapojnica']; ?> €</li>
									<li class="list-group-item fs-6"> <?php echo $driver['uberPovrat']; ?> €</li>
									<li class="list-group-item fs-6"> <?php echo $driver['uberGotovina']; ?> €</li>
									<li class="list-group-item fs-6"> <?php echo $driver['uberRazlika']; ?> €</li>
								</ul>
							</div>
							<?php endif ?>
							<?php if($driver['myPosNeto'] != 0) :  ?>
								<div class="col-sm">
									<h4>Taximetar</h4>
									<ul class="list-group list-group-flush border-danger">
										<li class="list-group-item fs-6"><?php echo $driver['myPosNeto']; ?> €</li>
										<li class="list-group-item fs-6"> <?php echo $driver['myPosNapojnica']; ?> €</li>
										<li class="list-group-item fs-6"> <?php echo $driver['myPosPovrat']; ?> €</li>
										<li class="list-group-item fs-6"><?php echo $driver['myPosGotovina']; ?> €</li>
										<li class="list-group-item fs-6"><?php echo $driver['myPosRazlika']; ?> €</li>
									</ul>
								</div>
							<?php endif ?>
							<div class="col-sm">
								<h4>Ukupno</h4>
								<ul class="list-group list-group-flush border-danger">
										<li class="list-group-item fs-6"><?php echo $driver['ukupnoNeto']; ?> €</li>
										<li class="list-group-item fs-6"> <?php echo $driver['ukupnoNapojnica']; ?> €</li>
										<li class="list-group-item fs-6"> <?php echo $driver['ukupnoPovrat']; ?> €</li>
										<li class="list-group-item fs-6"><?php echo $driver['ukupnoGotovina']; ?> €</li>
										<li class="list-group-item fs-6"><?php echo $driver['ukupnoRazlika']; ?> €</li>
								</ul>
							</div>
						</div>
						<div class="row text-center"> 
							<div class="col-sm">
								<?php 
								if($driver['doprinosi'] !=0){
									$cetDop = $driver['doprinosi'] + $driver['cetvrtinaNetoPlace']; 
								}else{
									$cetDop = 0;
								} 
								
								?>
								<h4>Troškovi</h4>
								<ul class="list-group list-group-flush border-danger">
									<li class="list-group-item fs-6 bg-danger">Računovodstveni troškovi: <?php echo $driver['provizija']; ?> €</li>
									<?php if($driver['taximetar'] !=0): ?>
									<li class="list-group-item fs-6 bg-danger">Taximetar aplikacija: <?php echo $driver['taximetar']; ?> €</li>
									<?php
									$taximetarnum += 1;
									$taximetarsum += $driver['taximetar'];
									
									?>
									<?php endif ?>
									<?php if($driver['dug'] < 0): ?>
									<li class="list-group-item fs-6 bg-danger">Dug: <?php echo $driver['dug']; ?> €</li>
									<?php endif ?>
									<?php if($driver['najamVozila'] != 0): ?>
									<li class="list-group-item fs-6 bg-danger">Najam Auta: <?php echo $driver['najamVozila']; ?> €</li>
									<?php endif ?>
									<?php if($driver['fiskalizacijaUber'] !=0): ?>
									<li class="list-group-item fs-6 bg-danger">Fiskalizacija Uber: <?php echo $driver['fiskalizacijaUber']; ?> €</li>
									<?php endif ?>
									<?php if($driver['fiskalizacijaBolt'] !=0): ?>
									<li class="list-group-item fs-6 bg-danger">Fiskalizacija Bolt: <?php echo $driver['fiskalizacijaBolt']; ?> €</li>
									<?php endif ?>
<!--
									<?php if($driver['doprinosi'] !=0): ?>
									<li class="list-group-item fs-6 bg-info">Četvrtina Bruto 2 troška plaće: <?php echo $cetDop; ?> €</li>
									<?php endif ?>
-->

									<?php if($driver['doprinosi'] !=0): ?>
									<li class="list-group-item fs-6 bg-danger">Četvrtina doprinosa: <?php echo $driver['doprinosi']; ?> €</li>
									<?php endif ?>
									<?php if($driver['cetvrtinaNetoPlace'] !=0): ?>
									<li class="list-group-item fs-6 bg-info">Četvrtina neto plaće koja će se isplatiti početkom mjeseca: <?php echo $driver['cetvrtinaNetoPlace'] ?> €</li>

									<?php endif ?>
								</ul>
							</div>
							<div class="col-sm">
								<h4>Bonusi</h4>
								<ul class="list-group list-group-flush border-danger">
									<?php 
									
									$referals = $driver['referals'];
									if($referals != 'nema'){
									$referals = json_decode($referals, true);
									foreach($referals as $ref){ 
										;?>
										<?php if($ref['refered_nagrada'] != 0): ?>
											<li class="list-group-item fs-6 bg-success"><?php echo $ref['refered_nagrada']; ?> € za <?php echo $ref['refered_vozac']; ?></li>
										<?php endif ?>
									<?php 
									} ?>
									<?php 
									} ?>
									
									<?php if($driver['dug'] > 0): ?>
									<li class="list-group-item fs-6 bg-success">Dug: <?php echo $driver['dug'] ?> €</li>
									<?php endif ?>
									<?php if($driver['boltNaljepnice'] > 0): ?>
									<li class="list-group-item fs-6 bg-success">Bolt Bonus/Naljepnica: <?php echo $driver['boltNaljepnice'] ?> €</li>
									<?php endif ?>
								</ul>
							</div>
						</div>
						<div class="row text-center">
							<div class="col-sm ">
								<ul class="list-group list-group-flush border border-danger border-2">
									<li class="list-group-item text-dark fs-6"> Završni saldo: </li>
									<?php if($driver['zaIsplatu'] > 0) : ?>
									<li class="list-group-item text-success fw-bold fs-5"> <?php echo $driver['zaIsplatu']?> €</li>
									<?php else: ?>
									<li class="list-group-item text-danger fw-bold fs-5"> <?php echo $driver['zaIsplatu']?> €</li>
									<?php endif ?>
								</ul>
							</div>
						</div>
					<?php if($driver['myPosTransakcije'] != 0 ): ?>
						<?php 
							$mPTransakcije = $driver['myPosTransakcije'];
							$mPTransakcije1 = json_decode($mPTransakcije, true);
						?>
						<div class="row text-center">
							<h4 class="border border-info border-2 text-info mt-1">MyPos Transakcije</h4>
							<div class="col-sm">
								<h4>Datum</h4>
								<ul class="list-group list-group-flush border-danger">
									<?php foreach($mPTransakcije1 as $mPT): ?>
										<li class="list-group-item fs-6"><?php echo $mPT['Date_initiated']?> </li>
									<?php endforeach ?>
								</ul>
							</div>
							<div class="col-sm">
								<h4>Vrsta</h4>
								<ul class="list-group list-group-flush border-danger">
									<?php foreach($mPTransakcije1 as $mPT): ?>
										<li class="list-group-item fs-6"><?php echo $mPT['Type']?> </li>
									<?php endforeach ?>
								</ul>
							</div>
						<div class="col-sm">
							<h4>Iznos</h4>
							<ul class="list-group list-group-flush border-danger">
								<?php foreach($mPTransakcije1 as $mPT): ?>
									<li class="list-group-item fs-6"><?php echo $mPT['Amount']?> €</li>
								<?php endforeach ?>
							</ul>
						</div>
						</div>
						<?php endif ?>
					</div>
				</div>
					</div>
				<?php if(!empty($driver['slikaObracuna'])): ?>
				<h6 class=" border-dark"><a href="<?php echo base_url($driver['slikaObracuna'])?>"><?php echo 'pogledaj obračun vozača ' .$driver['vozac']; ?></a></h6>
	
				<?php endif ?>

			</div>
						<div class="col-2"></div>
				<?php 
				$allPerHour +=$driver['totalPerH'] ;
				$allPerOHour +=$driver['totalPerOH'] ;
				$count += 1
			
			
			?>
				<?php endforeach ?>
			
			
<div class="container">
  <h1 class="text-center mt-5 mb-3">Download Screenshots</h1>
  <div class="text-center mb-3">
    <button class="btn btn-primary" id="download-screenshots">Spremi slike obračuna</button>
	  
	  <?php if($obracunFirma['sveSlikeSpremljene'] == true): ?>
	  <div class="row mt-4">
	  <div class="col">
	  <form action="<?php echo base_url(); ?>/index.php/AdminController/poslatiSlike" method="post">
	  	<textarea hidden name="poslatiSlikeJson"> <?php echo $poslatiSlikeJson ?></textarea>
		  <button type="submit" class="btn btn-dark">Pošalji vozačima slike obračuna</button>
	  </form>
		  </div>
	  <div class="col">
	  <form action="<?php echo base_url(); ?>/index.php/ObracunController/skinutiSlike" method="post">
	  	<input type="hidden" name="obracunFirmaID" value="<?php echo $obracunFirmaID; ?>">
	  	<input type="hidden" name="fleet" value="<?php echo $fleet; ?>">
	  	<input type="hidden" name="week" value="<?php echo $week; ?>">
		  <button type="submit" class="btn btn-dark">Skini slike obračuna</button>
	  </form>
		  </div>
	  </div>
	  <?php endif ?>
	  
	<div id="total-progress-container" class="progress" style="display: none;">
	  <div id="total-progress" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
	  </div>
			
			<div id="obracuniFirma" class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card border-danger text-white bg-secondary mt-3">
					<div class="card-header text-center"><h4 class="fw-bold">Firma Obračun</h4></div>
					<div class="card-body">
						<div class="row text-center">
							<div class="col">
								<h4>Uber</h4>
								<ul class="list-group list-group-flush border-danger">
									<li class="list-group-item fs-6">Neto promet svih vozača: <?php echo $obracunFirma['uberNeto']; ?> €</li>
									<li class="list-group-item fs-6">Gotovina svih vozača: <?php echo $obracunFirma['uberGotovina']; ?> €</li>
									<li class="list-group-item fs-6">Razlika za isplatu: <?php echo $obracunFirma['uberRazlika']; ?> €</li>
								</ul>
							</div>
							<div class="col">
								<h4>Bolt</h4>
								<ul class="list-group list-group-flush border-danger">
									<li class="list-group-item fs-6">Neto promet svih vozača: <?php echo $obracunFirma['boltNeto']; ?> €</li>
									<li class="list-group-item fs-6">Gotovina svih vozača: <?php echo $obracunFirma['boltGotovina']; ?> €</li>
									<li class="list-group-item fs-6">Razlika za isplatu: <?php echo $obracunFirma['boltRazlika']; ?> €</li>
								</ul>
							</div>
							<div class="col">
								<h4>MyPos</h4>
								<ul class="list-group list-group-flush border-danger">
									<li class="list-group-item fs-6">Neto promet svih vozača: <?php echo $obracunFirma['myPosNeto']; ?> €</li>
									<li class="list-group-item fs-6">Gotovina svih vozača: <?php echo $obracunFirma['myPosGotovina']; ?> €</li>
									<li class="list-group-item fs-6">Razlika za isplatu: <?php echo $obracunFirma['myPosRazlika']; ?> €</li>
								</ul>
							</div>
							<div class="col">
								<h4>Ukupno</h4>
								<ul class="list-group list-group-flush border-danger">
									<li class="list-group-item fs-6">Neto promet svih vozača: <?php echo $obracunFirma['ukupnoNetoSvi']; ?> €</li>
									<li class="list-group-item fs-6">Gotovina svih vozača: <?php echo $obracunFirma['ukupnoGotovinaSvi']; ?> €</li>
									<li class="list-group-item fs-6">Razlika za isplatu: <?php echo $obracunFirma['ukupnoRazlikaSvi']; ?> €</li>
									<li class="list-group-item fs-6">Provizija za naplatiti: <?php echo $obracunFirma['zaradaFirme']; ?> €</li>
									<li class="list-group-item fs-6">Za isplatit vozače: <?php echo $obracunFirma['zaIsplatu']; ?> €</li>
									<li class="list-group-item fs-6">Treba ostat firmi: <?php echo $obracunFirma['trebaOstatFirmi']; ?> €</li>
								</ul>
							</div>
						</div>
					</div>			   
				</div>
			</div>
			<div class="col-12">
					<a href=<? echo site_url('tjedneIsplateKreiraj/' .$week) ?> ><h4>Kreiraj datoteku za isplatu </h4></a>
			  </div>

	  </div>
					<?php endif?>
		<div class="row">
			<div class="col-6">
				<h4>Prosjek po vozaču </h4>
				<ul class="list-group list-group-flush border-danger">
					<li class="list-group-item fs-6">Broj Vozača kojima je naplačen taximetar : <?php echo $taximetarnum; ?></li>
					<li class="list-group-item fs-6">Ukupno naplačen taximetar : <?php echo $taximetarsum; ?></li>
					<li class="list-group-item fs-6">Broj Vozača : <?php echo round($stats['brojVozaca'], 2); ?></li>
					<li class="list-group-item fs-6">Neto promet : <?php echo round($stats['netoPoVozacu'], 2); ?> €</li>
					<li class="list-group-item fs-6">Provizija za naplatiti: <?php echo round($stats['provizijaPoVozacu'], 2); ?> €</li>
					<li class="list-group-item fs-6">Satnica aktivna: <?php echo round($allPerHour / $count, 2) ?> €</li>
					<li class="list-group-item fs-6">Satnica online: <?php echo round($allPerOHour / $count, 2) ?> €</li>
				</ul>
			</div>
			<div class="col-6">
			</div>
		</div>

	
		<div id="revolut" class="row">
			<div class="col-12 border-3 border-danger text-center">
				<h4>Isplata na Revolut </h4>
				<div class="row border-bottom border-danger mb-1 pb-2 bg-secondary text-white">
				<div class="col-md-4 mt-2  pb-2 border-bottom border-danger border-2">
					<label for="vozac" class="form-label ">Vozač</label>
					</div>
				<div class="col-md-2 mt-2  pb-2 border-bottom border-danger border-2">
					<label for="zaIsplatu" class="form-label ">Isplata</label>
					</div>
				<div class="col-md-4 mt-2  pb-2 border-bottom border-danger border-2">
					<label for="IBAN" class="form-label ">IBAN</label>
					</div>
				<div class="col-md-2 mt-2  pb-2 border-bottom border-danger border-2">
					<div class="form-check mt-2">
						<input class="form-check-input" type="checkbox" id="checkAll">   Check All
					</div>
				</div>
				<?php foreach($obracun as $obr): ?>
					<?php if($obr['isplataNa'] != 'hrIBAN'): ?>
						<?php if($obr['zaIsplatu'] > 0): ?>
						
						<div class="col-md-4 mb-2  pb-2 border-bottom border-danger border-2">
							
							<input readonly type="text" name ="vozac" class="form-control" value="<?php echo $obr['vozac'] ?>">
					    </div>
						<div class="col-md-2 mb-2  pb-2 border-bottom border-danger border-2">
							
							<input readonly type="text" name ="zaIsplatu" class="form-control" value="<?php echo $obr['zaIsplatu'] ?>">
					    </div>
						<div class="col-md-4 mb-2 pb-2 border-bottom border-danger border-2">
							
							<input readonly type="text" name ="IBAN" class="form-control" value="<?php echo $obr['IBAN'] ?>">
					    </div>
						<div class="col-md-2 mb-2 pb-2 border-bottom border-danger border-2">
							<div class="form-check mt-2">
							  <input class="form-check-input" name="<?php echo $obr['vozac_id'] ?>" type="checkbox" value="<?php echo $obr['zaIsplatu'] ?>" id="flexCheckDefault"> <?php echo $obr['zaIsplatu'] ?> €
							</div>
					    </div>
					<?php endif ?>
					<?php endif ?>
				<?php endforeach ?>
				<h5 class="text-right pt-2">Total Sum: <span id="totalSum">0</span> €</h5>

				</div>
			</div>
		</div>
		<div id="hrIban" class="row">
			<div class="col-12  text-center">
				<h4>Isplata na HR IBAN </h4>
				<div class="row  mb-1 pb-2 bg-secondary text-white">
				<div class="col-md-4 mt-2  pb-2 border-bottom border-danger border-2">
					<label for="vozac" class="form-label ">Vozač</label>
					</div>
				<div class="col-md-4 mt-2  pb-2 border-bottom border-danger border-2">
					<label for="zaIsplatu" class="form-label ">Isplata</label>
					</div>
				<div class="col-md-4 mt-2  pb-2 border-bottom border-danger border-2">
					<label for="IBAN" class="form-label ">IBAN</label>
					</div>
				<?php
					$totalSumHRiban = 0;
					foreach($obracun as $obr): ?>
					<?php if($obr['isplataNa'] != 'Revolut'): ?>
						<?php if($obr['zaIsplatu'] > 0): ?>
						
						<div class="col-md-4 mb-2  pb-2 border-bottom border-danger border-2">
							
							<input readonly type="text" name ="vozac" class="form-control" value="<?php echo $obr['vozac'] ?>">
					    </div>
						<div class="col-md-4 mb-2  pb-2 border-bottom border-danger border-2">
							
							<input readonly type="text" name ="zaIsplatu" class="form-control" value="<?php echo $obr['zaIsplatu'] ?>">
					    </div>
						<div class="col-md-4 mb-2 pb-2 border-bottom border-danger border-2">
							
							<input readonly type="text" name ="IBAN" class="form-control" value="<?php echo $obr['IBAN'] ?>">
					    </div>
					
					<?php
						$totalSumHRiban += $obr['zaIsplatu'];
						endif ?>
					<?php endif ?>
				<?php endforeach ?>
				<h5 class="text-right pt-2">Total Sum: <span id="totalSum"><?php echo $totalSumHRiban ?></span> €</h5>

				</div>
			</div>
		</div>
		<div id="uDugu" class="row">
			<div class="col-12  text-center">
				<h4>Vozači u dugu </h4>
				<div class="row  mb-1 pb-2 bg-secondary text-white">
				<div class="col-md-4 mt-2  pb-2 border-bottom border-danger border-2">
					<label for="vozac" class="form-label ">Vozač</label>
					</div>
				<div class="col-md-4 mt-2  pb-2 border-bottom border-danger border-2">
					<label for="zaIsplatu" class="form-label ">Isplata</label>
					</div>
				<div class="col-md-4 mt-2  pb-2 border-bottom border-danger border-2">
					<label for="IBAN" class="form-label ">IBAN</label>
					</div>
				<?php
					$totalSumUDugu = 0;
					foreach($obracun as $obr): ?>
						<?php if($obr['zaIsplatu'] < 0): ?>
						
						<div class="col-md-4 mb-2  pb-2 border-bottom border-danger border-2">
							
							<input readonly type="text" name ="vozac" class="form-control" value="<?php echo $obr['vozac'] ?>">
					    </div>
						<div class="col-md-4 mb-2  pb-2 border-bottom border-danger border-2">
							
							<input readonly type="text" name ="zaIsplatu" class="form-control" value="<?php echo $obr['zaIsplatu'] ?>">
					    </div>
						<div class="col-md-4 mb-2 pb-2 border-bottom border-danger border-2">
							
							<input readonly type="text" name ="IBAN" class="form-control" value="<?php echo $obr['IBAN'] ?>">
					    </div>
					
					<?php
						$totalSumUDugu += $obr['zaIsplatu'];
						endif ?>
				<?php endforeach ?>
				<h5 class="text-right pt-2">Total Sum: <span id="totalSum"><?php echo $totalSumUDugu ?></span> €</h5>

				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<table width="200" border="2" cellspacing="1" cellpadding="1">
  <tbody>
    <tr>
      <th scope="col">Vozač</th>
      <th scope="col">Isplata</th>
      <th scope="col">Isplačeno na Revolut/ HR iban</th>
    </tr>
	  <?php 
		$start = 0;
		foreach($obracun as $dr): ?>
	  <?php $start +=1 ?>
	    <tr>
      <td class="border border-dark"><?php echo $dr['vozac'] ; ?></td>
      <td class="border border-dark"><?php echo $dr['zaIsplatu'] ; ?></td>
      <td class="border border-dark"></td>
   		</tr>
  
	  
	  <?php endforeach ?>
  </tbody>
</table>
	
	</div>
</div>


<script>
  const checkboxes = document.querySelectorAll('input[type="checkbox"]');
  const checkAll = document.getElementById('checkAll');
  const totalSum = document.getElementById('totalSum');
  let sum = 0;

  checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', () => {
      if (checkbox === checkAll) {
        checkboxes.forEach(cb => cb.checked = checkbox.checked);
        sum = checkbox.checked ? Array.from(checkboxes).filter(cb => cb !== checkAll).reduce((acc, cb) => acc + parseFloat(cb.value), 0) : 0;
      } else {
        if (checkbox.checked) {
          sum += parseFloat(checkbox.value);
        } else {
          sum -= parseFloat(checkbox.value);
        }
        if (checkboxes.length - 1 === Array.from(checkboxes).filter(cb => cb !== checkAll && cb.checked).length) {
          checkAll.checked = true;
        } else {
          checkAll.checked = false;
        }
      }
      totalSum.textContent = sum.toFixed(2);
    });
  });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>




<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js" integrity="sha512-tVYBzEItJit9HXaWTPo8vveXlkK62LbA+wez9IgzjTmFNLMBO1BEYladBw2wnM3YURZSMUyhayPCoLtjGh84NQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js" integrity="sha512-XMVd28F1oH/O71fzwBnV7HucLxVwtxf26XV8P4wPk26EDxuGZ91N8bsOttmnomcCD3CS5ZMRL50H0GgOHvegtg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.6.0/jszip.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>


<script>
const reportIDs = <?php echo json_encode($reportIDs); ?>;
const zip = new JSZip();
const obracunFirmaID = <?php echo $obracunFirmaID; ?>

function downloadScreenshots() {
 const totalProgressContainer = document.getElementById('total-progress-container');
  totalProgressContainer.style.display = 'block';  
	downloadButton.disabled = true;

  const totalSteps = reportIDs.length;
  let completedSteps = 0;

  // Function to update the total progress bar
  function updateTotalProgress() {
    completedSteps++;
    const totalProgress = (completedSteps / totalSteps) * 100;
    const totalProgressBar = document.getElementById('total-progress');
	const formattedTotalProgress = totalProgress.toFixed(2); // Limit to 2 decimal places
    totalProgressBar.style.width = totalProgress + '%';
    totalProgressBar.textContent = totalProgress + '%';
  }

  // Function to save the screenshot on the server
  function saveScreenshot(idIndex) {
    if (idIndex >= totalSteps) {
      // All screenshots have been processed
      downloadButton.disabled = false;
      // Trigger the download of the zip file or perform any other action as needed
      downloadZipFile();
	  sendProgressCompletionInfo();
      return;
    }

    const id = reportIDs[idIndex];
    const element = document.getElementById(id);
    const elementId = element.getAttribute('data-element-id');

    html2canvas(element).then(canvas => {
      canvas.toBlob(blob => {
        const formData = new FormData();
        formData.append('screenshot', blob, id + '.jpg');
        formData.append('elementId', elementId);

        const url = "<?php echo base_url(); ?>/index.php/ObracunController/saveScreenshots";

        fetch(url, {
          method: 'POST',
          body: formData,
        })
        .then(response => {
          if (response.ok) {
            // Move on to the next screenshot
            updateTotalProgress(((idIndex + 1) / totalSteps) * 100);
            saveScreenshot(idIndex + 1);
          }
        });
      });
    });
  }

  // Function to download the zip file
  function downloadZipFile() {
    // ... (The rest of your existing code to generate and download the zip file)
  }

  // Start processing the first report ID
  saveScreenshot(0);
}

const downloadButton = document.getElementById('download-screenshots');
const screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
if (screenWidth > 767) { // Disable function on mobile phones with screen width smaller than 768 pixels
  downloadButton.addEventListener('click', downloadScreenshots);
}
	
// Function to send information to the controller when full progress is finished
function sendProgressCompletionInfo() {
  // You can include any data you need to send to the controller method in the formData
  const obracunFirmaID = <?php echo $obracunFirmaID; ?>

  const formData = new FormData();
  formData.append('sveSlikeSpremljene', 'true');
  formData.append('obracunFirmaID', obracunFirmaID);

  const url = "<?php echo base_url(); ?>/index.php/ObracunController/sveSlikeSpremljene";

  fetch(url, {
    method: 'POST',
    body: formData,
  })
  .then(response => {
    if (response.ok) {
      // You can perform any additional actions after successfully sending the data to the controller
      console.log('Full progress information sent to controller');
    }
  });
}	

</script>
