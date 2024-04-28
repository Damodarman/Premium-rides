<div class="row m-5">
		<div class="d-grid gap-2">
	  <a href="<?php echo base_url('/index.php/drivers/')?>" class="btn btn-success" role="button" >Pogledaj aktivne vozače</a>
</div>

        <table id="example" class="table table-striped table-dark table-sm" style="width:100%">
            <thead>
                <tr>
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
					<td><a class="text-decoration-none" href="<?php echo base_url('/index.php/drivers/'). '/' .$driver['id']?>"><?php echo $driver['vozac'] ?></a></td>
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