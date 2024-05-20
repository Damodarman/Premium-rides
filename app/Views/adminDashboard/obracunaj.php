
	<div class="row ms-5 me-5 mt-1">
	<div class="col-2 bg-dark"></div>
	<div class="col-8 bg-dark">
		<h3 class="text-center text-white bg-danger rounded-pill mt-3 mb-3"> Obračun od <?php echo $st_date ?> do <?php echo $en_date ?></h3>
	</div>
	<div class="col-2 bg-dark"></div>
	</div>


<div class="container">
		<form class="row g-3 mt-3" action="<?php echo base_url('index.php/ObracunController/obracunSavedodatni');?>" method="post">
	<?php  
			ini_set('post_max_size', '64M');   // Change to the desired size
ini_set('max_input_vars', 2000);   // Change to the desired maximum number of input variables
	$obracunFirmaJson = json_encode($firmaObracun);
	
	?>
	 <textarea style="display:none;" id="obracunFirma" name="obracunFirma" ><?php echo $obracunFirmaJson ; ?></textarea>
		<?php 
			$br = 0;
			$obracun = array();
			?>
			<?php foreach($obracunNaProviziju as $obracun): ?>
			<?php
				$dug = 0;
				foreach($dugovi as $duznik){
					if($duznik['vozac_id'] == $obracun['vozac_id']){
						$dug = $duznik['iznos'];
					}
				}
			?>
		<div class="row border-bottom border-danger mb-1 pb-2 bg-secondary text-white">
		<div class="col-md-2 ">
			<label for="obracun[<?php echo $br ?>][vozac]" class="form-label ">Vozač</label>
			<input readonly type="text" name ="obracun[<?php echo $br ?>][vozac]" class="form-control" id="obracun[<?php echo $br ?>][vozac]" value="<?php echo $obracun['vozac'] ?>">
		  </div>
		<div class="col-md-2">
			<label for="obracun[<?php echo $br ?>][ukupnoNeto]" class="form-label">Neto</label>
			<input readonly type="text" name ="obracun[<?php echo $br ?>][ukupnoNeto]" class="form-control" id="obracun[<?php echo $br ?>][ukupnoNeto]" value="<?php echo $obracun['ukupnoNeto'] ?>">
		  </div>
		<div class="col-md-2">
			<label for="obracun[<?php echo $br ?>][ukupnoGotovina]" class="form-label">Gotovina</label>
			<input readonly type="text" name ="obracun[<?php echo $br ?>][ukupnoGotovina]" class="form-control" id="obracun[<?php echo $br ?>][ukupnoGotovina]" value="<?php echo $obracun['ukupnoGotovina'] ?>">
		  </div>
		<div class="col-md-2">
			<label for="obracun[<?php echo $br ?>][bonus_ref]" class="form-label">Referal bonus</label>
			<input readonly type="text" name ="obracun[<?php echo $br ?>][bonus_ref]" class="form-control" id="obracun[<?php echo $br ?>][bonus_ref]" value="<?php echo $obracun['refBonus'] ?>">
		  </div>
		<div class="col-md-2">
			<label for="obracun[<?php echo $br ?>][provizija]" class="form-label">Provizija</label>
			<input type="text" name ="obracun[<?php echo $br ?>][provizija]" class="form-control" id="obracun[<?php echo $br ?>][provizija]" value="<?php echo $obracun['provizija'] ?>">
		  </div>
		<div class="col-md-1">
			<label for="obracun[<?php echo $br ?>][dug]" class="form-label">Dug</label>
			<input type="text" name ="obracun[<?php echo $br ?>][dug]" class="form-control" id="obracun[<?php echo $br ?>][dug]" value="<?php echo $dug ?> ">
		  </div>
		<div class="col-md-1">
			<label for="obracun[<?php echo $br ?>][taximetar]" class="form-label">Taximetar</label>
			<input type="text" name ="obracun[<?php echo $br ?>][taximetar]" class="form-control" id="obracun[<?php echo $br ?>][taximetar]" value="<?php echo $obracun['taximetar']  ?>">
		  </div>

				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][vozac_id]"  value ="<?php echo $obracun['vozac_id']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][uberNeto]"  value ="<?php echo $obracun['uberNeto']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][uberNapojnica]"  value ="<?php echo $obracun['uberNapojnica']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][uberPovrat]"  value ="<?php echo $obracun['uberPovrat']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][uberGotovina]"  value ="<?php echo $obracun['uberGotovina']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][uberRazlika]"  value ="<?php echo $obracun['uberRazlika']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][boltNeto]"  value ="<?php echo $obracun['boltNeto']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][boltNapojnica]"  value ="<?php echo $obracun['boltNapojnica']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][boltPovrat]"  value ="<?php echo $obracun['boltPovrati']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][boltGotovina]"  value ="<?php echo $obracun['boltGotovina']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][boltRazlika]"  value ="<?php echo $obracun['boltRazlika']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][myPosNeto]"  value ="<?php echo $obracun['myPosNeto']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][boltNaljepnice]"  value ="<?php echo $obracun['boltNaljepnice']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][myPosNapojnica]"  value ="<?php echo $obracun['myPosNapojnica']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][myPosPovrat]"  value ="<?php echo $obracun['myPosPovrati']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][myPosGotovina]"  value ="<?php echo $obracun['myPosGotovina']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][myPosRazlika]"  value ="<?php echo $obracun['myPosRazlika']  ?>">
				<textarea style="display:none;" id="obracun[<?php echo $br ?>][myPosTransakcije]" name="obracun[<?php echo $br ?>][myPosTransakcije]" ><?php echo $obracun['myPosTransakcije'] ; ?></textarea>
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][week]"  value ="<?php echo $obracun['week']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][fleet]"  value ="<?php echo $obracun['fleet']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][ukupnoNapojnica]"  value ="<?php echo $obracun['ukupnoNapojnica']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][ukupnoPovrat]"  value ="<?php echo $obracun['ukupnoPovrati']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][ukupnoRazlika]"  value ="<?php echo $obracun['ukupnoRazlika']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][zaIsplatu]"  value ="<?php echo $obracun['zaIsplatu']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][fiskalizacijaUber]"  value ="<?php echo $obracun['fiskalizacijaUber']  ?>">
				
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][uberOnline]"  value ="<?php echo $obracun['uberOnline']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][uberActiv]"  value ="<?php echo $obracun['uberActiv']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][uberPerH]"  value ="<?php echo $obracun['uberPerH']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][boltOnline]"  value ="<?php echo $obracun['boltOnline']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][boltActiv]"  value ="<?php echo $obracun['boltActiv']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][boltPerH]"  value ="<?php echo $obracun['boltPerH']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][totalPerH]"  value ="<?php echo $obracun['totalPerH']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][uberPerOH]"  value ="<?php echo $obracun['uberPerOH']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][boltPerOH]"  value ="<?php echo $obracun['boltPerOH']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][totalPerOH]"  value ="<?php echo $obracun['totalPerOH']  ?>">
			
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][fiskalizacijaBolt]"  value ="<?php echo $obracun['fiskalizacijaBolt']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][doprinosi]"  value ="<?php echo $obracun['doprinosi']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][IBAN]"  value ="<?php echo $obracun['IBAN']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][najamVozila]"  value ="<?php echo $obracun['najamVozila']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][isplataNa]"  value ="<?php echo $obracun['isplataNa']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][cetvrtinaNetoPlace]"  value ="<?php echo $obracun['cetvrtinaNetoPlace']  ?>">
				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][raspon]"  value ="<?php echo $st_date?> - <?php echo $en_date  ?>">
				<textarea style="display:none;" id="obracun[<?php echo $br ?>][referals]" name="obracun[<?php echo $br ?>][referals]" ><?php echo $obracun['referals'] ; ?></textarea>

				<input type="hidden" class="form-control" name="obracun[<?php echo $br ?>][bonus_ref]"  value ="<?php echo $obracun['refBonus']  ?>">
			</tr>
				<?php $br +=1 ?>
			</div>
		<?php endforeach ?>
			
	<div class="d-grid gap-2">
	  <button type="submit" class="btn btn-primary">Spremi obračun</button>
</div>

</form>
</div>


