
	<?php if(isset($validation)):?>
	<div class="alert alert-warning">
	   <?= $validation->listErrors() ?>
		<?php endif ?>

<div class="container">
	<div class="row">
		<div class="col-2"  style="display: none;">
			<ul class="list-group  border-danger">
				<?php
				$bUId = $driver['bolt_unique_id'];
				$UbUId = $driver['uber_unique_id'];
	
				
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

				<a href="<?php echo site_url('drivers/'). '/' . $dr['id'] ?>"
				   class="<?php echo $linkClass; ?>"><?php echo $dr['vozac'] ?></a>

				<?php endforeach ?>
			</ul>
	</div>
		<div class="col-12">
			
			
			<div class="row">
				<div class="d-flex align-items-center mb-3">
					<input type="text" id="searchBox" class="form-control me-2" placeholder="Ovdje pretraži vozače">
					<button class="btn btn-outline-secondary" id="toggleList">
						<i class="bi bi-chevron-down"></i> <!-- Bootstrap Icon -->
					</button>
				</div>

				<!-- Hidden List Group -->
				<ul class="list-group" id="driverList" style="display: none; max-height: 300px; overflow-y: auto;">
					<?php foreach ($drivers as $dr): ?>
						<a href="<?php echo site_url('drivers/') . '/' . $dr['id']; ?>" 
						   class="list-group-item list-group-item-action" 
						   data-driver-name="<?php echo strtolower($dr['vozac']); ?>">
							<?php echo $dr['vozac']; ?>
						</a>
					<?php endforeach; ?>
				</ul>			
			</div>
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
												<?php if (session()->has('msgKnjigovoda')){ ?>
										<div class="alert <?=session()->getFlashdata('alert-class') ?>">
											<?=session()->getFlashdata('msgKnjigovoda') ?>
										</div>
									<?php } ?>
												<?php if (session()->has('msgtaximetarlog')){ ?>
										<div class="alert <?=session()->getFlashdata('alert-class') ?>">
											<?=session()->getFlashdata('msgtaximetarlog') ?>
										</div>
									<?php } ?>

			<h2><?php echo $driver['vozac'] ?></h2>
			<div class="col-12">
				<!-- Button trigger modal -->
				<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#driverTaskModal">
				 Zadatak vezan za vozača <?php echo $driver['vozac'] ?>
				</button>

				<!-- Modal -->
				<div class="modal fade" id="driverTaskModal" tabindex="-1" aria-labelledby="driverTaskModalLabel" aria-hidden="true">
				  <div class="modal-dialog">
					<div class="modal-content">
					  <div class="modal-header">
						<h1 class="modal-title fs-5" id="driverTaskModalLabel">Dodaj novi zadatak za vozača</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					  </div>
					  <div class="modal-body">
						<form action="<?= site_url('tasks/store') ?>" method="post" class="needs-validation" novalidate>
							<!-- Title Field -->
							<div class="mb-3">
								<label for="title" class="form-label">Naslov</label>
								<input type="text" name="title" id="title" class="form-control <?= session('validation') && session('validation')->hasError('title') ? 'is-invalid' : '' ?>" value="<?= old('title') ?>" required>
								<?php if (session('validation') && session('validation')->hasError('title')): ?>
									<div class="invalid-feedback">
										<?= session('validation')->getError('title') ?>
									</div>
								<?php endif; ?>
							</div>

							<!-- Description Field -->
							<div class="mb-3">
								<label for="description" class="form-label">Zadatak</label>
								<textarea name="description" id="description" class="form-control <?= session('validation') && session('validation')->hasError('description') ? 'is-invalid' : '' ?>" rows="4" required><?= old('description') ?></textarea>
								<?php if (session('validation') && session('validation')->hasError('description')): ?>
									<div class="invalid-feedback">
										<?= session('validation')->getError('description') ?>
									</div>
								<?php endif; ?>
							</div>

							<!-- Priority Field -->
							<div class="mb-3">
								<label for="priority_id" class="form-label">Prioritet</label>
								<select name="priority_id" id="priority_id" class="form-select <?= session('validation') && session('validation')->hasError('priority_id') ? 'is-invalid' : '' ?>" required>
									<option value="" disabled <?= old('priority_id') ? '' : 'selected' ?>>Odaberi prioritet</option>
									<?php foreach ($priorities as $priority): ?>
										<option value="<?= $priority['id'] ?>" <?= old('priority_id') == $priority['id'] ? 'selected' : '' ?>><?= esc($priority['name']) ?></option>
									<?php endforeach; ?>
								</select>
								<?php if (session('validation') && session('validation')->hasError('priority_id')): ?>
									<div class="invalid-feedback">
										<?= session('validation')->getError('priority_id') ?>
									</div>
								<?php endif; ?>
							</div>

							<!-- Task Type Field -->
							<input type="hidden" value="vozac_related" name="task_type" id="task_type">
							<input type="hidden" value="<?php echo $driver['id']  ?>" name="related_entity_id" id="related_entity_id">

							<!-- Assigned Users Field -->
							<div class="mb-3">
								<label for="users" class="form-label">Kome dodjeliti zadatak</label>
								<select name="assigned_users[]" id="users" class="form-select <?= session('validation') && session('validation')->hasError('assigned_users') ? 'is-invalid' : '' ?>" multiple>
									<?php foreach ($users as $user): ?>
										<option value="<?= $user['id'] ?>" <?= in_array($user['id'], old('assigned_users') ?? []) ? 'selected' : '' ?>><?= esc($user['name']) ?></option>
									<?php endforeach; ?>
								</select>
								<?php if (session('validation') && session('validation')->hasError('assigned_users')): ?>
									<div class="invalid-feedback">
										<?= session('validation')->getError('assigned_users') ?>
									</div>
								<?php endif; ?>
							</div>

							<!-- Submit Button -->
							<button type="submit" class="btn btn-primary">Kreiraj zadatak</button>
						</form>
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
					  </div>
					</div>
				  </div>
				</div>
			</div>
			<div class="accordion accordion-flush" id="accordionFlushExample">
			<div class="accordion-item">

			<h2 class="accordion-header" id="flush-headingOne">
			<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
			Editiraj podatke o vozaču 
			</button>
			</h2>
			<div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
			<div class="accordion-body">
			<?php $VOZACID = $driver['id']; 
				
				
				?>

			<form id="form1" class="row g-3 needs-validation" action="<?php echo site_url('AdminController/driverUpdate');?>" method="post" novalidate>
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
				  <input data-date-format="mm/dd/yyyy" type="text" name="dob" id="datepicker2" class="datepicker_input form-control" value ="<?php echo $driver['dob']  ?>" required>
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
				  <input class="form-check-input" name="taximetarCheck" type="checkbox" id="taximetarCheckbox" value ="1" <?php if($driver['taximetar'] != 0){ echo 'checked';}  ?> >
				  <label class="form-check-label" for="taximetarCheckbox">Taximetar</label>
				</div>
				<div class="form-check form-check-inline">
				  <input class="form-check-input" name="myPosCheck" type="hidden" id="inlineCheckbox4" value="0">
				  <input class="form-check-input" name="myPosCheck" type="checkbox" id="inlineCheckbox4" value ="1" <?php if($driver['myPos'] != 0){ echo 'checked';}  ?>>
				  <label class="form-check-label" for="inlineCheckbox4">MyPos</label>
				</div>
				  <div class="col-md-6" id="mobTaximetarDiv" style="display: none;">
					<label for="mobTaximetar" class="form-label">Model mobitela za taximetar</label>
					<input type="text" class="form-control" name="mobTaximetar"  id="mobTaximetarInput" value ="<?php echo $driver['mobTaximetar']  ?>">
