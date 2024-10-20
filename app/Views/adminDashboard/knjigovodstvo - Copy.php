<div class="container">
	<div class="row">
		<div class="col-1"></div>
		<div class="col-10">
			</br><h3 class="text-center text-dark"> Evidencija korištenja vozila za period od <?php echo $prviDan ?> do <?php echo $zadnjiDan ?></h3>
			<table class="table table-bordered">

				<thead>
					<tr>
						<th> br.</th>
						<th> Zakupodavaoc</th>
						<th> Vozilo</th>
						<th> Registarska Oznaka</th>
						<th> Prijeđeni km</th>
					</tr>
				</thead>
				<tbody>
					<?php $redniBr = 0; ?>
					<?php foreach($knjigoReport as $rep): ?>
						<?php $redniBr += 1; ?>
					
					<tr>
						<td><a href="<?php echo site_url('placanjeNajam/'). '/' .$rep['id']?>"><?php echo $redniBr ?></a></td>
						<td><?php echo $rep['vozac'] ?></td>
						<td><?php echo $rep['proizvodac'].' '.$rep['model'] ?></td>
						<td><?php echo $rep['reg'] ?></td>
						<td><?php echo $rep['prijedeniKm'] ?></td>
					</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<div class="col-1"></div>
	</div>
</div>