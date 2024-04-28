
	<?php if(isset($validation)):?>
	<div class="alert alert-warning">
	   <?= $validation->listErrors() ?>
		<?php endif ?>

<div class="container">
	<div class="row">
		<div class="col-2">
			<ul class="list-group  border-danger">
				<?php
				$activeIndex = -1; // Initialize the active index to a value that won't match any valid index

				// Find the index of the active item
				foreach ($drivers as $index => $dr) {
					if ($dr['id'] == $driver['id']) {
						$activeIndex = $index;
						break; // Stop searching once the active item is found
					}
				}

				foreach ($drivers as $index => $dr):
					$isActive = $index === $activeIndex; // Check if this item is active

					$linkClass = 'list-group-item list-group-item-action';
					if ($isActive) {
						$linkClass .= ' active';
					}
				?>

				<a href="<?php echo base_url('/index.php/drivers/'). '/' . $dr['id'] ?>"
				   class="<?php echo $linkClass; ?>"><?php echo $dr['vozac'] ?></a>

				<?php endforeach ?>
			</ul>
	</div>
		<div class="col-10">
												<?php if (session()->has('msgVozilo')){ ?>
										<div class="alert <?=session()->getFlashdata('alert-class') ?>">
											<?=session()->getFlashdata('msgVozilo') ?>
										</div>
									<?php } ?>
												<?php if (session()->has('msgNapomena')){ ?>
										<div class="alert <?=session()->getFlashdata('alert-class') ?>">
											<?=session()->getFlashdata('msgNapomena') ?>
										</div>
									<?php } ?>

			<h2><?php echo $driver['vozac'] ?></h2>
			<div class="accordion accordion-flush" id="accordionFlushExample">
			<div class="accordion-item">

			<h2 class="accordion-header" id="flush-headingOne">
			<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
			Editiraj podatke o vozaču 
			</button>
			</h2>
			<div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
			<div class="accordion-body">


			<form id="form1" class="row g-3 needs-validation" action="<?php echo base_url('index.php/AdminController/driverUpdate');?>" method="post" novalidate>
			  <input type="hidden" class="form-control" name="id"  value ="<?php echo $driver['id']  ?>">
			  <input type="hidden" class="form-control" name="vozac"  value ="<?php echo $driver['vozac']  ?>">
			<div class="col-md-2">
				<label for="ime" class="form-label">Ime</label>
				<input type="text" name ="ime" class="form-control" value ="<?php echo $driver['ime']  ?>" required>
				<div class="invalid-feedback">Upišite ime.</div>
			  </div>
			  <div class="col-md-2">
				<label for="prezime" class="form-label">Prezime</label>
				<input type="text" name ="prezime" class="form-control" id="prezime" value ="<?php echo $driver['prezime']  ?>" required>
				  <div class="invalid-feedback">Upišite prezime.</div>
			  </div>
			<div class="col-md-3">
				<label for="email" class="form-label">Email</label>
				<input type="email" name ="email" class="form-control" id="email" value ="<?php echo $driver['email']  ?>" required>
				<div class="invalid-feedback">Upišite e-mail adresu.</div>
			  </div>
				<hr class="hr" />
			<div class="col-md-2">
				<label for="mobitel" class="form-label">Broj mobitela</label>
				<input type="text" name ="mobitel" class="form-control" id="mobitel" value ="<?php echo $driver['mobitel']  ?>" required>
				<div class="invalid-feedback">Upišite broj mobitela.</div>
			  </div>
			<div class="col-md-2">
				<label for="whatsApp" class="form-label">Broj whatsApp</label>
				<input type="text" name ="whatsApp" class="form-control" id="whatsApp" value ="<?php echo $driver['whatsApp']  ?>">
			  </div>
				<?php if($valjanost != 'valid'): ?>
			<div class="col-md-4">
				<label for="valjanost" class="form-label">Valjanost broja na whatsApp</label>
				<input type="text" name ="valjanost" class="border border-2 border-danger form-control" id="whatsApp" value ="<?php echo $valjanost  ?>">
			  </div>
				
				<?php else : ?>
			<div class="col-md-4">
				<label for="valjanost" class="form-label">Valjanost broja na whatsApp</label>
				<input type="text" name ="valjanost" class="form-control border border-2 border-success " id="whatsApp" value ="<?php echo $valjanost  ?>">
			  </div>
				<?php endif ?>
			
				<hr class="hr" />
			  <div class="col-6">
				<label for="adresa" class="form-label">Adresa</label>
				<input type="text" name ="adresa" class="form-control" id="adresa" value ="<?php echo $driver['adresa']  ?>" required>
				  <div class="invalid-feedback">Upišite adresu.</div>
			  </div>
			  <div class="col-md-3">
				<label for="grad" class="form-label">Grad</label>
				<input type="text" class="form-control" name="grad" value ="<?php echo $driver['grad']  ?>" required>
				  <div class="invalid-feedback">Upišite Grad.</div>
			  </div>
			  <div class="col-md-3">
				<label for="postanskiBroj" class="form-label">Poštanski broj</label>
				<input type="text" class="form-control" name="postanskiBroj" value ="<?php echo $driver['postanskiBroj']  ?>" required>
				  <div class="invalid-feedback">Upišite poštanski broj.</div>
			  </div>
			  <div class="col-md-4">
				<label for="drzava" class="form-label">Država</label>
				<input type="text" class="form-control" name="drzava" value ="<?php echo $driver['drzava']  ?>" required>
				  <div class="invalid-feedback">Upišite Državu.</div>
			  </div>
			<div class="col-md-3">
				  <label for="datepicker2" class="form-label">Datum rođenja</label>
				<div class="input-group mb-4">
				  <i class="bi bi-calendar-date input-group-text"></i>
				  <input type="text" name="dob" id="datepicker2" class="datepicker_input form-control" value ="<?php echo $driver['dob']  ?>" required>
					<div class="invalid-feedback">Odaberite datum rođenja.</div>
				</div>
			</div>
			  <div class="col-md-3">
				<label for="oib" class="form-label">OIB</label>
				<input type="text" class="form-control" name="oib" value ="<?php echo $driver['oib']  ?>" required>
				  <div class="invalid-feedback">Upišite OIB.</div>
			  </div>
						<hr class="hr" />

			  <div class="col-md-12">
				  <label for="inputState" class="form-label">Platforma: </label></br>
				<div class="form-check form-check-inline">
				  <input class="form-check-input" name="uberCheck" type="hidden" id="inlineCheckbox1" value="0">
				  <input class="form-check-input" name="uberCheck" type="checkbox" id="inlineCheckbox1" value ="1" <?php if($driver['uber'] != 0){ echo 'checked';}  ?> >
				  <label class="form-check-label" for="inlineCheckbox1">Uber</label>
				</div>
				<div class="form-check form-check-inline">
				  <input class="form-check-input" name="boltCheck" type="hidden" id="inlineCheckbox2" value="0">
				  <input class="form-check-input" name="boltCheck" type="checkbox" id="inlineCheckbox2" value ="1" <?php if($driver['bolt'] != 0){ echo 'checked';}  ?> >
				  <label class="form-check-label" for="inlineCheckbox2">Bolt</label>
				</div>
				<div class="form-check form-check-inline">
				  <input class="form-check-input" name="taximetarCheck" type="hidden" id="inlineCheckbox3" value="0">
				  <input class="form-check-input" name="taximetarCheck" type="checkbox" id="inlineCheckbox3" value ="1" <?php if($driver['taximetar'] != 0){ echo 'checked';}  ?> >
				  <label class="form-check-label" for="inlineCheckbox3">Taximetar</label>
				</div>
				<div class="form-check form-check-inline">
				  <input class="form-check-input" name="myPosCheck" type="hidden" id="inlineCheckbox4" value="0">
				  <input class="form-check-input" name="myPosCheck" type="checkbox" id="inlineCheckbox4" value ="1" <?php if($driver['myPos'] != 0){ echo 'checked';}  ?>>
				  <label class="form-check-label" for="inlineCheckbox4">MyPos</label>
				</div>
			</div>
						<hr class="hr" />
			  <div class="col-md-3">
				<label for="bolt_unique_id" class="form-label">Bolt unique ID</label>
				<input type="text" class="form-control" name="bolt_unique_id"  value ="<?php echo $driver['bolt_unique_id']  ?>" required>
				  <div class="invalid-feedback">Bolt unique ID je obavezan.</div>
			  </div>
			  <div class="col-md-3">
				<label for="myPos_unique_id" class="form-label">MyPos unique ID</label>
				  <input type="text" class="form-control" name="myPos_unique_id"  value ="<?php echo $driver['myPos_unique_id']  ?>" required>
				  <div class="invalid-feedback">MyPos unique ID je obavezan.</div>
			  </div>
			  <div class="col-md-3">
				<label for="uber_unique_id" class="form-label">Uber unique ID</label>
				  <input type="text" class="form-control" name="uber_unique_id"  value ="<?php echo $driver['uber_unique_id']  ?>" required>
				  <div class="invalid-feedback">Uber unique ID je obavezan.</div>
			  </div>
			  <div class="col-md-3">
				<label for="taximetar_unique_id" class="form-label">Email na taximetru</label>
				  <input type="text" class="form-control" name="taximetar_unique_id"  value ="<?php echo $driver['taximetar_unique_id']  ?>" required>
				  <div class="invalid-feedback">Email na taximetru je obavezan.</div>
			  </div>
						<hr class="hr" />

			<div class="col-md-3">
				<label for="refered_by" class="form-label">Preporuka od</label>
				<select class="form-select" name="refered_by" aria-label="Default select example">
					 <option value ="<?php echo $driver['refered_by']  ?>" selected ><?php echo $driver['refered_by'] ?></option>
					<?php   
						foreach($drivers as $driveri){
							echo('<option value="'. $driveri['vozac'] . '">' . $driveri['vozac'] . '</option>');
						}

					?>				
				</select>
			</div>
			<div class="col-md-3">
				<label for="vrsta_nagrade" class="form-label">Vrsta nagrade</label>
				<select class="form-select" name="vrsta_nagrade" aria-label="Default select example">
				<option value ="<?php echo $driver['vrsta_nagrade']  ?>" selected ><?php echo $driver['vrsta_nagrade'] ?></option>
				  <option value="tjedno">Tjedno</option>
				  <option value="jednokratno">Jednokratno</option>
				</select>
			</div>			
			  <div class="col-md-3">
				<label for="referal_reward" class="form-label">Nagrada preporučitelju</label>
				<input type="text" class="form-control" name="referal_reward"  value ="<?php echo $driver['referal_reward']  ?>">
			  </div>
						<hr class="hr" />

			<div class="col-md-3">
				<label for="nacin_rada" class="form-label">Način rada</label>
				<select class="form-select" name="nacin_rada" aria-label="Default select example" required>
				<option value ="<?php echo $driver['nacin_rada']  ?>" selected ><?php echo $driver['nacin_rada'] ?></option>
				  <option value="provizija">Provizija</option>
				  <option value="placa">Plaća</option>
				</select>
			</div>			
				<div class="col-md-4">
				<label for="vrsta_provizije" class="form-label">Vrsta provizije</label>
				<select class="form-select" name="vrsta_provizije" aria-label="Default select example">
				<option value ="<?php echo $driver['vrsta_provizije']  ?>" selected ><?php echo $driver['vrsta_provizije'] ?></option>
				  <option value="Fiksna">Fiksna</option>
				  <option value="Postotak">Postotak</option>
				</select>
			</div>
			<div class="col-md-4">
				<label for="iznos_provizije" class="form-label">Iznos provizije</label>
				<select class="form-select" name="iznos_provizije" aria-label="Default select example">
				<option value ="<?php echo $driver['iznos_provizije']  ?>" selected ><?php echo $driver['iznos_provizije'] ?></option>
					<option value="40">40 €</option>
				  <option value="10">10 %</option>
				  <option value="45">45 %(Gorivo i auto)</option>
				  <option value="70">Sezona 70 €</option>
				  <option value="60">Sezona 60 €</option>
				  <option value="80">Sezona 80 €</option>
				</select>
			</div>
			<div class="col-md-4">
				<label for="popust_na_proviziju" class="form-label">Popust na proviziju</label>
				<select class="form-select" name="popust_na_proviziju" aria-label="Default select example">
				<option value ="<?php echo $driver['popust_na_proviziju']  ?>" selected ><?php echo $driver['popust_na_proviziju'] ?></option>
				  <option value="0">0 €</option>
				  <option value="7">7 €</option>
				  <option value="13">13 €</option>
				  <option value="1">1 %</option>
				  <option value="2">2 %</option>
				  <option value="3,4">3,4 %</option>
				  <option value="3">3 %</option>
				  <option value="4">4 %</option>
				  <option value="5">5 %</option>
				  <option value="6">6 %</option>
				  <option value="7">7 %</option>
				  <option value="8">8 %</option>
				  <option value="9">9 %</option>
				  <option value="5">5 €</option>
				  <option value="-5">-5 €</option>
				  <option value="-10">-10 €</option>
				  <option value="-15">-15 €</option>
				  <option value="10">10 €</option>
				  <option value="15">15 €</option>
				  <option value="20">20 €</option>
				  <option value="25">25 €</option>
				  <option value="27">27 €</option>
				  <option value="30">30 €</option>
				  <option value="35">35 €</option>
				  <option value="40">40 €</option>
				</select>
			</div>
						
			  <div class="col-md-3">
				<label for="provizijaNaljepnice" class="form-label">Provizija na naljepnice</label>
				<input type="text" class="form-control" name="provizijaNaljepnice"  value ="<?php echo $driver['provizijaNaljepnice']  ?>">
			  </div>
			<div class="col-md-3">
				<label for="popust_na_taximetar" class="form-label">Popust na taximetar</label>
				<select class="form-select" name="popust_na_taximetar" aria-label="Default select example">
				<option value ="<?php echo $driver['popust_na_taximetar']  ?>" selected ><?php if($driver['popust_na_taximetar'] != 0){ echo '1 €';} else{ echo '0 €';} ?></option>
					<option value="1">1 €</option>
				  <option value="0">0 €</option>
				</select>
			</div>
						<hr class="hr" />

		<div class="col-md-2">
			<label for="isplata" class="form-label">Isplata na </label>
			<select class="form-select" name="isplata" aria-label="Default select example">
				<option value ="<?php echo $driver['isplata']  ?>" selected ><?php if($driver['isplata'] != 'Revolut'){ echo 'HR IBAN';} else{ echo 'Revolut';} ?></option>
						<?php if($driver['isplata'] != 'Revolut'){echo '<option value="Revolut">Revolut</option>';} 
							  if($driver['isplata'] != 'hrIBAN'){echo '<option value="hrIBAN">HR IBAN</option>';}
						?>
			</select>
		</div>
			  <div class="col-md-3">
				<label for="IBAN" class="form-label">IBAN računa </label>
				<input type="text" class="form-control" name="IBAN"  placeholder="HR..." value ="<?php echo $driver['IBAN']  ?>">
			  </div>
			  <div class="col-md-3">
				<label for="zasticeniIBAN" class="form-label">IBAN zaštičenog računa </label>
				<input type="text" class="form-control" name="zasticeniIBAN"  placeholder="HR..." value ="<?php echo $driver['zasticeniIBAN']  ?>">
			  </div>
			  <div class="col-md-3">
				<label for="strani_IBAN" class="form-label">IBAN stranog računa </label>
				<input type="text" class="form-control" name="strani_IBAN"  placeholder="LT..." value ="<?php echo $driver['strani_IBAN']  ?>">
			  </div>
										<hr class="hr" />
			  <div class="col-md-4">
				<label for="blagMin" class="form-label">Blagajnički minimum </label>
				<input type="text" class="form-control" name="blagMin"  value ="<?php echo $driver['blagMin']  ?>" required>
				  <div class="invalid-feedback">Blagajnički minimum je obavezan.</div>
			  </div>
			  <div class="col-md-4">
				<label for="blagMax" class="form-label">Blagajnički maksimum </label>
				<input type="text" class="form-control" name="blagMax"  value ="<?php echo $driver['blagMax']  ?>" required>
				  <div class="invalid-feedback">Blagajnički maksimum je obavezan.</div>
			  </div>
			  <div class="col-md-3">
				<label for="pocetakRada" class="form-label">Početak rada </label>
				<input type="text" class="form-control other-datepicker" name="pocetak_rada"  value ="<?php echo $driver['pocetak_rada']  ?>">
				  
			  </div>
			  <div class="col-12">
				<button type="submit" class="btn btn-primary">Spremi promjene</button>
			  </div>
			</form>
				
			<hr class="hr" />
				
				
			<form id="form2" class="row g-3 needs-validation" action="<?php echo base_url('index.php/AdminController/driverPrijavaUpdate');?>" method="post" novalidate>
				
				<div class="col-md-4">
					<label for="prijava" class="form-label">Prijava</label>
					<select class="form-select" name="prijava" aria-label="Default select example" id="prijavaSelect">
				<option value ="<?php echo $driver['prijava']  ?>" selected ><?php if($driver['prijava'] != 0){ echo 'DA';} else{ echo 'NE';} ?></option>
						<?php if($driver['prijava'] == 0){ echo '<option value="1">DA</option>';} else{ echo '<option value="0">NE</option>';} ?>
					</select>
				</div>
			  <div class="col-md-3">
				<label for="pocetakPrijave" class="form-label">Početak prijave </label>
				<input type="text" class="form-control other-datepicker" name="pocetak_prijave"  value ="<?php echo $driver['pocetak_prijave']  ?>">
			  </div>
				<div class="col-md-3">
					<label for="radniOdnos" class="form-label">Radni odnos</label>
					<select class="form-select" name="radniOdnos" aria-label="Default select example">
					<option value ="<?php echo $driver['radniOdnos'] ?>" selected > <?php echo $driver['radniOdnos'] ?> </option>
					<option value ="obrtnik"> Obrtnik </option>
					<option value ="ugovor" > Ugovor o radu</option>
					<option value ="student"> Studentski ugovor </option>
					<option value ="obrtnik_sa_agregatorom"> Obrtnik sa Agregatorom </option>
					</select>
				</div>
				<div class="col-md-4">
					<label for="broj_sati" class="form-label">Broj sati</label>
					<select class="form-select" name="broj_sati" aria-label="Default select example">
					<option value ="<?php echo $driver['broj_sati'] ?>" selected > <?php echo $driver['broj_sati'] ?> </option>
					<option value ="2"> 2 </option>
					<option value ="1.5" > 1,5 </option>
					<option value ="3.29" > 3h i 29min </option>
					<option value ="4"> 4 </option>
					<option value ="6"> 6 </option>
					<option value ="8"> 8 </option>
					</select>
				</div>
			  <div class="col-md-3">
			  <input type="hidden" class="form-control" name="id"  value ="<?php echo $driver['id']  ?>">
			  <input type="hidden" class="form-control" name="ime"  value ="<?php echo $driver['ime']  ?>">
			  <input type="hidden" class="form-control" name="prezime"  value ="<?php echo $driver['prezime']  ?>">
			  <input type="hidden" class="form-control" name="dob"  value ="<?php echo $driver['dob']  ?>">
			  <input type="hidden" class="form-control" name="oib"  value ="<?php echo $driver['oib']  ?>">
			  <input type="hidden" class="form-control" name="IBAN"  value ="<?php echo $driver['IBAN']  ?>">
			  <input type="hidden" class="form-control" name="zasticeniIBAN"  value ="<?php echo $driver['zasticeniIBAN']  ?>">
			  <input type="hidden" class="form-control" name="strani_IBAN"  value ="<?php echo $driver['strani_IBAN']  ?>">
			<label for="pocetak_promjene" class="form-label">Početak promjene </label>
				<input type="text" class="form-control other-datepicker" name="pocetak_promjene"  value ="<?php echo $driver['pocetak_promjene']  ?>" required>
				  <div class="invalid-feedback">Početak promjene je obavezan.</div>
			  </div>
		  <div class="col-md-6">
			<label for="radno_mjesto" class="form-label">Radno mjesto</label>
			<input type="text" class="form-control" name="radno_mjesto" id="radno_mjesto" value="<?php echo $driver['radno_mjesto']  ?>">
		  </div>
			<div class="col-md-4">
				<label for="aktivan" class="form-label">Aktivan</label>
				<select class="form-select" name="aktivan" aria-label="Default select example"  id="aktivanSelect">
				<option value ="<?php echo $driver['aktivan']  ?>" selected ><?php if($driver['aktivan'] != 0){ echo 'DA';} else{ echo 'NE';} ?></option>
					<option value="1">DA</option>
				  <option value="0">NE</option>
				</select>
			</div>
			 <div class="col-12">
				<button type="submit" class="btn btn-primary">Spremi promjene vezano za prijavu</button>
			  </div>

				</form>
			</div>
		</div>
	</div>

			<div class="accordion-item">
			<h2 class="accordion-header" id="flush-headingTwo">
			  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
				Obračuni
			  </button>
			</h2>
			<div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
			  <div class="accordion-body" style="max-height: 800px; overflow: auto;">

		<?php 
			if(isset($driverObracun)): ?>
				<?php foreach ($driverObracun as $driver):?>
				  
			<?php	$driver_slug_id = str_replace(' ','_', $driver['vozac']); 
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
			<div class="col-2"></div>
			<div class="col col-xxl-8 col-xl-8 col-lg-12 col-m-12 col-sm-12">
				<div id="<?php echo $driver_slug_id ?>" class="card border-danger text-white bg-secondary mt-3">
					<div class="card-header text-center"><h4 class="fw-bold"><a href="<?php echo base_url('/index.php/drivers/'). '/' .$driver['vozac_id']?>"><?php echo $driver['vozac']; ?></a></h4>
					<h6 class=" border-dark"><a href="<?php echo base_url('/index.php/editirajObracun/'). '/' .$driver['id']?>"><?php echo $driver['raspon']; ?></a></h6>
					</div>
					<div class="card-body">
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
									<h4>MyPos</h4>
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
								<h4>Troškovi</h4>
								<ul class="list-group list-group-flush border-danger">
									<li class="list-group-item fs-6 bg-danger">Provizija: <?php echo $driver['provizija']; ?> €</li>
									<?php if($driver['taximetar'] !=0): ?>
									<li class="list-group-item fs-6 bg-danger">Taximetar: <?php echo $driver['taximetar']; ?> €</li>
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
						<div class="col-2"></div>
		
				<?php endforeach ?>
							<?php endif ?>
				</div>
			</div>
			</div>
			<div class ="accordion-item">
				<h2 class="accordion-header" id="flush-headingThree">
			  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
				Dokumenti
			  </button>
			</h2>
				<div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
			  <div class="accordion-body">
					<div class="box">
						<div class="container">
							<div class="row">
								
								<div class="col"> 
								
									<a class="btn btn-outline-info" href="<?php echo base_url('/index.php/ugovoroRadu/'). '/' .$driverId ?>" target="_blank" rel="noopener noreferrer" role="button">Ugovor o radu</a>
								</div>
								<div class="col">
									<a class="btn btn-outline-info" href="<?php echo base_url('/index.php/ugovoroNajmu/'). '/' .$driverId ?>" target="_blank" rel="noopener noreferrer" role="button">Ugovor o najmu</a>
								</div>
								<div class="col">
									<a class="btn btn-outline-info" href="<?php echo base_url('/index.php/blagajnickiminmax/'). '/' .$driverId ?>" target="_blank" rel="noopener noreferrer" role="button">Blagajnički min/max</a>
								</div>
								<div class="col">
									<a class="btn btn-outline-info" href="<?php echo base_url('/index.php/radniOdnos/'). '/' .$driverId ?>" target="_blank" rel="noopener noreferrer" role="button">Odnos Uber</a>
								</div>
								<div class="col">
									<a class="btn btn-outline-info" href="<?php echo base_url('/index.php/radniOdnosBolt/'). '/' .$driverId ?>" target="_blank" rel="noopener noreferrer" role="button">Odnos Bolt</a>
								</div>
								<?php if($aneks == TRUE): ?>
								<div class="col">
									<a class="btn btn-outline-info" href="<?php echo base_url('/index.php/aneksUgovora/'). '/' .$driverId ?>" target="_blank" rel="noopener noreferrer" role="button">Aneks Ugovora</a>
								</div>
								<?php endif ?>
								
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class ="accordion-item">
				<h2 class="accordion-header" id="flush-headingFour">
			  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
				Vozilo
			  </button>
			</h2>
				<div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
			  <div class="accordion-body">
					<div class="box">
						<div class="container">
							<div class="row">
								
								<div class="col-12"> 
									<?php if(isset($vozilo)): ?>
									<form class="row g-3" action="<?php echo base_url('index.php/AdminController/addVoziloUpdate');?>" method="post">
									<?php else: ?>
									<form class="row g-3" action="<?php echo base_url('index.php/AdminController/addVoziloSave');?>" method="post">
									<?php endif ?>
									<div class="col-6">
											<label for="proizvodac" class="form-label">Proizvođač</label>
											<?php 
												if(isset($vozilo)): ?>
												<input type="text" name ="proizvodac" class="form-control" id="proizvodac" 
												   value ="<?php echo $vozilo['proizvodac']?>">
											<?php
												else: ?>
												<input type="text" name ="proizvodac" class="form-control" id="proizvodac" placeholder="Proizvođač" >
											<?php endif  ?>
										</div>	
										<div class="col-6">
											<label for="model" class="form-label">Model</label>
											<?php 
												if(isset($vozilo)): ?>
												<input type="hidden" name="id" value="<?php echo $vozilo['id']?>">
												<input type="text" name ="model" class="form-control" id="model" 
												   value ="<?php echo $vozilo['model']?>">
											<?php
												else: ?>
												<input type="text" name ="model" class="form-control" id="model" placeholder="Model" >
											<?php endif  ?>
										</div>	
										<div class="col-6">
											<label for="reg" class="form-label">Registracija</label>
											<?php 
												if(isset($vozilo)): ?>
												<input type="text" name ="reg" class="form-control" id="reg" 
												   value ="<?php echo $vozilo['reg']?>">
											<?php
												else: ?>
												<input type="text" name ="reg" class="form-control" id="reg" placeholder="ZG-1234-AB" >
											<?php endif  ?>
										</div>	
										<div class="col-6">
											<label for="godina" class="form-label">Godina</label>
											<?php 
												if(isset($vozilo)): ?>
												<input type="text" name ="godina" class="form-control" id="godina" 
												   value ="<?php echo $vozilo['godina']?>">
											<?php
												else: ?>
												<input type="text" name ="godina" class="form-control" id="godina" placeholder="2020" >
											<?php endif  ?>
										</div>	
										<div class="col-6">
											<label for="cijena_tjedno" class="form-label">Cijena tjedno</label>
											<?php 
												if(isset($vozilo)): ?>
												<input type="text" name ="cijena_tjedno" class="form-control" id="cijena_tjedno" 
												   value ="<?php echo $vozilo['cijena_tjedno']?>">
											<?php
												else: ?>
												<input type="text" name ="cijena_tjedno" class="form-control" id="cijena_tjedno" placeholder="150" >
											<?php endif  ?>
										</div>	
										<div class="col-6">
											<label for="cijena_po_km" class="form-label">Cijena po km</label>
											<?php 
												if(isset($vozilo)): ?>
												<input type="text" name ="cijena_po_km" class="form-control" id="cijena_po_km" 
												   value ="<?php echo $vozilo['cijena_po_km']?>">
											<?php
												else: ?>
												<input type="text" name ="cijena_po_km" class="form-control" id="cijena_po_km" placeholder="0.75" >
											<?php endif  ?>
										</div>	
										<div class="col-md-4">
											<label for="placa_firma" class="form-label">Tko plaća vozilo ? </label>
											<select class="form-select" name="placa_firma" aria-label="Default select example">
												<?php 
												if(isset($vozilo)): ?>
											<option value ="<?php echo $vozilo['placa_firma']  ?>" selected ><?php if($vozilo['placa_firma'] != 0){ echo 'Firma';} else{ echo 'Vozač';} ?></option>
											  <option value="0">Vozač</option>
												<option value="1">Firma</option>
												<?php
												else: ?>
											  <option value="0">Vozač</option>
												<option value="1">Firma</option>
											<?php endif  ?>
											</select>
										</div>
										<input type="hidden" class="form-control" name="vozac_id"  value ="<?php echo $driverId  ?>">
										<div class="col-6">
											<label for="change_date" class="form-label">Datum promjene</label>
											<?php 
												if(isset($vozilo)): ?>
												<input type="text" name ="change_date" class="form-control" id="change_date" 
												   value ="<?php echo $vozilo['change_date']?>">
											<?php
												else: ?>
												<input type="text" name ="change_date" class="form-control" id="change_date" value="" >
											<?php endif  ?>
										</div>	
										<div class="col-12">
											<button type="submit" class="btn btn-primary">Spremi promjene</button>
										  </div>
									</form>
								</div>
								
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>	
						<div class ="accordion-item">
				<h2 class="accordion-header" id="flush-headingFive">
			  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
				Napomene
			  </button>
			</h2>
				<div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
			  <div class="accordion-body">
					<div class="box">
						<div class="container">
							<div class="row">
								<ul class="list-group">
									<?php if(isset($napomene)): ?>
										<?php foreach($napomene as $napomena): ?>
									  <li class="list-group-item"> <?php echo $napomena['timestamp'] .' - ' .$napomena['user'] .' je dodao/la sljedeću napomenu "' .$napomena['napomena'] .'"' ?> </li>
										<?php endforeach ?>
									<?php else: ?>
									  <li class="list-group-item">Nema napomena</li>
									<?php endif?>
								</ul>
								<form class="row g-3" action="<?= base_url('index.php/AdminController/napomenaSave');?>" method="post">
									<div class="input-group">
										<span class="input-group-text">Napiši napomenu</span>
										<textarea class="form-control" aria-label="napomena" name="napomena"></textarea>
										<input type="hidden" name="driver_id" value="<?= $driverId ?>">
										<input type="hidden" name="user" value="<?= $name ?>">
										<input type="hidden" name="timestamp" value="<?= time() ?>">
									</div>
									<div class="col-12">
										<button type="submit" class="btn btn-primary">Spremi napomenu</button>
									</div>
								</form>
							</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class ="accordion-item">
				<h2 class="accordion-header" id="flush-headingSix">
			  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix">
				Prijave
			  </button>
			</h2>
		<div id="flush-collapseSix" class="accordion-collapse collapse" aria-labelledby="flush-headingSix" data-bs-parent="#accordionFlushExample">
			<div class="accordion-body">
				<div class="box">
					<div class="container">
						<div class="row">
		 <table id="example" class="table table-dark table-sm table-striped" style="font-size: 14px; width:80%">
			<thead>
				<tr>
					<th scope="col">Broj unosa</th>
					<th scope="col">Ime</th>
					<th scope="col">Prezime</th>
					<th scope="col">OIB</th>
					<th scope="col">Datum Rođenja</th>
					<th scope="col">Početak prijave</th>
					<th scope="col">Broj sati</th>
					<th scope="col">IBAN</th>
					<th scope="col">Zaštičeni IBAN</th>
					<th scope="col">Strani IBAN</th>
					<th scope="col">Radno mjesto</th>
					<th scope="col">Prekid rada</th>
					<th scope="col">Početak promjene</th>
					<th scope="col">Prvi unos</th>
					<th scope="col">Nadopuna</th>
					<th scope="col">Vrijeme unosa</th>
					<th scope="col">Administrator</th>
				</tr>
   			</thead>
            <tbody class="text-nowrap">
				<?php $ids = array(); ?>
				<?php foreach ($prijave as $radnik): ?>
					<?php
						$currentVozacId = $radnik['vozac_id'];
						if (in_array($currentVozacId, $ids)) {
							continue; // Skip if the ID has already been processed
						}
						$moreOccurrences = array_filter($prijave, function ($item) use ($currentVozacId) {
							return $item['vozac_id'] === $currentVozacId;
						});
						$num = count($moreOccurrences);
						if (count($moreOccurrences) > 1):
							// Sort the $moreOccurrences array by 'id' in descending order
						usort($moreOccurrences, function($a, $b) {
								return $b['id'] <=> $a['id'];
							});
					?>
					<?php $count = 0 ?>
					<?php foreach($moreOccurrences as $radnik): ?>
						<?php if($count === 0): ?>
						<tr>
							<!-- Display data for the latest row based on highest 'id' -->
							<th scope="row"><?php echo $num; ?></th>
							<td><a href="<?php echo base_url('/index.php/drivers/'). '/' .$radnik['vozac_id']?>" style="text-decoration: none; color: inherit;"><?php echo $radnik['ime']; ?></a></td>
							<td><?php echo $radnik['prezime']; ?></td>
							<td><?php echo $radnik['OIB']; ?></td>
							<td><?php echo $radnik['dob']; ?></td>
							<td><?php echo $radnik['pocetak_prijave']; ?></td>
							<td><?php echo $radnik['broj_sati']; ?></td>
							<td><?php echo $radnik['IBAN']; ?></td>
							<td><?php echo $radnik['zasticeniIBAN']; ?></td>
							<td><?php echo $radnik['strani_IBAN']; ?></td>
							<td><?php echo $radnik['radno_mjesto']; ?></td>
							<td><?php echo $radnik['prekid_rada']; ?></td>
							<td><?php echo $radnik['pocetak_promjene']; ?></td>
							<td><?php if($radnik['prvi_unos'] != 0) echo 'DA'; else echo 'NE'; ?></td>
							<td><?php if($radnik['nadopuna'] != 0) echo 'DA'; else echo 'NE'; ?></td>
							<td><?php echo $radnik['timestamp']; ?></td>
							<td><?php echo $radnik['administrator']; ?></td>
						</tr>
						<?php else: ?>
						<tr>
							<!-- Display data for the latest row based on highest 'id' -->
							<th scope="row"><i class="bi bi-arrow-90deg-up ms-3"></i></th>
							<td><a href="<?php echo base_url('/index.php/drivers/'). '/' .$radnik['vozac_id']?>" style="text-decoration: none; color: inherit;"><?php echo $radnik['ime']; ?></a></td>
							<td><?php echo $radnik['prezime']; ?></td>
							<td><?php echo $radnik['OIB']; ?></td>
							<td><?php echo $radnik['dob']; ?></td>
							<td><?php echo $radnik['pocetak_prijave']; ?></td>
							<td><?php echo $radnik['broj_sati']; ?></td>
							<td><?php echo $radnik['IBAN']; ?></td>
							<td><?php echo $radnik['zasticeniIBAN']; ?></td>
							<td><?php echo $radnik['strani_IBAN']; ?></td>
							<td><?php echo $radnik['radno_mjesto']; ?></td>
							<td><?php echo $radnik['prekid_rada']; ?></td>
							<td><?php echo $radnik['pocetak_promjene']; ?></td>
							<td><?php if($radnik['prvi_unos'] != 0) echo 'DA'; else echo 'NE'; ?></td>
							<td><?php if($radnik['nadopuna'] != 0) echo 'DA'; else echo 'NE'; ?></td>
							<td><?php echo $radnik['timestamp']; ?></td>
							<td><?php echo $radnik['administrator']; ?></td>
						</tr>
				
						<?php endif ?>
					<?php $count += 1 ?>
					<?php endforeach ?>
						<?php $ids[] = $radnik['vozac_id']; ?>
					<?php else: ?>
					
				<tr>
							<th scope="row"><?php echo $num; ?></th>
							<td><a href="<?php echo base_url('/index.php/drivers/'). '/' .$radnik['vozac_id']?>" style="text-decoration: none; color: inherit;"><?php echo $radnik['ime']; ?></a></td>
							<td><?php echo $radnik['prezime']; ?></td>
							<td><?php echo $radnik['OIB']; ?></td>
							<td><?php echo $radnik['dob']; ?></td>
							<td><?php echo $radnik['pocetak_prijave']; ?></td>
							<td><?php echo $radnik['broj_sati']; ?></td>
							<td><?php echo $radnik['IBAN']; ?></td>
							<td><?php echo $radnik['zasticeniIBAN']; ?></td>
							<td><?php echo $radnik['strani_IBAN']; ?></td>
							<td><?php echo $radnik['radno_mjesto']; ?></td>
							<td><?php echo $radnik['prekid_rada']; ?></td>
							<td><?php echo $radnik['pocetak_promjene']; ?></td>
							<td><?php if($radnik['prvi_unos'] != 0) echo 'DA'; else echo 'NE'; ?></td>
							<td><?php if($radnik['nadopuna'] != 0) echo 'DA'; else echo 'NE'; ?></td>
							<td><?php echo $radnik['timestamp']; ?></td>
							<td><?php echo $radnik['administrator']; ?></td>
				</tr>
				<?php $ids[] = $radnik['vozac_id']; ?>
				<?php endif ?>
				<?php endforeach; ?>				
				
				
            </tbody>			
		</table>
						</div>
					</div>
				</div>
			</div>
		</div>