<?php 
	
	if(!empty($driver['mobTaximetar'])){
		$mobOK = true;
	}else{
		$mobOK = false;
	}
	
	
	if($whatsApp){
		// Ako flota koristi whatsApp
		if($whatsAppTaximetar){
			// Ako flota koristi whatsApp za TAXIMETAR
			if($whatsAppTaximetarBroj){
				// Ako flota koristi whatsApp za TAXIMETAR i ima upisan broj
			if (isset($vozilo['reg'])): ?>
				<!-- Put your HTML code here -->
				<?php if($mobOK): ?>
					<div class="alert alert-success">
						Svi podaci su dostupni i spremni za slanje.<a class="ms-3 btn btn-success" href="<?php echo site_url('posaljiTaximetar/'.$driver['id'])?>">Pošalji</a>
					</div>
				<?php else: ?>
					<div class="alert alert-warning">
						Nemamo upisan model mobitela.
					</div>
				<?php endif; ?>
			<?php else: ?>
				<!-- This will be shown if 'reg' is not set -->
				<div class="alert alert-warning">
					Nemamo upisanu registracijsku oznaku vozila.
				</div>
			<?php endif; 
				
			}else{
				// Ako flota koristi whatsApp za TAXIMETAR i NEMA upisan broj
				echo '<div class="alert alert-warning"> Nije upisan WhatsApp broj za Taximetar. </div>';
			}
		}else{
			// Ako flota NE koristi whatsApp za TAXIMETAR
				echo '<div class="alert alert-warning"> Flota ne koristi WhatsApp putem aplikacije za komunikaciju sa Taximetrom. </div>';
		}
	}else{
		// Ako flota NE koristi whatsApp
				echo '<div class="alert alert-warning"> Flota ne koristi WhatsApp putem aplikacije. </div>';
	}
					  ?>
												<?php if (session()->has('msgtaximetarlog')){ ?>
										<div class="alert <?=session()->getFlashdata('alert-class') ?>">
											<?=session()->getFlashdata('msgtaximetarlog') ?>
										</div>
									<?php } ?>
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
				
				<?php 
				$boltUniqueId = $driver['bolt_unique_id'];
				$uberUniqueId = $driver['uber_unique_id'];
				$taximetarUniqueId = $driver['taximetar_unique_id'];
				$myPosUniqueId = $driver['myPos_unique_id'];

				
				?>
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
				<label for="sezona" class="form-label">Radi na sezoni ?</label>
				<select class="form-select" name="sezona" aria-label="Default select example">
				<option value ="<?php echo $driver['sezona']  ?>" selected ><?php if($driver['sezona'] != 0){echo 'DA';}else{echo'NE';} ?></option>
				  <option value="1">DA</option>
				  <option value="0">NE</option>
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

<div class="col-md-6">
    <!-- Change the label to "Tjedna isplata na:" -->
    <label for="tjedna_isplata" class="form-label">Tjedna isplata na: </label>
    <select class="form-select" name="tjedna_isplata" aria-label="Select weekly payment IBAN">
        <option value="<?php echo $driver['tjedna_isplata']; ?>" selected>
            <?php 
                // Echo the correct IBAN type based on the selected value
                if($driver['tjedna_isplata'] == 'Revolut') {
                    echo 'Revolut'; 
                } elseif($driver['tjedna_isplata'] == 'hrIBAN') {
                    echo 'HR IBAN';
                } elseif($driver['tjedna_isplata'] == 'zasticeniIBAN') {
                    echo 'Zaštičeni IBAN';
                }
            ?>
        </option>
        <!-- Offer all 3 IBAN options -->
        <?php if($driver['tjedna_isplata'] != 'Revolut') { echo '<option value="Revolut">Revolut</option>'; } ?>
        <?php if($driver['tjedna_isplata'] != 'hrIBAN') { echo '<option value="hrIBAN">HR IBAN</option>'; } ?>
        <?php if($driver['tjedna_isplata'] != 'zasticeniIBAN') { echo '<option value="zasticeniIBAN">Zaštičeni IBAN</option>'; } ?>
    </select>
</div>

<div class="col-md-6">
    <!-- Add new select input for "Isplata plaće na:" -->
    <label for="isplata_place" class="form-label">Isplata plaće na: </label>
    <select class="form-select" name="isplata_place" aria-label="Select salary payment IBAN">
        <option value="<?php echo $driver['isplata_place']; ?>" selected>
            <?php 
                // Echo the correct IBAN type based on the selected value
                if($driver['isplata_place'] == 'Revolut') {
                    echo 'Revolut'; 
                } elseif($driver['isplata_place'] == 'hrIBAN') {
                    echo 'HR IBAN';
                } elseif($driver['isplata_place'] == 'zasticeniIBAN') {
                    echo 'Zaštičeni IBAN';
                }
            ?>
        </option>
        <!-- Offer all 3 IBAN options -->
        <?php if($driver['isplata_place'] != 'Revolut') { echo '<option value="Revolut">Revolut</option>'; } ?>
        <?php if($driver['isplata_place'] != 'hrIBAN') { echo '<option value="hrIBAN">HR IBAN</option>'; } ?>
        <?php if($driver['isplata_place'] != 'zasticeniIBAN') { echo '<option value="zasticeniIBAN">Zaštičeni IBAN</option>'; } ?>
    </select>
