<div class="row m-5">
	<div class="d-grid gap-2">
	  <a href="<?php echo site_url('neaktdrivers/')?>" class="btn btn-danger" role="button" >Pogledaj neaktivne vozače</a>
</div>
	<div class="row">
		<h3 class="text-center">Pregled radnih sati vozača</h3>
		<div class="col-6 border border-dark bg-dark pt-2 pb-2">
			<div class="d-grid">
				<a href="<?php echo site_url('naProviziju/'); ?>" class="btn btn-info" role="button">Na Proviziju</a>
			</div>
		</div>
		<div class="col-6 border border-dark bg-dark pt-2 pb-2">
			<div class="d-grid">
				<a href="<?php echo site_url('naPlacu/'); ?>" class="btn btn-secondary" role="button">Na plaću</a>
			</div>
		</div>
	</div>
	<table id="example" class="table table-striped table-dark table-sm" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Vozač</th>
                    <th>email</th>
                    <th>Mobitel</th>
                    <th>Datum rođenja</th>
                    <th>Uber</th>
                    <th>Bolt</th>
                    <th>Taxi</th>
                    <th>MyPos</th>
                    <th>Referral</th>
                    <th>Referral nagrada</th>
                    <th>Provizija</th>
                    <th>Iznos Provizije</th>
                    <th>Popust</th>
                    <th>Prijava</th>
                    <th>Broj Sati</th>
                </tr>
            </thead>
            <tbody>
				<?php foreach($drivers as $driver):  ?>
                <tr>
                    <td><?php echo $driver['id'] ?></td>
					<td><a class="text-decoration-none" href="<?php echo site_url('drivers/'). '/' .$driver['id']?>"><?php echo $driver['vozac'] ?></a></td>
                    <td><?php echo $driver['email'] ?></td>
                    <td><?php echo $driver['mobitel'] ?></td>
                    <td><?php echo $driver['dob'] ?> </td>
                    <td><?php if ($driver['uber'] != true ): ?> <i class="bi bi-x-circle" style="font-size: 1.5rem; color: red;"></i> <?php else: ?><i class="bi bi-check-circle" style="font-size: 1.5rem; color: green;"></i> <?php endif?></td>
                    <td><?php if ($driver['bolt'] != true ): ?> <i class="bi bi-x-circle" style="font-size: 1.5rem; color: red;"></i> <?php else: ?><i class="bi bi-check-circle" style="font-size: 1.5rem; color: green;"></i> <?php endif?></td>
                    <td><?php if ($driver['taximetar'] != true ): ?> <i class="bi bi-x-circle" style="font-size: 1.5rem; color: red;"></i> <?php else: ?><i class="bi bi-check-circle" style="font-size: 1.5rem; color: green;"></i> <?php endif?></td>
                    <td><?php if ($driver['myPos'] != true ): ?> <i class="bi bi-x-circle" style="font-size: 1.5rem; color: red;"></i> <?php else: ?><i class="bi bi-check-circle" style="font-size: 1.5rem; color: green;"></i> <?php endif?></td>
                    <td><?php echo $driver['refered_by'] ?></td>
                    <td><?php if ($driver['vrsta_provizije'] != 'Postotak')
						{ 
							echo $driver['referal_reward']. ' €';
						}
						else{
							echo $driver['referal_reward']. ' %';
						}
						?></td>
                    <td><?php echo $driver['vrsta_provizije'] ?></td>
                    <td><?php if ($driver['vrsta_provizije'] != 'Postotak')
						{ 
							echo $driver['iznos_provizije']. ' €';
						}
						else{
							echo $driver['iznos_provizije']. ' %';
						}
						?></td>
                    <td><?php if ($driver['vrsta_provizije'] != 'Postotak')
						{ 
							echo $driver['popust_na_proviziju']. ' €';
						}
						else{
							echo $driver['popust_na_proviziju']. ' %';
						}
						?></td>
                    <td><?php if ($driver['prijava'] != true ): ?> <i class="bi bi-x-circle" style="font-size: 1.5rem; color: red;">0</i> <?php else: ?><i class="bi bi-check-circle" style="font-size: 1.5rem; color: green;">1</i> <?php endif?></td>
                    <td><?php echo $driver['broj_sati'] ?></td>
                </tr>
				<?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Vozač</th>
                    <th>email</th>
                    <th>Mobitel</th>
                    <th>Datum rođenja</th>
                    <th>Uber</th>
                    <th>Bolt</th>
                    <th>Taxi</th>
                    <th>MyPos</th>
                    <th>Referral</th>
                    <th>Referral nagrada</th>
                    <th>Provizija</th>
                    <th>Iznos Provizije</th>
                    <th>Popust</th>
                    <th>Prijava</th>
                    <th>Broj Sati</th>
   				</tr>
            </tfoot>
        </table>

    </div>
</body>

<script>
$(document).ready(function () {
    $('#example').DataTable();
});
</script>