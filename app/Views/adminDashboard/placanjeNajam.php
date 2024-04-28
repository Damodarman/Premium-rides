<div class="container">
	<div class="row">
		<div class="col-2"></div>
		<div class="col-8">
			<div class="row">
				<div class="col-6">
					</br></br></br>
			<p>
					<?php echo $tvrtka['naziv'] ?></br>
					<?php echo $tvrtka['adresa'] ?>, <?php echo $tvrtka['grad'] ?></br>
					OIB: <?php echo $tvrtka['OIB'] ?></br>
			</p>
				</div>
				<div class="col-8"></div>
			</div>
</br></br>
		<h3 class="text-center"> Obračun zakupa vozila za period od <?php echo $knjigoReport['obrRazdobljeOd']?> do <?php echo $knjigoReport['obrRazdobljeDo']?></h3></br></br></br>
		<p>Temeljem ugovora o zakupu/podzakupu za vozilo <?php echo $knjigoReport['proizvodac']?> <?php echo $knjigoReport['model']?>, registarskih oznaka <?php echo $knjigoReport['reg']?> te uvidom u evidenciju korištenja vozila, ZAKUPNIK: <?php echo $knjigoReport['vozac'] ?> ostvaruje pravo na naknadu u iznosu od <?php echo $knjigoReport['osnovnaCijena'] + $knjigoReport['dug']?> €. Koja će se isplatiti dana <?php echo $knjigoReport['danIsplate'] ?></p>
		</div>
		<div class="col-2"></div>
	</div>
<div class="row">
	<div class="col-2"></div>
	<div class="col-4">
		</br>
		<h6> U Zagrebu, <?php echo $knjigoReport['danObracuna'] ?></h6></br>

		<p>
			Direktor <?php echo $tvrtka['direktor']?> </br></br>

			________________________
		</p>
	
	</div>

</div>
</div>