</div>

<div class="col-md-4">
    <label for="IBAN" class="form-label">IBAN računa </label>
    <input type="text" class="form-control" name="IBAN" placeholder="HR..." value="<?php echo $driver['IBAN']; ?>">
    <?php if($ibValid['ibanValid'] === true): ?>
        <div id="IBANHelp" class="form-text text-success border border-success">IBAN je ispravan</div>
    <?php else: ?>
        <div id="IBANHelp" class="form-text text-danger border border-danger">IBAN je neispravan</div>
    <?php endif; ?>
</div>

<div class="col-md-4">
    <label for="zasticeniIBAN" class="form-label">IBAN zaštičenog računa </label>
    <input type="text" class="form-control" name="zasticeniIBAN" placeholder="HR..." value="<?php echo $driver['zasticeniIBAN']; ?>">
    <?php if($ibValid['zIbanValid'] === true): ?>
        <div id="zasticeniIBANHelp" class="form-text text-success border border-success">IBAN je ispravan</div>
    <?php else: ?>
        <div id="zasticeniIBANHelp" class="form-text text-danger border border-danger">IBAN je neispravan</div>
    <?php endif; ?>
</div>

<div class="col-md-4">
    <label for="strani_IBAN" class="form-label">IBAN stranog računa </label>
    <input type="text" class="form-control" name="strani_IBAN" placeholder="LT..." value="<?php echo $driver['strani_IBAN']; ?>">
    <?php if($ibValid['sIbanValid'] === true): ?>
        <div id="strani_IBANHelp" class="form-text text-success border border-success">IBAN je ispravan</div>
    <?php else: ?>
        <div id="strani_IBANHelp" class="form-text text-danger border border-danger">IBAN je neispravan</div>
    <?php endif; ?>
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
				<button type="submit" class="btn btn-primary" <?php if($role!= 'knjigovoda'){}else{ echo 'disabled';} ?>>Spremi promjene</button>
			  </div>
			</form>
				
			<hr class="hr" />
				
				
			<form id="form2" class="row g-3 needs-validation" action="<?php echo site_url('AdminController/driverPrijavaUpdate');?>" method="post" novalidate>
				
				<div class="col-md-4">
					<label for="prijava" class="form-label">Prijava</label>
					<select class="form-select" name="prijava" aria-label="Default select example" id="prijavaSelect">
				<option value ="<?php echo $driver['prijava']  ?>" selected ><?php if($driver['prijava'] != 0){ echo 'DA';} else{ echo 'NE';} ?></option>
						<?php if($driver['prijava'] == 0){ echo '<option value="1">DA</option>';} else{ echo '<option value="0">NE</option>';} ?>
					</select>
				</div>
				<div id="vrstaZaposlenja" class="col-md-2">
					<label for="vrsta_zaposlenja" class="form-label">Vrsta zaposlenja</label>
					<select class="form-select" name="vrsta_zaposlenja" aria-label="Default select example" id="vrsta_zaposlenja">
						<option value ="<?php echo $driver['vrsta_zaposlenja'] ?>" selected > <?php echo $driver['vrsta_zaposlenja'] ?> </option>
						<option value="neodredeno">Neodređeno</option>
						<option value="odredeno">Određeno</option>
					</select>
				</div>
			  <div class="col-md-3">
				<label for="pocetakPrijave datepicker3" class="form-label" data-date-format="yyyy-mm-dd">Početak prijave </label>
				<input type="text" id="datepicker3" class="form-control datepicker_input" name="pocetak_prijave"  value ="<?php echo $driver['pocetak_prijave']  ?>">
			  </div>
			<div id="krajPrijave" class="col-md-2">
				<label for="kraj_prijave datepicker4" class="form-label">Kraj prijave </label>
				<input type="text" class="datepicker_input form-control" data-date-format="yyyy-mm-dd" id="datepicker4" name="kraj_prijave" value ="<?php echo $driver['kraj_prijave']  ?>">
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
			<label for="pocetak_promjene datepicker5" class="form-label">Početak promjene </label>
				<input type="text" class="form-control datepicker_input " data-date-format="yyyy-mm-dd" id="datepicker5" name="pocetak_promjene"  value ="<?php echo $driver['pocetak_promjene']  ?>" required>
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
				<button type="submit" id="submitBtn" class="btn btn-primary" <?php if($role!= 'knjigovoda'){}else{ echo 'disabled';} ?>>Spremi promjene vezano za prijavu</button>
			  </div>

				</form>
			</div>
		</div>
	</div>
