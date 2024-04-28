<div class="container">
	<div class="row mt-5">
			<?php if (session()->has('msgProdajnoMjesto')){ ?>
				<div class="alert <?=session()->getFlashdata('alert-class') ?>">
					<?=session()->getFlashdata('msgProdajnoMjesto') ?>
				</div>
			<?php } ?>

		<form class="row g-3" action="<?php echo base_url('index.php/KnjigovodstvoController/addPrMjTrSave');?>" method="post">
			<div class="mb-3">
			<label for="trgovci_id" class="form-label">Trgovci:</label>
			<select class="form-select" name="trgovci_id">
			  <option selected>Odaberi trgovca s popisa</option>
				<?php foreach($trgovci as $trgovac): ?>
			  <option value="<?php echo $trgovac['id'] ?>"><?php echo $trgovac['nazivTrgovca'] ?></option>
				<?php endforeach ?>
			</select>
			<a class="nav-link" href="<?php echo base_url('/index.php/addTrgovca')?>">Dodaj Trgovca ako ga nema na popisu</a>
			</div>
		  <div class="mb-3">
			<label for="mjestoBroj" class="form-label">Prodajno mjesto broj:</label>
			<input type="text" class="form-control" name="mjestoBroj">
		  </div>
		  <div class="mb-3">
			<label for="poDanu" class="form-label">Broj računa po danu:</label>
			<input type="text" class="form-control" name="poDanu">
		  </div>
		  <button type="submit" class="btn btn-primary">Submit</button>
		</form>
	</div>
</div>