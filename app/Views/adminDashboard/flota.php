<div class="container">
	<?php if (session()->has('msgflota')){ ?>
		<div class="alert <?=session()->getFlashdata('alert-class') ?>">
			<?=session()->getFlashdata('msgtvrtka') ?>
		</div>
	<?php } ?>
	<?php if (session()->has('msgtvrtka')){ ?>
		<div class="alert <?=session()->getFlashdata('alert-class') ?>">
			<?=session()->getFlashdata('msgtvrtka') ?>
		</div>
	<?php } ?>
	
	<h4>Postavke flote</h4>
	<div class="row">
		<form class="row g-3" action="<?php echo base_url('index.php/FlotaController/postavkeFlote');?>" method="post">
			<div class="col-md-3">
				<label for="naziv" class="form-label">Naziv Flote</label>
				<input type="text" name ="naziv" class="form-control" <?php if(isset($flota)):?> value="<?php echo $flota['naziv'] ?>" <?php else:?> placeholder="Naziv flote"   <?php endif ?> </input>
				<input type="hidden" name="flota_id" value="<?php echo $flota['id']?>">
				<input type="hidden" name="vlasnik" value="<?php echo $flota['vlasnik']?>">
				<input type="hidden" name="vlasnik_users_id" value="<?php echo $flota['vlasnik_users_id']?>">
			</div>
			<div class="col-md-3">
				<label for="fiskalizacija_bolt" class="form-label">Fiskalizacija Bolt  €</label>
				<input type="text" name ="fiskalizacija_bolt" class="form-control" <?php if(isset($flota)):?> value="<?php echo $flota['fiskalizacija_bolt'] ?>" <?php else:?> placeholder="Fiskalizacija Bolt"   <?php endif ?> </input>
			</div>
			<div class="col-md-3">
				<label for="fiskalizacija_uber" class="form-label">Fiskalizacija Uber  €</label>
				<input type="text" name ="fiskalizacija_uber" class="form-control" <?php if(isset($flota)):?> value="<?php echo $flota['fiskalizacija_uber'] ?>" <?php else:?> placeholder="Fiskalizacija Uber"   <?php endif ?> </input>
			</div>
			<div class="col-md-3">
				<label for="provizija_fiks" class="form-label">Fiksna provizija  €</label>
				<input type="text" name ="provizija_fiks" class="form-control" <?php if(isset($flota)):?> value="<?php echo $flota['provizija_fiks'] ?>" <?php else:?> placeholder="40"   <?php endif ?> </input>
			</div>
			<div class="col-md-3">
				<label for="provizija_fiks_sezona" class="form-label">Fiksna provizija sezona €</label>
				<input type="text" name ="provizija_fiks_sezona" class="form-control" <?php if(isset($flota)):?> value="<?php echo $flota['provizija_fiks_sezona'] ?>" <?php else:?> placeholder="40"   <?php endif ?> </input>
			</div>
			<div class="col-md-3">
				<label for="provizija_postotak" class="form-label">Provizija postotak %</label>
				<input type="text" name ="provizija_postotak" class="form-control" <?php if(isset($flota)):?> value="<?php echo $flota['provizija_postotak'] ?>" <?php else:?> placeholder="10"   <?php endif ?> </input>
			</div>
			<div class="col-md-3">
				<label for="taximetar" class="form-label">Taximetar €</label>
				<input type="text" name ="taximetar" class="form-control" <?php if(isset($flota)):?> value="<?php echo $flota['taximetar'] ?>" <?php else:?> placeholder="20"   <?php endif ?> </input>
			</div>
			<div class="col-md-3">
				<label for="taximetar_tjedno" class="form-label">Taximetar tjedno </label>
				<select class="form-select" name="taximetar_tjedno" aria-label="Default select example">
					<option value ="<?php echo $flota['taximetar_tjedno']  ?>" selected ><?php if($flota['taximetar_tjedno'] != '1'){ echo 'NE';} else{ echo 'DA';} ?></option>
							<?php if($flota['taximetar_tjedno'] != '0'){echo '<option value="0">NE</option>';} 
								  if($flota['taximetar_tjedno'] != '1'){echo '<option value="1">DA</option>';}
							?>
				</select>
			</div>
			<div class="col-md-3">
				<label for="koristi_activity" class="form-label">Koristiti activity u obračunu ? </label>
				<select class="form-select" name="koristi_activity" aria-label="Default select example">
					<option value ="<?php echo $flota['koristi_activity']  ?>" selected ><?php if($flota['koristi_activity'] != '1'){ echo 'NE';} else{ echo 'DA';} ?></option>
							<?php if($flota['koristi_activity'] != '0'){echo '<option value="0">NE</option>';} 
								  if($flota['koristi_activity'] != '1'){echo '<option value="1">DA</option>';}
							?>
				</select>
			</div>
			<div class="col-md-3">
				<label for="koristi_min_proviziju" class="form-label">Koristiti minimalnu proviziju ? </label>
				<select class="form-select" name="koristi_min_proviziju" aria-label="Default select example">
					<option value ="<?php echo $flota['koristi_min_proviziju']  ?>" selected ><?php if($flota['koristi_min_proviziju'] != '1'){ echo 'NE';} else{ echo 'DA';} ?></option>
							<?php if($flota['koristi_min_proviziju'] != '0'){echo '<option value="0">NE</option>';} 
								  if($flota['koristi_min_proviziju'] != '1'){echo '<option value="1">DA</option>';}
							?>
				</select>
			</div>
			<div class="col-md-3">
				<label for="iznos_min_provizije" class="form-label">Iznos minimalne provizije u €</label>
				<input type="text" name ="iznos_min_provizije" class="form-control" <?php if(isset($flota)):?> value="<?php echo $flota['iznos_min_provizije'] ?>" <?php else:?> placeholder="20"   <?php endif ?> </input>
			</div>
			<div class="col-md-3">
				<label for="koristi_min_proviziju_sezona" class="form-label">Koristiti minimalnu proviziju za sezonu? </label>
				<select class="form-select" name="koristi_min_proviziju_sezona" aria-label="Default select example">
					<option value ="<?php echo $flota['koristi_min_proviziju_sezona']  ?>" selected ><?php if($flota['koristi_min_proviziju_sezona'] != '1'){ echo 'NE';} else{ echo 'DA';} ?></option>
							<?php if($flota['koristi_min_proviziju_sezona'] != '0'){echo '<option value="0">NE</option>';} 
								  if($flota['koristi_min_proviziju_sezona'] != '1'){echo '<option value="1">DA</option>';}
							?>
				</select>
			</div>
			<div class="col-md-3">
				<label for="minimalna_provizija_sezona" class="form-label">Iznos minimalne provizije sezona u €</label>
				<input type="text" name ="minimalna_provizija_sezona" class="form-control" <?php if(isset($flota)):?> value="<?php echo $flota['minimalna_provizija_sezona'] ?>" <?php else:?> placeholder="20"   <?php endif ?> </input>
			</div>
			<div class="col-md-3">
				<label for="tvrtka_id" class="form-label">Tvrtka</label>
				<select class="form-select" name="tvrtka_id" aria-label="Default select example">
					<option value="<?php echo $aktivnaTvrtka['id']?>" selected><?php echo $aktivnaTvrtka['naziv']?></option>
					<?php foreach($tvrtke as $tvrtka): ?>
						<?php if($tvrtka['id'] != $aktivnaTvrtka['id']): ?>
							<option value="<?php echo $tvrtka['id']?>"><?php echo $tvrtka['naziv']?></option>
						<?php endif ?>
					<?php endforeach ?>
				</select>
			</div>
			<div class="col-md-9"></div>
			<div class="col-md-3"> <a class="nav-link" href="<?php echo base_url('/index.php/admin/tvrtka')?>">Dodaj tvrtku koja nije na popisu</a></div>
			<div class="col-12 text-center">
				<button type="submit" class="btn btn-primary">Spremi promjene</button>
			  </div>
		</form>
	</div>
		<h4>Postavke za slanje WhatsApp poruka</h4>
	
	<div class="row">
		
			<?php if(isset($validation)):?>
	<div class="alert alert-warning">
	   <?= $validation->listErrors() ?>
	</div>
	<?php endif;?>

		<form class="row g-3" action="<?php echo base_url('index.php/FlotaController/UltramsgLibPostavke');?>" method="post">
			<div class="col-md-3">
				<label for="api_url" class="form-label">API URL</label>
				<input type="text" name ="api_url" class="form-control" <?php if(isset($UltramsgLibConf)):?> value="<?php echo $UltramsgLibConf['api_url'] ?>"  <?php elseif(isset($input)): ?> value="<?php echo $input['api_url'] ?>" <?php else: ?> placeholder="api_url"  <?php endif ?></input>
				<input type="hidden" name="fleet_id" value="<?php echo $flota['id']?>">
				<?php if(isset($UltramsgLibConf)):?> <input type="hidden" name="id" value="<?php echo $UltramsgLibConf['id']?>">  <?php endif ?>

			</div>
			<div class="col-md-3">
				<label for="Instance_ID" class="form-label">Instance ID</label>
				<input type="text" name ="Instance_ID" class="form-control" <?php if(isset($UltramsgLibConf)):?> value="<?php echo $UltramsgLibConf['Instance_ID'] ?>"  <?php elseif(isset($input['Instance_ID'])): ?> value="<?php echo $input['Instance_ID'] ?>" <?php else: ?> placeholder="Instance_ID"  <?php endif ?></input>				
			</div>
			<div class="col-md-3">
				<label for="Token" class="form-label">Token</label>
				<input type="text" name ="Token" class="form-control" <?php if(isset($UltramsgLibConf)):?> value="<?php echo $UltramsgLibConf['Token'] ?>"  <?php elseif(isset($input['Token'])): ?> value="<?php echo $input['Token'] ?>" <?php else: ?> placeholder="Token"  <?php endif ?></input>				
			</div>
			<div class="col-12 text-center">
				<button type="submit" class="btn btn-primary">Spremi promjene</button>
			  </div>
		</form>
	</div>

</div>