<?php if($role != 'knjigovoda'): ?>
			<div class="accordion-item">
			<h2 class="accordion-header" id="flush-headingTwo">
			  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
				Obračuni
			  </button>
			</h2>
			<div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
			  <div class="accordion-body" style="max-height: 800px; overflow: auto;">

		<?php 
				  $ukupnoNeto = 0;
				  $ukupnoGotovina = 0;
				  $ukupnoBonusZaVozace = 0;
				  $ukupnoNapojnica = 0;
				  $ukupnoPovrati = 0;
				  $ukupnoProvizija = 0;
			if(isset($driverObracun)): ?>
				<?php foreach ($driverObracun as $driver):?>
				  
			<?php	
				  $ukupnoNeto += $driver['ukupnoNeto'];
				  $ukupnoGotovina += $driver['ukupnoGotovina'];
				  $ukupnoBonusZaVozace += $driver['bonus_ref'];
				  $ukupnoNapojnica += $driver['ukupnoNapojnica'];
				  $ukupnoPovrati += $driver['ukupnoPovrat'];
				  $ukupnoProvizija += $driver['provizija'];
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
			<div class="col-2"></div>
			<div class="col col-xxl-8 col-xl-8 col-lg-12 col-m-12 col-sm-12">
				<div id="<?php echo $driver_slug_id ?>" class="card border-danger text-white bg-secondary mt-3">
					<div class="card-header text-center"><h4 class="fw-bold"><a href="<?php echo site_url('drivers/'). '/' .$driver['vozac_id']?>"><?php echo $driver['vozac']; ?></a></h4>
					<h6 class=" border-dark"><a href="<?php echo site_url('editirajObracun/'). '/' .$driver['id']?>"><?php echo $driver['raspon']; ?></a></h6>
					</div>
					<div class="card-body">
						<?php if($koristi_activity != 0): ?>
	
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
			<?php endif ?>
			<div class ="accordion-item">
				<h2 class="accordion-header" id="flush-headingSeven">
			  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven" aria-expanded="false" aria-controls="flush-collapseSeven">
				Izvještaji
			  </button>
			</h2>
				<div id="flush-collapseSeven" class="accordion-collapse collapse" aria-labelledby="flush-headingSeven" data-bs-parent="#accordionFlushExample">
			  <div class="accordion-body">
					<div class="box">
						<div class="container">
							<div class="row">
								<div class="col-5">
									<label for="startWeek" class="form-label">Start Week:</label>
									<select id="startWeek"  class="form-select"></select>
								</div>
								<div class="col-5">
									<label for="endWeek" class="form-label">End Week:</label>
									<select id="endWeek"  class="form-select"></select>
								</div>
								<div class="col-2">
									<button id="fetchReports" class="btn btn-sm btn-primary bottom-50">Fetch Reports</button>
								</div>
								</div>
							</div>
							<div class="container mt-4">
								<h1 class="mb-4">Driver Reports</h1>
								<!-- Bootstrap Tabs -->
								<ul class="nav nav-tabs" id="reportTabs" role="tablist">
									<li class="nav-item" role="presentation">
										<button class="nav-link active" id="uber-tab" data-bs-toggle="tab" data-bs-target="#uberReports" type="button" role="tab">Uber Reports</button>
									</li>
									<li class="nav-item" role="presentation">
										<button class="nav-link" id="bolt-tab" data-bs-toggle="tab" data-bs-target="#boltReports" type="button" role="tab">Bolt Reports</button>
									</li>
									<li class="nav-item" role="presentation">
										<button class="nav-link" id="taximetar-tab" data-bs-toggle="tab" data-bs-target="#taximetarReports" type="button" role="tab">Taximetar Reports</button>
									</li>
									<li class="nav-item" role="presentation">
										<button class="nav-link" id="mypos-tab" data-bs-toggle="tab" data-bs-target="#myPosReports" type="button" role="tab">MyPos Reports</button>
									</li>
								</ul>
								<div class="tab-content mt-3">
									<!-- Uber Reports -->
									<div class="tab-pane fade show active" id="uberReports" role="tabpanel">
										<table id="uberTable" class="display table table-bordered">
											<thead>
												<tr>
													<th>Week</th>
													<th>Net Earnings</th>
													<th>Expenses</th>
													<th>Cash Payments</th>
													<th>Tips</th>
												</tr>
											</thead>
											<tbody></tbody>
										</table>
									</div>
									<!-- Bolt Reports -->
									<div class="tab-pane fade" id="boltReports" role="tabpanel">
										<table id="boltTable" class="display table table-bordered">
											<thead>
												<tr>
													<th>Gross Amount</th>
													<th>Cancel Fees</th>
													<th>Toll Fees</th>
													<th>Cash Collected</th>
													<th>Bonus</th>
												</tr>
											</thead>
											<tbody></tbody>
										</table>
									</div>
									<!-- Taximetar Reports -->
									<div class="tab-pane fade" id="taximetarReports" role="tabpanel">
										<table id="taximetarTable" class="display table table-bordered">
											<thead>
												<tr>
													<th>Week</th>
													<th>Total Revenue</th>
												</tr>
											</thead>
											<tbody></tbody>
										</table>
									</div>
									<!-- MyPos Reports -->
									<div class="tab-pane fade" id="myPosReports" role="tabpanel">
										<table id="myPosTable" class="display table table-bordered">
											<thead>
												<tr>
													<th>Week</th>
													<th>Amount</th>
													<th>Type</th>
													<th>Date Initiated</th>
												</tr>
											</thead>
											<tbody></tbody>
										</table>
									</div>
								</div>
							</div>
				  		</div>
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
								
									<a class="btn btn-outline-info" href="<?php echo site_url('ugovoroRadu/'). '/' .$driverId ?>" target="_blank" rel="noopener noreferrer" role="button">Ugovor o radu</a>
								</div>
								<div class="col">
									<a class="btn btn-outline-info" href="<?php echo site_url('ugovoroNajmu/'). '/' .$driverId ?>" target="_blank" rel="noopener noreferrer" role="button">Ugovor o najmu</a>
								</div>
								<div class="col">
									<a class="btn btn-outline-info" href="<?php echo site_url('blagajnickiminmax/'). '/' .$driverId ?>" target="_blank" rel="noopener noreferrer" role="button">Blagajnički min/max</a>
								</div>
								<div class="col">
									<a class="btn btn-outline-info" href="<?php echo site_url('radniOdnos/'). '/' .$driverId ?>" target="_blank" rel="noopener noreferrer" role="button">Odnos Uber</a>
								</div>
								<div class="col">
									<a class="btn btn-outline-info" href="<?php echo site_url('radniOdnosBolt/'). '/' .$driverId ?>" target="_blank" rel="noopener noreferrer" role="button">Odnos Bolt</a>
								</div>
								<div class="col">
									<a class="btn btn-outline-info" href="<?php echo site_url('generateCombinedPdf'). '/' .$driverId ?>" target="_blank" rel="noopener noreferrer" role="button">Kombinirani dokumenti Dupli</a>
								</div>
								<div class="col">
									<a class="btn btn-outline-info" href="<?php echo site_url('generateCombinedPdfSimple'). '/' .$driverId ?>" target="_blank" rel="noopener noreferrer" role="button">Kombinirani dokumenti</a>
								</div>
								<?php if($aneks == TRUE): ?>
								<div class="col">
									<a class="btn btn-outline-info" href="<?php echo site_url('aneksUgovora/'). '/' .$driverId ?>" target="_blank" rel="noopener noreferrer" role="button">Aneks Ugovora</a>
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
									<form class="row g-3" action="<?php echo site_url('AdminController/addVoziloUpdate');?>" method="post">
									<?php else: ?>
									<form class="row g-3" action="<?php echo site_url('AdminController/addVoziloSave');?>" method="post">
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
											<button type="submit" class="btn btn-primary"  <?php if($role!= 'knjigovoda'){}else{ echo 'disabled';} ?>>Spremi promjene</button>
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
								<form class="row g-3" action="<?= site_url('AdminController/napomenaSave');?>" method="post">
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
							<td><a href="<?php echo site_url('drivers/'). '/' .$radnik['vozac_id']?>" style="text-decoration: none; color: inherit;"><?php echo $radnik['ime']; ?></a></td>
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
							<td><a href="<?php echo site_url('drivers/'). '/' .$radnik['vozac_id']?>" style="text-decoration: none; color: inherit;"><?php echo $radnik['ime']; ?></a></td>
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
							<td><a href="<?php echo site_url('drivers/'). '/' .$radnik['vozac_id']?>" style="text-decoration: none; color: inherit;"><?php echo $radnik['ime']; ?></a></td>
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

				
			
				
<div class="row">
	<?php if($role != 'knjigovoda'): ?>
	<div class="col-12">
		<table class="table">
		  <thead>
			<tr>
			  <th scope="col">Ukupno Neto</th>
			  <th scope="col">Ukupno Gotovina</th>
			  <th scope="col">Ukupno Napojnica</th>
			  <th scope="col">Ukupno Povrati</th>
			  <th scope="col">Ukupno Bonusi</th>
			  <th scope="col">Ukupno Provizija</th>
			</tr>
		  </thead>
		  <tbody>
			<tr>
			  <td><?=$ukupnoNeto?></td>
			  <td><?=$ukupnoGotovina ?></td>
			  <td><?=$ukupnoNapojnica ?></td>
			  <td><?=$ukupnoPovrati?></td>
			  <td><?=$ukupnoBonusZaVozace?></td>
			  <td><?=$ukupnoProvizija?></td>
			</tr>
		  </tbody>
		</table>
	</div>
	<?php endif ?>
	
	<div class="col-md-12">
		<label for="daterange" class="form-label">Raspon</label>
        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
            <i class="fa fa-calendar"></i>&nbsp;
            <span>Pick a date range</span> 
            <i class="fa fa-caret-down"></i>
        </div>
	  </div>
    <table id="activityTable" class="display">
        <thead>
            <tr>
                <th>Datum</th>
                <th>Uber online</th>
                <th>Uber vrijeme vožnje</th>
                <th>Bolt online</th>
                <th>Bolt vrijeme vožnje</th>
                <th>Total online</th>
                <th>Total vrijeme vožnje</th>
            </tr>
        </thead>
        <tbody></tbody>
        <tfoot>
            <tr>
                <th colspan="5" style="text-align:right">Total:</th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
				
				
				</div>				
				
