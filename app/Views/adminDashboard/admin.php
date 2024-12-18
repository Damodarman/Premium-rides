
<body>
	<div class="container bg-danger">
		<div class="row bg-dark text-white mt-2">
			<div class="col-12 text-white mt-3 border-bottom border-2 border-danger">
				<h4> Dobro došao <?php echo $name;?>, ovo je pregled tvoje flote</h4>
				<?php if($level == 1): ?>
					<h4> <?php echo $name;?>,  <a class="nav-link" href="<?php echo site_url('admin/posaljiPoruku')?>">ovdje pošalji whatsapp poruku</a></h4>
	
				<?php endif ?>

			</div>
			<div class="col col-xxl-6 col-xl-6 col-lg-12 col-m-12 col-sm-12  mt-3">
				<div class="row">
					<div class="col-6">
						<h4>Info Flote, <?php echo $fleet; ?></h4>
					</div>
					<div class="col-6">
						<h4><a class="nav-link" href="<?php echo site_url('admin/flota')?>">Postavke flote</a></h4>
					</div>
				</div>
					<div class="container text-dark border-top border-2 border-danger mb-2">
				<a href="<?php echo site_url('drivers')?>">
						<div class="row pt-1">
							<div class="col-6 pt-3 ">
								<div class="bg-secondary border border-danger rounded d-flex align-items-center justify-content-between p-4 pt-1 pb-1">
								<i class="bi bi-people" style="font-size: 3rem; color: greenyellow" ></i>
									<div class="ms-3">
									<p class=" fs-5 mb-2 ms-5"> <?php echo $vozaci; ?></p>
									<h6 class="mb-1"> Aktivnih vozača</h6>
									</div>
								</div>
							</div>
							<div class="col-6 pt-3">
								<div class="bg-secondary border border-danger rounded d-flex align-items-center justify-content-between p-4 pt-1 pb-1">
								<i class="bi bi-people" style="font-size: 3rem; color: mediumblue" ></i>
									<div class="ms-3">
									<p class=" fs-5 mb-2 ms-5"> <?php echo $vozaciPrijava; ?></p>
									<h6 class="mb-1"> Aktivnih sa prijavom</h6>
									</div>
								</div>
							</div>
						</div>
					</div>
			</a>
				</div>
			<div class="col col-xxl-6 col-xl-6 col-lg-12 col-m-12 col-sm-12  mt-3">
				<h4>Info Tvrtke</h4>
				<div class="container border-top text-dark border-2 border-danger">
					<div class="row">
						<div class="col-12 pt-3">
							<div class="bg-secondary border border-danger rounded d-flex align-items-center justify-content-between p-4 pt-1 pb-1">
							<i class="bi bi-building-fill" style="font-size: 3rem; color: red" ></i>
								<div class="ms-3">
									<p class=" fs-6 mb-1 ms-2"> 
										<?php echo $tvrtka['naziv']; ?><br>
										<?php echo $tvrtka['adresa']; ?>, <?php echo $tvrtka['grad']; ?><br>
										OIB: <?php echo $tvrtka['OIB']; ?><br>
									</p>
								</div>
								<div class="ms-3"><h6 class="mb-0"> Direktor: <?php echo $tvrtka['direktor']; ?></h6></div>
							</div>
						</div>

					</div>
				</div>
			</div>
			
		</div>
		<div class="row bg-dark  mt-2 text-white">
				<div class="container  border-top border-2 border-danger mb-2">
					<div class="row pt-1">
							<h4 class="text-center mt-4 fs-3">Zadnji Obračun</h4>
						<div class="col col-xxl-4 col-xl-4 col-lg-12 col-m-12 col-sm-12 pt-3 ">
							<div class="bg-secondary border border-danger rounded d-flex align-items-center justify-content-between p-4 pt-1 pb-1">
							<i class="bi bi-currency-euro" style="font-size: 3rem; color: black" ></i>
								<div class="ms-3 text-dark fw-bold">
									<?php $zadnjiObracunNeto = floatval($zadnjiObracun['ukupnoNetoSvi']); ?>
								<h3 class="fs-3 fw-bold ms-5"> <?php echo $zadnjiObracunNeto; ?> €</h3>
								<h6 class="fs-5 fw-bold ms-5"> Neto promet </h6>
								</div>
							</div>
						</div>
						<div class="col col-xxl-4 col-xl-4 col-lg-12 col-m-12 col-sm-12 pt-3">
							<div class="bg-secondary border border-danger rounded d-flex align-items-center justify-content-between p-4 pt-1 pb-1">
								<i class="bi bi-cash" style="font-size: 3rem; color: black" ></i>
								<div class="ms-3 text-dark fw-bold">
									<?php $zadnjiObracunGotovina = floatval($zadnjiObracun['ukupnoGotovinaSvi']); ?>
									<h3 class="fs-3 fw-bold ms-5"> <?php echo $zadnjiObracunGotovina; ?> €</h3>
									<h6 class="fs-5 fw-bold ms-5">  Gotovina </h6>
								</div>
							</div>
						</div>
						<div class="col col-xxl-4 col-xl-4 col-lg-12 col-m-12 col-sm-12 pt-3 text-dark fw-bold">
							<div class="bg-secondary border border-danger rounded d-flex align-items-center justify-content-between p-4 pt-1 pb-1">
								<i class="bi bi-cash-stack" style="font-size: 3rem; color: black" ></i>
								<div class="ms-3">
									<?php $zadnjiObracunprovizija = floatval($zadnjiObracun['zaradaFirme']); ?>
									<h3 class="fs-3 fw-bold ms-5"> <?php echo $zadnjiObracunprovizija; ?> €</h3>
									<h6 class="fs-5 fw-bold ms-5">  Zarada </h6>
								</div>
							</div>
						</div>
					</div>
			</div>
		</div>
	
		<div class="row bg-dark text-white mt-2">
				<div class="container  border-top border-2 border-danger mb-2">
					<div class="row pt-1">
							<h4 class="text-center mt-4 fs-3">U odnosu na prošli obračun</h4>
						<?php $razlikaNeto = $zadnjiObracunNeto - $predzadnjiObracun['ukupnoNetoSvi'] ?>
						<?php $razlikaZarada = $zadnjiObracun['zaradaFirme'] - $predzadnjiObracun['zaradaFirme'] ?>
						<?php $razlikaGotovina =abs($zadnjiObracunGotovina) - abs($predzadnjiObracun['ukupnoGotovinaSvi']);
								$razlikaGotovina = round($razlikaGotovina, 2)
						?>
						<div class="col col-xxl-4 col-xl-4 col-lg-12 col-m-12 col-sm-12 text-dark fw-bold pt-3 ">
							<div class="bg-secondary border border-danger rounded d-flex align-items-center justify-content-between p-4 pt-1 pb-1">
							<i class="bi <?php if($razlikaNeto < 0){ echo 'bi-graph-down-arrow" style="font-size: 3rem; color: red"';}else{ echo 'bi-graph-up-arrow" style="font-size: 3rem; color: lawngreen"';} ?>></i>
								<div class="ms-3">
									
								<h3 class="fs-3 fw-bold ms-5"> <?php echo $razlikaNeto; ?> €</h3>
								<h6 class="fs-5 fw-bold ms-5"> Razlika Neto </h6>
								</div>
							</div>
						</div>
						<div class="col col-xxl-4 col-xl-4 col-lg-12 col-m-12 col-sm-12 text-dark fw-bold pt-3">
							<div class="bg-secondary border border-danger rounded d-flex align-items-center justify-content-between p-4 pt-1 pb-1">
							<i class="bi <?php if($razlikaGotovina < 0){ echo 'bi-graph-down-arrow" style="font-size: 3rem; color: red"';}else{ echo 'bi-graph-up-arrow" style="font-size: 3rem; color: lawngreen"';} ?>></i>
								<div class="ms-3">
									<h3 class="fs-3 fw-bold ms-5"> <?php echo $razlikaGotovina; ?> €</h3>
									<h6 class="fs-5 fw-bold ms-5">  Razlika Gotovina </h6>
								</div>
							</div>
						</div>
						<div class="col col-xxl-4 col-xl-4 col-lg-12 col-m-12 col-sm-12 text-dark fw-bold pt-3">
							<div class="bg-secondary border border-danger rounded d-flex align-items-center justify-content-between p-4 pt-1 pb-1">
							<i class="bi <?php if($razlikaZarada < 0){ echo 'bi-graph-down-arrow" style="font-size: 3rem; color: red"';}else{ echo 'bi-graph-up-arrow" style="font-size: 3rem; color: lawngreen"';} ?>></i>
								<div class="ms-3">
									<h3 class="fs-3 fw-bold ms-5"> <?php echo $razlikaZarada; ?></h3>
									<h6 class="fs-5 fw-bold ms-5">  Razlika Zarada</h6>
								</div>
							</div>
						</div>
		
<!--
						<form class="row g-3" action="<?php echo site_url('AdminController/sendSms');?>" method="post">
							<div class="col-md-6">
								<label for="broj" class="form-label">Broj mobitela</label>
								<input type="text" name ="broj" class="form-control" id="broj" placeholder="+385945784244">
							  </div>
							
							<div class="col-md-6">
								<label for="msg" class="form-label">Poruka</label>
								<input type="text" name ="msg" class="form-control" id="msg">
							  </div>
									<div class="d-grid gap-2">
										  <button type="submit" class="btn btn-primary">Pošalji poruku</button>
									</div>

						</form>
-->
					</div>
			</div>
		</div>
	</div>
	
	
	
	
	
</body>
</html>