</div>
<div class="d-flex justify-content-end fixed-bottom fixed-right">
    <!-- Create Previous and Next buttons based on the active item -->
    <?php if ($activeIndex > 0): ?>
        <a href="<?php echo base_url('/index.php/drivers/') . '/' . $drivers[$activeIndex - 1]['id'] ?>"
           class="btn btn-primary me-2 mb-5">Previous</a>
    <?php endif ?>

    <?php if ($activeIndex < count($drivers) - 1): ?>
        <a href="<?php echo base_url('/index.php/drivers/') . '/' . $drivers[$activeIndex + 1]['id'] ?>"
           class="btn btn-primary me-5 mb-5">Next</a>
    <?php endif ?>
</div>	
		
		</div>
		
			
			
		</div>
	</div>
</div>




<!-- Bootstrap 5 JavaScript Bundle with Popper -->

<!-- Vanilla Datepicker JS -->
<script src='https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.1.4/dist/js/datepicker-full.min.js'></script>
<script>
$(document).ready(function() {
    // Initialize datepicker with 'dd/mm/yyyy' format for specific element
    $('#datepicker2').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true
    });

    // Initialize datepicker with 'yyyy-mm-dd' format for other elements
    $('.other-datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });

    // Helper function to format the date as "yyyy-mm-dd"
    function formatDate(date) {
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        var day = date.getDate();
        return year + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;
    }
});
</script>

		
		
		
		<script>
    // Get reference to 'Aktivan' select
    const aktivanSelect = document.getElementById('aktivanSelect');
    
    // Get reference to 'Prijava' select
    const prijavaSelect = document.getElementById('prijavaSelect');
    
    // Add event listener to 'Aktivan' select for change event
    aktivanSelect.addEventListener('change', function() {
        // If 'Aktivan' select value changes to 0
        if (this.value === '0') {
            // Set 'Prijava' select value to 0
            prijavaSelect.value = '0';
        }
    });
</script>
		
<script>
(function () {
    'use strict';

    var form1 = document.getElementById('form1');
    var form2 = document.getElementById('form2');

    form1.addEventListener('submit', function (event) {
        if (!form1.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }

        form1.classList.add('was-validated');
    }, false);

    form2.addEventListener('submit', function (event) {
        if (!form2.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }

        form2.classList.add('was-validated');
    }, false);
})();
</script>		

</body>
</html>