<div class="d-flex justify-content-end fixed-bottom fixed-right">
    <!-- Create Previous and Next buttons based on the active item -->
    <?php if ($activeIndex > 0): ?>
        <a href="<?php echo site_url('drivers/') . '/' . $drivers[$activeIndex - 1]['id'] ?>"
           class="btn btn-primary me-2 mb-5">Previous</a>
    <?php endif ?>

    <?php if ($activeIndex < count($drivers) - 1): ?>
        <a href="<?php echo site_url('drivers/') . '/' . $drivers[$activeIndex + 1]['id'] ?>"
           class="btn btn-primary me-5 mb-5">Next</a>
    <?php endif ?>
</div>	
		
		</div>
		
			
			
		</div>
	</div>
</div>
<?php //print_r($driverActivity); ?>



<script>
    $(document).ready(function () {
        // Fetch weeks data via AJAX
        $.ajax({
            url: '<?= site_url("/api/getReportWeeks") ?>',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                const weeks = response.uniqueWeeks;

                // Function to convert YYYYWW to date range format (dd.mm.yyyy - dd.mm.yyyy)
                function getWeekRange(week) {
                    const year = parseInt(week.substring(0, 4));
                    const weekNumber = parseInt(week.substring(4));
                    const firstDay = new Date(Date.UTC(year, 0, (weekNumber - 1) * 7));
                    const lastDay = new Date(Date.UTC(year, 0, (weekNumber - 1) * 7 + 6));
                    const formatDate = (date) =>
                        `${date.getUTCDate().toString().padStart(2, '0')}.${(date.getUTCMonth() + 1)
                            .toString()
                            .padStart(2, '0')}.${date.getUTCFullYear()}`;
                    return `${formatDate(firstDay)} - ${formatDate(lastDay)}`;
                }

                // Populate dropdowns
                weeks.forEach((week) => {
                    const range = getWeekRange(week);
                    $('#startWeek').append(new Option(range, week));
                    $('#endWeek').append(new Option(range, week));
                });

                // Set default values (first and last week)
                $('#startWeek').val(weeks[0]);
                $('#endWeek').val(weeks[weeks.length - 1]);
            },
            error: function (xhr, status, error) {
                console.error("Error fetching weeks:", error);
            }
        });

        // Fetch reports on button click
        $('#fetchReports').click(function () {
            const startWeek = $('#startWeek').val();
            const endWeek = $('#endWeek').val();

            $.ajax({
                url: '<?= site_url("/api/getDriversReports") ?>',
                type: 'POST',
                data: {
                    startWeek: startWeek,
                    endWeek: endWeek,
                    boltUniqueId: '<?= $boltUniqueId ?>',
                    uberUniqueId: '<?= $uberUniqueId ?>',
                    taximetarUniqueId: '<?= $taximetarUniqueId ?>',
                    myPosUniqueId: '<?= $myPosUniqueId ?>'
                },
                dataType: 'json',
                success: function (response) {
					 console.log('AJAX Response:', response); 
                if (response.uberReports) {
                    // Destroy existing DataTable if it exists
                    if ($.fn.DataTable.isDataTable('#uberTable')) {
                        $('#uberTable').DataTable().destroy();
                    }

                    // Initialize the DataTable with the fetched data
                    $('#uberTable').DataTable({
                        data: response.uberReports,
 						lengthMenu: [
							[10, 25, 50, -1],
							[10, 25, 50, 'All']
						],
						"responsive": true, "lengthChange": false, "autoWidth": false,
						paging: true, // Enable pagination
						ordering: true, // Enable sorting
						info: false, // Disable the information display
						dom: 'Bfrtip', // Show buttons for exporting
						buttons: [
							'copy', 'csv', 'excel', 'pdf', 'print','pageLenght'
						],
                       columns: [
                            { title: "Week", data: "report_for_week" },
                            { title: "Net Earnings", data: "Ukupna_zarada_Neto_cijena" },
                            { title: "Costs", data: "Povrati_i_troskovi" },
                            { title: "Cash Paid", data: "Isplate_Naplaceni_iznos_u_gotovini" },
                            { title: "Tips", data: "Ukupna_zarada_Napojnica" }
                        ]
                    });
                } else {
                    console.error('No data for Uber Reports');
                }

                // Repeat the above logic for other tables (Bolt, Taximetar, MyPos)
                if (response.boltReports) {
                    if ($.fn.DataTable.isDataTable('#boltTable')) {
                        $('#boltTable').DataTable().destroy();
                    }
                    $('#boltTable').DataTable({
                        data: response.boltReports,
						lengthMenu: [
							[10, 25, 50, -1],
							[10, 25, 50, 'All']
						],
						"responsive": true, "lengthChange": false, "autoWidth": false,
						paging: true, // Enable pagination
						ordering: true, // Enable sorting
						info: false, // Disable the information display
						dom: 'Bfrtip', // Show buttons for exporting
						buttons: [
							'copy', 'csv', 'excel', 'pdf', 'print','pageLenght'
						],
                        columns: [
                            { title: "Gross Amount", data: "Bruto_iznos" },
                            { title: "Cancellation Fee", data: "Otkazna_naknada" },
                            { title: "Toll Fee", data: "Naknada_za_cestarinu" },
                            { title: "Cash Collected", data: "Voznje_placene_gotovinom_prikupljena_gotovina" },
                            { title: "Bonus", data: "Bonus" }
                        ]
                    });
                } else {
                    console.error('No data for Bolt Reports');
                }

                if (response.taximetarReports) {
                    if ($.fn.DataTable.isDataTable('#taximetarTable')) {
                        $('#taximetarTable').DataTable().destroy();
                    }
                    $('#taximetarTable').DataTable({
                        data: response.taximetarReports,
 						lengthMenu: [
							[10, 25, 50, -1],
							[10, 25, 50, 'All']
						],
						"responsive": true, "lengthChange": false, "autoWidth": false,
						paging: true, // Enable pagination
						ordering: true, // Enable sorting
						info: false, // Disable the information display
						dom: 'Bfrtip', // Show buttons for exporting
						buttons: [
							'copy', 'csv', 'excel', 'pdf', 'print','pageLenght'
						],
                       columns: [
                            { title: "Week", data: "week" },
                            { title: "Total Revenue", data: "Ukupni_promet" }
                        ]
                    });
                } else {
                    console.error('No data for Taximetar Reports');
                }

                if (response.myPosReports) {
                    if ($.fn.DataTable.isDataTable('#myPosTable')) {
                        $('#myPosTable').DataTable().destroy();
                    }
                    $('#myPosTable').DataTable({
                        data: response.myPosReports,
						lengthMenu: [
							[10, 25, 50, -1],
							[10, 25, 50, 'All']
						],
						"responsive": true, "lengthChange": false, "autoWidth": false,
						paging: true, // Enable pagination
						ordering: true, // Enable sorting
						info: false, // Disable the information display
						dom: 'Bfrtip', // Show buttons for exporting
						buttons: [
							'copy', 'csv', 'excel', 'pdf', 'print','pageLenght'
						],
                        columns: [
                            { title: "Date Initiated", data: "Date_initiated" },
                            { title: "Type", data: "Type" },
                            { title: "Amount", data: "Amount" },
                            { title: "Week", data: "report_for_week" }
                        ]
						
                    });
                } else {
                    console.error('No data for MyPos Reports');
                }
            },
                error: function (xhr, status, error) {
                    console.error("Error:", error);
                }
            });
        });
    });
