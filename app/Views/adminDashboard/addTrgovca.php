<div class="container">
	<?php if (session()->has('msgTrgovac')){ ?>
		<div class="alert <?=session()->getFlashdata('alert-class') ?>">
			<?=session()->getFlashdata('msgTrgovac') ?>
		</div>
	<?php } ?>

	<form class="row g-3" action="<?php echo base_url('index.php/KnjigovodstvoController/addTrgovcaSave');?>" method="post">
	  <div class="mb-3">
		<label for="nazivTrgovca" class="form-label">Naziv Trgovca</label>
		<input type="text" class="form-control" name="nazivTrgovca">
	  </div>
	  <div class="mb-3">
		<label for="adresaTrgovca" class="form-label">Adresa Trgovca</label>
		<input type="text" class="form-control" name="adresaTrgovca">
	  </div>
	  <div class="mb-3">
		<label for="gradTrgovca" class="form-label">Grad Trgovca</label>
		<input type="text" class="form-control" name="gradTrgovca">
	  </div>
	  <div class="mb-3">
		<label for="porezniBrojTrgovca" class="form-label">Porezni broj Trgovca</label>
		<input type="text" class="form-control" name="porezniBrojTrgovca">
	  </div>
	  <div class="mb-3">
		<label for="postanskiBroj" class="form-label">Poštanski broj</label>
		<input type="text" class="form-control" name="postanskiBroj">
	  </div>
	  <button type="submit" class="btn btn-primary">Dodaj novog Trgovca</button>
	</form>
</div>