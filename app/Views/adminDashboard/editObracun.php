<div class="container">
<div class="row">
	<div class="col-12 text-center">
		<h2> <?php echo $obracun['vozac'] ?></h2>
		<h6><?php echo $obracun['raspon'] ?></h6>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<form class="row g-3" action="<?php echo base_url('index.php/ObracunController/obracunUpdate');?>" method="post">
			<input type="hidden" class="form-control" name="id"  value ="<?php echo $obracun['id']  ?>">
			<input type="hidden" class="form-control" name="vozac_id"  value ="<?php echo $obracun['vozac_id']  ?>">
			<input type="hidden" class="form-control" name="vozac"  value ="<?php echo $obracun['vozac']  ?>">
			<input type="hidden" class="form-control" name="boltNeto"  value ="<?php echo $obracun['boltNeto']  ?>">
			<input type="hidden" class="form-control" name="boltGotovina"  value ="<?php echo $obracun['boltGotovina']  ?>">
			<input type="hidden" class="form-control" name="boltRazlika"  value ="<?php echo $obracun['boltRazlika']  ?>">
			<input type="hidden" class="form-control" name="uberNeto"  value ="<?php echo $obracun['uberNeto']  ?>">
			<input type="hidden" class="form-control" name="uberGotovina"  value ="<?php echo $obracun['uberGotovina']  ?>">
			<input type="hidden" class="form-control" name="uberRazlika"  value ="<?php echo $obracun['uberRazlika']  ?>">
			<input type="hidden" class="form-control" name="myPosNeto"  value ="<?php echo $obracun['myPosNeto']  ?>">
			<input type="hidden" class="form-control" name="myPosGotovina"  value ="<?php echo $obracun['myPosGotovina']  ?>">
			<input type="hidden" class="form-control" name="myPosRazlika"  value ="<?php echo $obracun['myPosRazlika']  ?>">
			<input type="hidden" class="form-control" name="fleet"  value ="<?php echo $obracun['fleet']  ?>">
			<input type="hidden" class="form-control" name="week"  value ="<?php echo $obracun['week']  ?>">
			<input type="hidden" class="form-control" name="fiskalizacijaUber"  value ="<?php echo $obracun['fiskalizacijaUber']  ?>">
			<input type="hidden" class="form-control" name="fiskalizacijaBolt"  value ="<?php echo $obracun['fiskalizacijaBolt']  ?>">
			<input type="hidden" class="form-control" name="refered_by"  value ="<?php echo $obracun['refered_by']  ?>">
			<input type="hidden" class="form-control" name="referal_reward"  value ="<?php echo $obracun['referal_reward']  ?>">
			<input type="hidden" class="form-control" name="bonus_ref"  value ="<?php echo $obracun['bonus_ref']  ?>">
			<input type="hidden" class="form-control" name="doprinosi"  value ="<?php echo $obracun['doprinosi']  ?>">
			<input type="hidden" class="form-control" name="bruto_placa"  value ="<?php echo $obracun['bruto_placa']  ?>">
			<input type="hidden" class="form-control" name="uberNapojnica"  value ="<?php echo $obracun['uberNapojnica']  ?>">
			<input type="hidden" class="form-control" name="uberPovrat"  value ="<?php echo $obracun['uberPovrat']  ?>">
			<input type="hidden" class="form-control" name="boltNapojnica"  value ="<?php echo $obracun['boltNapojnica']  ?>">
			<input type="hidden" class="form-control" name="boltPovrat"  value ="<?php echo $obracun['boltPovrat']  ?>">
			<input type="hidden" class="form-control" name="myPosNapojnica"  value ="<?php echo $obracun['myPosNapojnica']  ?>">
			<input type="hidden" class="form-control" name="myPosPovrat"  value ="<?php echo $obracun['myPosPovrat']  ?>">
			<input type="hidden" class="form-control" name="ukupnoNapojnica"  value ="<?php echo $obracun['ukupnoNapojnica']  ?>">
			<input type="hidden" class="form-control" name="ukupnoPovrat"  value ="<?php echo $obracun['ukupnoPovrat']  ?>">
			<input type="hidden" class="form-control" name="cetvrtinaNetoPlace"  value ="<?php echo $obracun['cetvrtinaNetoPlace']  ?>">
			<input type="hidden" class="form-control" name="raspon"  value ="<?php echo $obracun['raspon']  ?>">
			<input type="hidden" class="form-control" name="isplataNa"  value ="<?php echo $obracun['isplataNa']  ?>">
			<input type="hidden" class="form-control" name="IBAN"  value ="<?php echo $obracun['IBAN']  ?>">

		  <div class="col-md-2">
			<label for="ukupnoNeto" class="form-label text-center">Neto</label>
			<input type="text" name ="ukupnoNeto" class="form-control" id="ukupnoNeto" value="<?php echo $obracun['ukupnoNeto'] ?>">
		  </div>
		  <div class="col-md-2">
			<label for="ukupnoGotovina" class="form-label text-center">Gotovina</label>
			<input type="text" name ="ukupnoGotovina" class="form-control" id="ukupnoGotovina" value="<?php echo $obracun['ukupnoGotovina'] ?>">
		  </div>
		  <div class="col-md-2">
			<label for="ukupnoRazlika" class="form-label text-center">Razlika</label>
			<input type="text" name ="ukupnoRazlika" class="form-control" id="ukupnoRazlika" value="<?php echo $obracun['ukupnoRazlika'] ?>">
		  </div>
		  <div class="col-md-2">
			<label for="provizija" class="form-label text-center">Provizija</label>
			<input type="text" name ="provizija" class="form-control" id="provizija" value="<?php echo $obracun['provizija'] ?>">
		  </div>
		  <div class="col-md-2">
			<label for="taximetar" class="form-label text-center">Taximetar</label>
			<input type="text" name ="taximetar" class="form-control" id="taximetar" value="<?php echo $obracun['taximetar'] ?>">
		  </div>
		  <div class="col-md-2">
			<label for="dug" class="form-label text-center">Dug</label>
			<input type="text" name ="dug" class="form-control" id="dug" value="<?php echo $obracun['dug'] ?>">
		  </div>
		  <div class="col-md-2">
			<label for="zaIsplatu" class="form-label text-center">Za Isplatu</label>
			<input type="text" name ="zaIsplatu" class="form-control" id="zaIsplatu" value="<?php echo $obracun['zaIsplatu'] ?>">
		  </div>

<?php 
									
									$referals = $obracun['referals'];
									if($referals != 'nema'){
									//$referals = json_decode($referals, true);
										;?>
											<textarea name="referals"><?php print_r($referals) ?></textarea>
									<?php 
									} ?>
		<div class="col-12 text-center">
			<button type="submit" class="btn btn-primary">Spremi promjene</button>
		  </div>
		</form>
	</div>
</div>
</div>