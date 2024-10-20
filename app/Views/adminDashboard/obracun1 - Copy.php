
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
			
		<?php 
			if(isset($obracun)): ?>
				<?php 
			$reportIDs = array();
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
			<div class="col-2"></div>
			<div class="col col-xxl-8 col-xl-8 col-lg-12 col-m-12 col-sm-12">
				<div id="<?php echo $driver_slug_id ?>" class="card border-danger text-white bg-secondary mt-3">
					<div class="card-header text-center"><h4 class="fw-bold"><a href="<?php echo site_url('drivers/'). '/' .$driver['vozac_id']?>"><?php echo $driver['vozac']; ?></a></h4>
					<h6 class=" border-dark"><a href="<?php echo site_url('editirajObracun/'). '/' .$driver['id']?>"><?php echo $driver['raspon']; ?></a></h6>
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
									
								</ul>
							</div>
						</div>
						<div class="row text-center">
							<div class="col-sm">
								<h4 class="border border-info border-2 text-info mt-1">Završni saldo</h4>
								<ul class="list-group list-group-flush border-danger">
									<li class="list-group-item fs-6"> <?php echo $driver['zaIsplatu']?> €</li>
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
		  <div class="container">
			<h1 class="text-center mt-5 mb-3">Download Screenshots</h1>
			<div class="text-center mb-3">
			  <button id="download-screenshots" class="btn btn-primary">Download Screenshots</button>
			</div>
			<div class="progress mb-3" style="display: none;">
			  <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
			</div>
		  </div>
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
		</div>
					<?php endif?>
		<div class="row">
			<div class="col-6">
				<h4>Prosjek po vozaču </h4>
				<ul class="list-group list-group-flush border-danger">
					<li class="list-group-item fs-6">Broj Vozača : <?php echo $stats['brojVozaca']; ?></li>
					<li class="list-group-item fs-6">Neto promet : <?php echo $stats['netoPoVozacu']; ?> €</li>
					<li class="list-group-item fs-6">Provizija za naplatiti: <?php echo $stats['provizijaPoVozacu']; ?> €</li>
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
	</div></div>


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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js" integrity="sha512-T1JjQL1Qc0ILix4JUTx4gP81Z0ulmheS1SctLLcSdUCVE9h5b5+jVT/p5uz5RlQu7VnCwZZu8Gq3F+x2uL06yQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script>
    const reportIDs = <?php echo json_encode($reportIDs); ?>;

    function downloadScreenshots() {
      const downloadButton = document.getElementById('download-screenshots');
      downloadButton.disabled = true;

      const progress = document.querySelector('.progress');
      progress.style.display = 'block';

      const progressBar = document.querySelector('.progress-bar');

      const totalSteps = reportIDs.length * 2;
      let completedSteps = 0;

      reportIDs.forEach(id => {
        const element = document.getElementById(id);

        html2canvas(element).then(canvas => {
          completedSteps++;
          progressBar.style.width = Math.floor((completedSteps / totalSteps) * 100) + '%';
          canvas.toBlob(blob => {
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = id + '.jpg';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
            completedSteps++;
            progressBar.style.width = Math.floor((completedSteps / totalSteps) * 100) + '%';
            if (completedSteps === totalSteps) {
              downloadButton.disabled = false;
              progress.style.display = 'none';
              progressBar.style.width = '0%';
            }
          });
        });
      });
    }

    const downloadButton = document.getElementById('download-screenshots');
    downloadButton.addEventListener('click', downloadScreenshots);
  </script>