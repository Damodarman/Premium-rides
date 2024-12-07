<?php



?>
<div class="container">
<?php if(isset($status) || isset($statusOk)): ?>
    <div class="row">
        <div class="col-6">
            <div class="card text-white bg-success mb-3">
                <div class="card-header"><?= $countSucces ?> Uspješno poslanih poruka</div>
                <ul class="list-group list-group-flush">
                    <?php if($statusOk): ?>
                        <?php foreach($statusOk as $successfulStatus): ?>
                            <li class="list-group-item"><?= $successfulStatus ?></li>
                        <?php endforeach ?>
                    <?php else: ?>
                        <li class="list-group-item">Nema uspješno poslanih poruka</li>
                    <?php endif ?>
                </ul>
            </div>
        </div>
        <div class="col-6">
            <div class="card text-dark bg-warning mb-3">
                <div class="card-header"><?= $countError ?> Neuspješno poslanih poruka</div>
                <ul class="list-group list-group-flush">
                    <?php if($status): ?>
                        <?php foreach($status as $errorStatus): ?>
                            <li class="list-group-item"><?= $errorStatus ?></li>
                        <?php endforeach ?>
                    <?php else: ?>
                        <li class="list-group-item">Nema neuspješno poslanih poruka</li>
                    <?php endif ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif ?>
	<div class="row">
		
			<?php if(isset($validation)):?>
	<div class="alert alert-warning">
	   <?= $validation->listErrors() ?>
	</div>
	<?php endif;?>
		<?php if (session()->has('msgPoruka')){ ?>
			<div class="alert <?=session()->getFlashdata('alert-class') ?>">
				<?=session()->getFlashdata('msgPoruka') ?>
			</div>
		<?php } ?>

		<form class="row g-3" action="<?php echo site_url('AdminController/sendmsg');?>" method="post">
		<label for="exampleDataList" class="form-label">Odaberi kome želiš poslati poruku: </label>
		<input name="vozac" class="form-control" list="datalistOptions" id="exampleDataList" placeholder="Type to search...">
		<datalist id="datalistOptions">
			<?php foreach($contacts as $contact): ?>
			<option value="<?php echo $contact['vozac'] ?>" >

			<? endforeach ?>
		</datalist>
			<div class="col-12">
				<label for="poruka" class="form-label">Poruka</label>
				<textarea class="form-control" name ="poruka" placeholder="poruka ...."  rows="3" cols="20"></textarea>

			</div>

			<div class="col-12 text-center">
				<button type="submit" class="btn btn-primary">Pošalji poruku</button>
			  </div>
		</form>
	</div>
	<hr>
	
	
	<div class="row">
		<?php if (session()->has('msgMultiple')){ ?>
			<div class="alert <?=session()->getFlashdata('alert-class') ?>">
				<?=session()->getFlashdata('msgMultiple') ?>
			</div>
		<?php } ?>
		
		<h1 class="alert alert-danger"> Oprezno koristiti, moguće je slanje poruka svim Vozačima!!!</h1>
		
		<h3>Označi vozače kojima želiš poslati poruku:</h3>
		<form class="row g-3" action="<?php echo site_url('sendMultipleMsg');?>" method="post">
        <!-- Select All checkbox -->
        <label class="form-check-label col-12 border-bottom">
            <input type="checkbox" id="select-all" class="form-check-input"> Označi sve
        </label>
			
		<?php foreach($contacts as $contact):?>
			<label class="form-check-label col-3 border-bottom"><input class="form-check-input" type="checkbox" name="driver_ids[]" value="<?=$contact['id']?>">   <?=$contact['vozac']?></label>
		<?php endforeach ?>
			<hr>
			<div class="col-12">
				<label for="poruka" class="form-label">Poruka</label>
				<textarea class="form-control" name ="poruka" placeholder="Poruka ...."  rows="3" cols="20"></textarea>
			</div>
			<p>
				Kako bi dodao ime vozača koristiti "Placeholder" : {{vozac}}. {{vozac}} će se zamijeniti sa imenom i prezimenom vozača.
			</p>
			<div class="col-12 text-center">
				<button type="submit" class="btn btn-primary">Pošalji poruku</button>
			  </div>
		</form>
	</div>
</div>


<script>
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input[name="driver_ids[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
</script>