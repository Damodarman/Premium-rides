
<h1>Requested amount is: <?php echo $total_sum; ?></h1>
<h1>Generated amount is: <?php echo $totalsum; ?></h1>

<form class="row g-3" action="<?php echo base_url('index.php/NumberGenerationController/saveRacuna');?>" method="post">
	<button type="submit" class="btn btn-primary">Unesi u bazu</button>
</form>
<?= $table ?>