</script>
<!-- Vanilla Datepicker JS -->

		
<script>
    // Function to check and toggle visibility of 'krajPrijave'
    function toggleKrajPrijave() {
        var vrstaZaposlenjaSelect = document.getElementById('vrsta_zaposlenja');
        var krajPrijaveDiv = document.getElementById('krajPrijave');

        // Get the selected value from the 'vrsta_zaposlenja' select
        var selectedValue = vrstaZaposlenjaSelect.value;

        // Toggle visibility based on the selected value
        if (selectedValue === 'neodredeno') {
            krajPrijaveDiv.style.display = 'none';
        } else {
            krajPrijaveDiv.style.display = 'block';
        }
    }

    // Add an event listener to the 'vrsta_zaposlenja' select element
    document.getElementById('vrsta_zaposlenja').addEventListener('change', toggleKrajPrijave);

    // Initial check when the page loads
    toggleKrajPrijave();
</script>
		
<script>
$('#datepicker2').daterangepicker({
    "singleDatePicker": true,
    "showDropdowns": true,
    "autoApply": true,
    "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
        "applyLabel": "Apply",
        "cancelLabel": "Cancel",
        "fromLabel": "From",
        "toLabel": "To",
        "customRangeLabel": "Custom",
        "weekLabel": "W",
        "daysOfWeek": [
            "Su",
            "Mo",
            "Tu",
            "We",
            "Th",
            "Fr",
            "Sa"
        ],
        "monthNames": [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December"
        ],
        "firstDay": 1
    },
    "showCustomRangeLabel": false,
//    "startDate": "05/14/2024",
//    "endDate": "05/20/2024",
    "opens": "center"
});		
		
</script>
		
