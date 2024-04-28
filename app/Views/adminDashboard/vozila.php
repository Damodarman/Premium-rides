<div class="row m-5">
	<table id="example" class="table table-striped table-dark table-sm" style="width:100%">
		<thead>
			<tr>
				<th>ID</th>
				<th>Proizvođać</th>
				<th>Model</th>
				<th>Registracija</th>
				<th>Vozač</th>
				<th>Plaća firma ?</th>
				<th>Cijana</th>
			</tr>
		</thead>
		            <tbody>
				<?php foreach($vozila as $vozilo):  ?>
                <tr>
					<td><?php echo $vozilo['id'] ?></td>
					<td><?php echo $vozilo['proizvodac'] ?></td>
					<td><?php echo $vozilo['model'] ?></td>
					<td><?php echo $vozilo['reg'] ?></td>
					<td><a class="text-decoration-none" href="<?php echo base_url('/index.php/drivers/'). '/' .$vozilo['vozac_id']?>"><?php echo $vozilo['vozac'] ?></a></td>
					<td><?php echo $vozilo['placa_firma'] ?></td>
					<td><?php echo $vozilo['cijena_tjedno'] ?></td>
				<?php endforeach ?>
						</tr>
		</tbody>
	</table>

</div>