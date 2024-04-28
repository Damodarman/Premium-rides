<div class="container">
	<div class="row">
        <?php if (session()->has('dugPlacen')){ ?>
            <div class="alert <?=session()->getFlashdata('alert-class') ?>">
                <?=session()->getFlashdata('dugPlacen') ?>
            </div>
        <?php } ?>
		<h1>Editiraj  <?php echo $page ?></h1>
		<form class="row g-3" action="<?php echo base_url('index.php/AdminController/spremiDug');?>" method="post">
						<input hidden type="text" name="id" value="<?php echo $dug['id']?>">
			<input class="form-control" type="text" name="iznos" value="<?php echo $dug['iznos']?>">
			<button type="submit" class="btn btn-primary">Spremi promjene</button>
		</form>
	</div>
</div>