<script>
//$(document).ready(function() {
//    // Initialize datepicker with 'dd/mm/yyyy' format for specific element
//    $('#datepicker2').datepicker({
//        format: 'dd/mm/yyyy',
//        autoclose: true
//    });
//});
</script>
<script>
$('#datepicker3').daterangepicker({
    "singleDatePicker": true,
    "showDropdowns": true,
    "autoApply": true,
    "locale": {
        "format": "YYYY-MM-DD",
        "separator": " - ",
        "applyLabel": "Apply",
        "cancelLabel": "Cancel",
        "fromLabel": "From",
        "toLabel": "To",
        "customRangeLabel": "Custom",
        "weekLabel": "W",
        "daysOfWeek": [
            "Su",
            "Mo",
            "Tu",
            "We",
            "Th",
            "Fr",
            "Sa"
        ],
        "monthNames": [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December"
        ],
        "firstDay": 1
    },
    "showCustomRangeLabel": false,
//    "startDate": "05/14/2024",
//    "endDate": "05/20/2024",
    "opens": "center"
});		
		
</script>
<script>
$('#datepicker4').daterangepicker({
    "singleDatePicker": true,
    "showDropdowns": true,
    "autoApply": true,
    "locale": {
        "format": "YYYY-MM-DD",
        "separator": " - ",
        "applyLabel": "Apply",
        "cancelLabel": "Cancel",
        "fromLabel": "From",
        "toLabel": "To",
        "customRangeLabel": "Custom",
        "weekLabel": "W",
        "daysOfWeek": [
            "Su",
            "Mo",
            "Tu",
            "We",
            "Th",
            "Fr",
            "Sa"
        ],
        "monthNames": [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December"
        ],
        "firstDay": 1
    },
    "showCustomRangeLabel": false,
//    "startDate": "05/14/2024",
//    "endDate": "05/20/2024",
    "opens": "center"
});		
		
</script>
<script>
$('#datepicker5').daterangepicker({
    "singleDatePicker": true,
    "showDropdowns": true,
    "autoApply": true,
    "locale": {
        "format": "YYYY-MM-DD",
        "separator": " - ",
        "applyLabel": "Apply",
        "cancelLabel": "Cancel",
        "fromLabel": "From",
        "toLabel": "To",
        "customRangeLabel": "Custom",
        "weekLabel": "W",
        "daysOfWeek": [
            "Su",
            "Mo",
            "Tu",
            "We",
            "Th",
            "Fr",
            "Sa"
        ],
        "monthNames": [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December"
        ],
        "firstDay": 1
    },
    "showCustomRangeLabel": false,
//    "startDate": "05/14/2024",
//    "endDate": "05/20/2024",
    "opens": "center"
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

<script>

$(document).ready(function() {
    // Initialize DateRangePicker (with your specific ranges)
    var start = moment().subtract(1, 'weeks').startOf('isoWeek'); // Start of last week
    var end = moment().subtract(1, 'weeks').endOf('isoWeek'); // End of today (last day of the week)    $('#reportrange').daterangepicker({
    $('#reportrange').daterangepicker({
        ranges: {
            'Today': [moment(), moment()], // Use colons here
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')], 
            'This Week': [moment().startOf('isoWeek'), moment().endOf('isoWeek')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
		 startDate: start, // Set initial startDate
		endDate: end,     // Set initial endDate
		opens: 'left',
        locale: {
            format: 'YYYY-MM-DD' // Ensure consistent date format
        }
    });

	  jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "time-h-min-pre": function ( a ) {
      var timeParts = a.split(" "); // Split into hours and minutes parts
      var hours = parseInt(timeParts[0], 10) || 0;  // Extract hours (default 0)
      var minutes = parseInt(timeParts[1], 10) || 0; // Extract minutes (default 0)
      return hours * 60 + minutes;  // Convert to total minutes
    },

    "time-h-min-asc": function ( a, b ) {
      return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },

    "time-h-min-desc": function ( a, b ) {
      return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    }
  });
	
    // Initialize DataTable
    var table = $('#activityTable').DataTable({
		paging: false,
        ajax: {
            url: '<?= site_url("admin/driver-activity") ?>',
            type: "POST",
            data: function (d) {
                // Get the selected date range directly from the picker
				$('#reportrange span').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
				$('#startDate').val(start.format('YYYY-MM-DD'));
				$('#endDate').val(end.format('YYYY-MM-DD'));
                
                 d.startDate = $('#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD') || start.format('YYYY-MM-DD');
                d.endDate = $('#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD') || end.format('YYYY-MM-DD');               // Add your other data parameters (boltUniqueId, uberUniqueId) here
                d.boltUniqueId = '<?php echo $bUId; ?>';
                d.uberUniqueId = '<?php echo $UbUId; ?>';
                return d;
            },
			
            dataSrc: function (json) {
				 if (json.uberActivity && json.uberActivity.length > 0) {
					json.uberActivity.forEach(item => {
					  item.datum = moment(item.datum, 'YYYY-MM-DD').format('DD.MM.YYYY');
					});
				  }
				  if (json.boltActivity && json.boltActivity.length > 0) {
					json.boltActivity.forEach(item => {
					  item.datum = moment(item.datum, 'YYYY-MM-DD').format('DD.MM.YYYY');
					});
				  }


                // Calculate totals and update footer (if needed)
                // ... 
                return json;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
                // Log the raw response text for further debugging
				return json; 
            }
        },
        columns: [
             { data: "datum",
        render: function (data, type, row) {
          // Check if 'data' is not empty before trying to parse and format
          return data ? moment(data, 'YYYY-MM-DD').format('DD.MM.YYYY') : '';
        }
      },
            { 
        data: "uber_online", 
        render: function (data, type, row) {
          return formatDecimalHours(data); // Use the new formatting function
        }
      },
             { 
        data: "uber_vrijeme_voznje", 
        render: function (data, type, row) {
          return formatDecimalHours(data); // Use the new formatting function
        }
      },
             { 
        data: "bolt_online", 
        render: function (data, type, row) {
          return formatDecimalHours(data); // Use the new formatting function
        }
      },
             { 
        data: "bolt_vrijeme_voznje", 
        render: function (data, type, row) {
          return formatDecimalHours(data); // Use the new formatting function
        }
      },
             { 
        data: "total_online", 
        render: function (data, type, row) {
          return formatDecimalHours(data); // Use the new formatting function
        }
      },
             { 
        data: "total_vrijeme_voznje", 
        render: function (data, type, row) {
          return formatDecimalHours(data); // Use the new formatting function
        }
      },
        ],
		
		
		
        footerCallback: function ( row, data, start, end, display ) {
            var api = this.api(), data;

            // ... (same intVal function as before) ...
			var intVal = function ( i ) {
        return typeof i === 'string' ?
          i.replace(/[\$,.]/g, '')*1 :   // Remove commas and periods (if present)
          typeof i === 'number' ?
            i : 0;
      },
             // Total over all pages
  total_online = api
    .column( 5 )
    .data()
    .reduce( function (a, b) {
      return intVal(a) + intVal(b); // Sum the decimal hour values
    }, 0 );

  total_vrijeme_voznje = api
    .column( 6 )
    .data()
    .reduce( function (a, b) {
      return intVal(a) + intVal(b); // Sum the decimal hour values
    }, 0 );

  // Update footer using the formatDecimalHours function
  $( api.column( 5 ).footer() ).html(
    formatDecimalHours(total_online) 
  );
  $( api.column( 6 ).footer() ).html(
    formatDecimalHours(total_vrijeme_voznje) 
  );
}
		
		
		
		
		
    });
	
function formatDecimalHours(decimalHours) {
  const totalMinutes = Math.round(decimalHours * 60);
  const hours = Math.floor(totalMinutes / 60);
  const minutes = totalMinutes % 60;

  // Use singular "h" and "min" for values of 1
  const hourLabel = hours === 1 ? "h" : "h";
  const minuteLabel = minutes === 1 ? "min" : "min";

  // Create the formatted string
  let formattedTime = "";
  if (hours > 0) {
    formattedTime += `${hours} ${hourLabel} i `; // Add space after hours
  }
  if (minutes > 0) {
    formattedTime += `${minutes} ${minuteLabel}`;
  }

  // Handle the case where both hours and minutes are zero
  if (formattedTime === "") {
    formattedTime = "0 min"; 
  }

  return formattedTime;
}
	

    // Trigger DataTable reload when the date range is changed
    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        table.ajax.reload(); 
    });
});

		
		
		</script>		
		
<script>
  document.getElementById('taximetarCheckbox').addEventListener('change', function() {
    var mobTaximetarDiv = document.getElementById('mobTaximetarDiv');
    var mobTaximetarInput = document.getElementById('mobTaximetarInput');
    if (this.checked) {
      mobTaximetarDiv.style.display = 'block';
      mobTaximetarInput.setAttribute('required', 'required');
    } else {
      mobTaximetarDiv.style.display = 'none';
      mobTaximetarInput.removeAttribute('required');
    }
  });

  window.onload = function() {
    var checkbox = document.getElementById('taximetarCheckbox');
    var mobTaximetarDiv = document.getElementById('mobTaximetarDiv');
    var mobTaximetarInput = document.getElementById('mobTaximetarInput');
    if (checkbox.checked) {
      mobTaximetarDiv.style.display = 'block';
      mobTaximetarInput.setAttribute('required', 'required');
    } else {
      mobTaximetarDiv.style.display = 'none';
      mobTaximetarInput.removeAttribute('required');
    }
  };
</script>		
		<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get references to the checkbox and the form
    var taximetarCheckbox = document.getElementById('taximetarCheckbox');
    var form1 = document.getElementById('form1');
    
    // Store the initial state of the checkbox
    var initialTaximetarCheck = taximetarCheckbox.checked;

    // Add an event listener to the checkbox
    taximetarCheckbox.addEventListener('change', function() {
        // If the checkbox was initially checked and is now unchecked
        if (initialTaximetarCheck && !taximetarCheckbox.checked) {
            // Show a confirmation dialog
            var confirmation = confirm("Jeste li sigurni da želite deaktivirati vozačev taximetar? Deaktivacija će automatski biti poslana na taximetar.");

            // If the user does not confirm, prevent the checkbox from being unchecked
            if (!confirmation) {
                taximetarCheckbox.checked = true;
            }
        }
    });

    // Add form submission event listener to check the state before submitting
    form1.addEventListener('submit', function(event) {
        // If the checkbox was initially checked and is now unchecked, do nothing here because the confirmation is already handled above.
    });
});		
		</script>
<!--		 DRIVERS SEARCH BOX-->
    <script>
        $(document).ready(function () {
            const searchBox = $('#searchBox');
            const driverList = $('#driverList');
            const toggleListButton = $('#toggleList');
            const listItems = driverList.find('a');

            // Function to show the list and update the arrow icon
            function showList() {
                driverList.show(); // Show the list
                toggleListButton.find('i').removeClass('bi-chevron-down').addClass('bi-chevron-up'); // Update icon
            }

            // Function to hide the list and update the arrow icon
            function hideList() {
                driverList.hide(); // Hide the list
                toggleListButton.find('i').removeClass('bi-chevron-up').addClass('bi-chevron-down'); // Update icon
            }

            // Expand/collapse list on button click
            toggleListButton.on('click', function () {
                if (driverList.is(':visible')) {
                    hideList();
                } else {
                    showList();
                }
            });

            // Expand list when user focuses on the search box or starts typing
            searchBox.on('focus input', function () {
                showList(); // Expand the list
                const query = searchBox.val().trim().toLowerCase();

                // Filter the list dynamically
                listItems.each(function () {
                    const driverName = $(this).data('driver-name');
                    if (query.length === 0 || driverName.includes(query)) {
                        $(this).show(); // Show matched items or all if no query
                    } else {
                        $(this).hide(); // Hide unmatched items
                    }
                });
            });

            // Hide the list when clicking outside the search box or list
            $(document).on('click', function (e) {
                if (!$(e.target).closest('#searchBox, #driverList, #toggleList').length) {
                    hideList(); // Hide the list when clicking outside
                }
            });
        });
    </script>		
		
<script>
document.getElementById('form2').addEventListener('submit', function(event) {
    var aktivanValue = document.getElementById('aktivanSelect').value;
    
    if (aktivanValue == '0') {
        event.preventDefault(); // Prevent the default form submission
        
        // Open the deactivate function in a new tab
        window.open('<?php echo site_url('taximetar/deaktiviraj/'.$VOZACID)?>', '_blank');
        
        // Submit the form in the same tab
        this.submit();
    }
});
</script>		
</body>
</html>